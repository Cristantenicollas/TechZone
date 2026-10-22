<?php
/**
 * Migra o banco techzone do MySQL (local) para o PostgreSQL remoto.
 *
 * Uso: php database/migrar-para-postgres.php
 *
 * 1. Recria as tabelas no PostgreSQL (apaga o que estiver lá).
 * 2. Copia todas as linhas do MySQL local (XAMPP).
 * 3. Ajusta as sequences dos SERIAL para o próximo id.
 */

mysqli_report(MYSQLI_REPORT_OFF);

$mysqlHost = "localhost";
$mysqlUser = "root";
$mysqlPass = "";
$mysqlName = "techzone";

$pgHost = "192.168.10.37";
$pgPort = 5432;
$pgUser = "postgres";
$pgPass = "nick2509";
$pgName = "techzone";

// ------------------------------------------------------------------ MySQL
$mysql = @new mysqli($mysqlHost, $mysqlUser, $mysqlPass, $mysqlName);

if ($mysql->connect_errno) {
    fwrite(STDERR, "MySQL local indisponivel: {$mysql->connect_error}\n");
    fwrite(STDERR, "Inicie o XAMPP e tente de novo.\n");
    exit(1);
}

$mysql->set_charset("utf8mb4");

// --------------------------------------------------------------- Postgres
$dsn = sprintf(
    "host=%s port=%d dbname=%s user=%s password=%s connect_timeout=5",
    $pgHost,
    $pgPort,
    $pgName,
    $pgUser,
    str_replace(["\\", "'"], ["\\\\", "\\'"], $pgPass)
);

$pg = @pg_connect($dsn);

if (!$pg) {
    fwrite(STDERR, "PostgreSQL indisponivel em {$pgHost}:{$pgPort}\n");
    exit(1);
}

pg_set_client_encoding($pg, "UTF8");

function pgExec($pg, string $sql): void
{
    $res = @pg_query($pg, $sql);

    if ($res === false) {
        fwrite(STDERR, "Erro SQL: " . pg_last_error($pg) . "\nSQL: " . substr($sql, 0, 300) . "\n");
        exit(1);
    }
}

// ------------------------------------------------------------------- DDL
echo "Recriando tabelas no PostgreSQL...\n";

pgExec($pg, "DROP TABLE IF EXISTS itens_pedido, pedidos, produtos, categorias, usuarios CASCADE");

$ddl = [
    "CREATE TABLE usuarios (
        id SERIAL PRIMARY KEY,
        nome VARCHAR(100) NOT NULL,
        email VARCHAR(150) NOT NULL UNIQUE,
        senha VARCHAR(255) NOT NULL,
        tipo VARCHAR(10) NOT NULL DEFAULT 'cliente',
        data_cadastro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    )",
    "CREATE TABLE categorias (
        id SERIAL PRIMARY KEY,
        nome VARCHAR(100) NOT NULL UNIQUE,
        descricao TEXT,
        icone VARCHAR(16),
        tipo VARCHAR(10) NOT NULL DEFAULT 'peca'
    )",
    "CREATE TABLE produtos (
        id SERIAL PRIMARY KEY,
        categoria_id INT NOT NULL REFERENCES categorias(id) ON DELETE RESTRICT,
        nome VARCHAR(150) NOT NULL,
        descricao TEXT NOT NULL,
        preco NUMERIC(10,2) NOT NULL,
        estoque INT NOT NULL DEFAULT 0,
        icone VARCHAR(16),
        destaque SMALLINT NOT NULL DEFAULT 0,
        imagem VARCHAR(255),
        data_cadastro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        CONSTRAINT uniq_produto UNIQUE (nome, categoria_id)
    )",
    "CREATE TABLE pedidos (
        id SERIAL PRIMARY KEY,
        usuario_id INT NOT NULL REFERENCES usuarios(id) ON DELETE CASCADE,
        data_pedido TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        status VARCHAR(50) NOT NULL DEFAULT 'Pendente',
        total NUMERIC(10,2) NOT NULL
    )",
    "CREATE TABLE itens_pedido (
        id SERIAL PRIMARY KEY,
        pedido_id INT NOT NULL REFERENCES pedidos(id) ON DELETE CASCADE,
        produto_id INT NOT NULL REFERENCES produtos(id) ON DELETE RESTRICT,
        quantidade INT NOT NULL,
        preco_unitario NUMERIC(10,2) NOT NULL
    )",
];

foreach ($ddl as $sql) {
    pgExec($pg, $sql);
}

// ------------------------------------------------------------- Cópia
// Ordem respeita as chaves estrangeiras.
$tabelas = ["usuarios", "categorias", "produtos", "pedidos", "itens_pedido"];

foreach ($tabelas as $tabela) {
    $resultado = $mysql->query("SELECT * FROM `$tabela`");

    if (!$resultado) {
        fwrite(STDERR, "Falha ao ler {$tabela} no MySQL: {$mysql->error}\n");
        exit(1);
    }

    $colunas = array_map(fn($f) => $f->name, $resultado->fetch_fields());
    $total   = 0;

    while ($linha = $resultado->fetch_assoc()) {
        $params = [];
        $values = [];

        foreach ($colunas as $i => $coluna) {
            $params[]  = "\$" . ($i + 1);
            $values[]  = $linha[$coluna];
        }

        $sql = "INSERT INTO {$tabela} (" . implode(", ", $colunas) . ") VALUES (" . implode(", ", $params) . ")";
        $res = @pg_query_params($pg, $sql, $values);

        if ($res === false) {
            fwrite(STDERR, "Erro ao copiar linha de {$tabela}: " . pg_last_error($pg) . "\n");
            exit(1);
        }

        $total++;
    }

    // Volta a sequence para o próximo id depois das linhas copiadas.
    pgExec(
        $pg,
        "SELECT setval(pg_get_serial_sequence('{$tabela}', 'id'),
                       COALESCE((SELECT MAX(id) FROM {$tabela}), 0) + 1, false)"
    );

    echo "  {$tabela}: {$total} linha(s)\n";
}

echo "Migracao concluida: MySQL -> PostgreSQL ({$pgHost}:{$pgPort}/{$pgName})\n";
