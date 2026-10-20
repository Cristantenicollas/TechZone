<?php
/**
 * Conexão com o PostgreSQL.
 *
 * O site foi migrado de MySQL para PostgreSQL. Para não reescrever as
 * páginas, esta camada expõe a mesma API usada no projeto
 * (query, prepare, bind_param, execute, get_result, fetch_assoc,
 * insert_id, begin_transaction...) por cima das funções pg_*.
 *
 * Se o banco não estiver no ar, mostra uma página de erro em vez de
 * despejar um stack trace na frente do usuário.
 */

require_once __DIR__ . "/config.php";

// ------------------------------------------------------------------ Resultado
/** Linhas de uma consulta, com a mesma leitura de uma mysqli_result. */
final class PgResultado
{
    private array $linhas;
    private int $posicao = 0;
    public int $num_rows;

    public function __construct(array $linhas)
    {
        $this->linhas   = $linhas;
        $this->num_rows = count($linhas);
    }

    public function fetch_assoc(): ?array
    {
        if (!isset($this->linhas[$this->posicao])) {
            return null;
        }

        return $this->linhas[$this->posicao++];
    }

    public function free(): void
    {
        $this->linhas   = [];
        $this->num_rows = 0;
    }
}

// ----------------------------------------------------------------- Statement
/** Statement compatível com mysqli_stmt sobre pg_query_params. */
final class PgStatement
{
    public int $insert_id = 0;

    private PgConexao $conexao;
    private string $sqlOriginal;
    private string $sqlPreparada;
    private string $tipos = "";
    private array $params = [];
    private ?PgResultado $resultado = null;

    public function __construct(PgConexao $conexao, string $sql)
    {
        $this->conexao     = $conexao;
        $this->sqlOriginal = $sql;
        $this->sqlPreparada = self::trocarPlaceholders($sql);
    }

    /** Converte os "?" do estilo mysqli em "$1, $2..." do PostgreSQL. */
    private static function trocarPlaceholders(string $sql): string
    {
        $saida  = "";
        $indice = 0;
        $dentroDeAspas = false;

        for ($i = 0, $tamanho = strlen($sql); $i < $tamanho; $i++) {
            $caractere = $sql[$i];

            if ($caractere === "'") {
                $dentroDeAspas = !$dentroDeAspas;
            }

            if ($caractere === "?" && !$dentroDeAspas) {
                $indice++;
                $saida .= "\$" . $indice;
                continue;
            }

            $saida .= $caractere;
        }

        return $saida;
    }

    public function bind_param(string $tipos, mixed ...$valores): void
    {
        $this->tipos  = $tipos;
        $this->params = $valores;
    }

    public function execute(): bool
    {
        $parametros = [];

        foreach ($this->params as $i => $valor) {
            $tipo = $this->tipos[$i] ?? "s";

            $parametros[] = match ($tipo) {
                "i"     => (string) (int) $valor,
                "d"     => (string) (float) $valor,
                default => $valor === null ? null : (string) $valor,
            };
        }

        $this->resultado = $this->conexao->executar($this->sqlPreparada, $parametros);

        if (preg_match('/^\s*INSERT\b/i', $this->sqlOriginal)) {
            $this->insert_id = $this->conexao->ultimoId();
        }

        return true;
    }

    public function get_result(): PgResultado
    {
        return $this->resultado ?? new PgResultado([]);
    }
}

// ------------------------------------------------------------------ Conexão
/** Conexão compatível com mysqli sobre pg_connect. */
final class PgConexao
{
    /** @var resource */
    private $conexao;

    public function __construct($conexao)
    {
        $this->conexao = $conexao;
    }

    /** Executa SQL sem parâmetros (mesmo formato de mysqli::query). */
    public function query(string $sql): PgResultado
    {
        return $this->executar($sql, []);
    }

    public function prepare(string $sql): PgStatement
    {
        return new PgStatement($this, $sql);
    }

