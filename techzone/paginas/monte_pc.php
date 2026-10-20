<?php
require_once __DIR__ . "/../includes/functions.php";
require_once __DIR__ . "/../includes/conexao.php";

// ------------------------------------------------------------------ Montagem
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $selecionados = $_POST["peca"] ?? [];
    $adicionados  = 0;
    $total        = 0;

    foreach ((array) $selecionados as $id) {
        $id = (int) $id;
        if ($id <= 0) {
            continue;
        }

        $stmt = $conexao->prepare("SELECT id, nome, preco, icone, estoque FROM produtos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $peca = $stmt->get_result()->fetch_assoc();

        if ($peca && adicionarAoCarrinho($peca) > 0) {
            $adicionados++;
            $total += (float) $peca["preco"];
        }
    }

    if ($adicionados > 0) {
        $_SESSION["montagem"] = ["total" => $total];
        redirecionar("paginas/carrinho.php");
    }
}

// ------------------------------------------------------------------ Listagem
// Todas as categorias de peças viram um campo de seleção.
$essenciais = $conexao->query(
    "SELECT id, nome
     FROM categorias
     WHERE tipo = 'peca'
     ORDER BY nome"
);

$titulo = "Monte seu PC - " . NOME_PROJETO;
require_once __DIR__ . "/../includes/header.php";
?>
<section class="page-header">
    <span class="tag">CONFIGURADOR</span>
    <h1>Monte seu PC</h1>
    <p>Escolha uma peça por categoria. As escolhas vão para o carrinho.</p>
</section>

<form class="builder" method="POST">
    <?php while ($categoria = $essenciais->fetch_assoc()): ?>
        <?php
        $categoriaId = (int) $categoria["id"];

        $stmt = $conexao->prepare(
            "SELECT id, nome, preco, icone, estoque
             FROM produtos
             WHERE categoria_id = ? AND estoque > 0
             ORDER BY nome"
        );
        $stmt->bind_param("i", $categoriaId);
        $stmt->execute();
        $pecas = $stmt->get_result();
        ?>
        <div class="builder-row">
            <div>
                <strong><?= e($categoria["nome"]) ?></strong>
                <small>Selecione uma peça</small>
            </div>

            <?php if ($pecas->num_rows === 0): ?>
                <p class="stock out">Sem peças disponíveis</p>
            <?php else: ?>
                <select name="peca[<?= $categoriaId ?>]">
                    <option value="">Selecionar</option>
                    <?php while ($peca = $pecas->fetch_assoc()): ?>
                        <option value="<?= (int) $peca["id"] ?>">
                            <?= e(iconeProduto($peca)) ?> <?= e($peca["nome"]) ?> — <?= preco($peca["preco"]) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>

    <button class="btn" type="submit">Adicionar configuração ao carrinho</button>
</form>
<?php require_once __DIR__ . "/../includes/footer.php"; ?>