    /** Executa uma instrução já com os placeholders convertidos. */
    public function executar(string $sql, array $parametros): PgResultado
    {
        if ($parametros) {
            $resultado = @pg_query_params($this->conexao, $sql, $parametros);
        } else {
            $resultado = @pg_query($this->conexao, $sql);
        }

        if ($resultado === false) {
            throw new RuntimeException("Erro no banco de dados: " . pg_last_error($this->conexao));
        }

        if ($resultado instanceof PgSql\Result || is_resource($resultado)) {
            $linhas = @pg_fetch_all($resultado);
            $linhas = $linhas === false ? [] : array_map(
                fn($linha) => array_change_key_case($linha, CASE_LOWER),
                $linhas
            );
            pg_free_result($resultado);

            return new PgResultado($linhas);
        }

        return new PgResultado([]);
    }

    /** Próximo valor da sequence usada no último INSERT. */
    public function ultimoId(): int
    {
        $resultado = @pg_query($this->conexao, "SELECT LASTVAL()");

        if ($resultado === false) {
            return 0;
        }

        return (int) pg_fetch_result($resultado, 0, 0);
    }

    public function begin_transaction(): void
    {
        @pg_query($this->conexao, "BEGIN");
    }

    public function commit(): void
    {
        @pg_query($this->conexao, "COMMIT");
    }

    public function rollback(): void
    {
        @pg_query($this->conexao, "ROLLBACK");
    }
}

// ----------------------------------------------------------------- Conectar
$dsn = sprintf(
    "host=%s port=%d dbname=%s user=%s password=%s connect_timeout=5",
    DB_HOST,
    DB_PORT,
    DB_NAME,
    DB_USER,
    str_replace(["\\", "'"], ["\\\\", "\\'"], DB_PASS)
);

$pgConexao = @pg_connect($dsn);

if ($pgConexao === false) {
    $motivo = "Não foi possível conectar ao PostgreSQL em " . DB_HOST . ":" . DB_PORT
            . " como usuário '" . DB_USER . "'.";
    $detalhe = error_get_last()["message"] ?? "Falha ao conectar.";

    http_response_code(503);

    if (PHP_SAPI === "cli") {
        echo $motivo . "\n" . $detalhe . "\n";
        exit(1);
    }

    $rotulo = htmlspecialchars($motivo, ENT_QUOTES, "UTF-8");
    $saida  = htmlspecialchars($detalhe, ENT_QUOTES, "UTF-8");

    echo <<<HTML
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Banco de dados indisponível - TechZone</title>
        <style>
            body {
                font-family: system-ui, -apple-system, "Segoe UI", sans-serif;
                background: #0f1220;
                color: #e6e8f0;
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 100vh;
                margin: 0;
                padding: 24px;
            }
            .box {
                background: #171a2e;
                border: 1px solid #2a2f4a;
                border-radius: 12px;
                padding: 32px;
                max-width: 620px;
            }
            h1 { font-size: 20px; margin: 0 0 12px; }
            p  { line-height: 1.6; margin: 0 0 12px; }
            code {
                background: #0f1220;
                padding: 2px 6px;
                border-radius: 4px;
                font-size: 14px;
            }
            pre {
                background: #0f1220;
                border-left: 3px solid #e5484d;
                padding: 12px;
                overflow-x: auto;
                font-size: 13px;
                color: #ffb4ab;
            }
            ol { line-height: 1.8; padding-left: 20px; }
        </style>
    </head>
    <body>
        <div class="box">
            <h1>Banco de dados indisponível</h1>
            <p>{$rotulo}</p>
            <p>Detalhe do PostgreSQL:</p>
            <pre>{$saida}</pre>
            <p>Para resolver:</p>
            <ol>
                <li>Confirme que o servidor <code>192.168.10.37:5432</code> está ligado e acessível (rede/MobaXterm).</li>
                <li>Confira <code>DB_HOST</code>, <code>DB_USER</code> e <code>DB_PASS</code> em <code>includes/config.php</code>.</li>
                <li>Para recriar as tabelas e copiar os dados do MySQL local, rode <code>database/migrar-para-postgres.php</code>.</li>
            </ol>
        </div>
    </body>
    </html>
    HTML;

    exit(1);
}

@pg_set_client_encoding($pgConexao, "UTF8");
@pg_query($pgConexao, "SET NAMES 'UTF8'");

$conexao = new PgConexao($pgConexao);

