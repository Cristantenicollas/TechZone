<?php
require_once __DIR__ . "/../includes/functions.php";
require_once __DIR__ . "/../includes/conexao.php";

$id = (int) ($_GET["id"] ?? 0);

$stmt = $conexao->prepare(
    "SELECT p.*, c.nome categoria, c.tipo categoria_tipo
     FROM produtos p
     INNER JOIN categorias c ON c.id = p.categoria_id
     WHERE p.id = ?"
);
$stmt->bind_param("i", $id);
$stmt->execute();
$produto = $stmt->get_result()->fetch_assoc();

if (!$produto) {
    redirecionar("paginas/produtos.php");
}

// Sugere peças parecidas da mesma categoria (exceto o próprio produto).
$stmt = $conexao->prepare(
    "SELECT id, nome, preco, icone, imagem
     FROM produtos
     WHERE categoria_id = ? AND id <> ?
     ORDER BY destaque DESC, RANDOM()
     LIMIT 4"
);
$stmt->bind_param("ii", $produto["categoria_id"], $id);
$stmt->execute();
$relacionados = $stmt->get_result();

$ehPc = $produto["categoria_tipo"] === "pc";
$voltaPara = $ehPc ? "paginas/pcs_prontos.php" : "paginas/produtos.php";

$titulo = $produto["nome"] . " - " . NOME_PROJETO;
require_once __DIR__ . "/../includes/header.php";
?>
<section class="detail">
    <?= placeholderImagem($produto, "big") ?>

    <div>
        <span class="tag"><?= e($produto["categoria"]) ?></span>
        <h1><?= e($produto["nome"]) ?></h1>
        <p><?= e($produto["descricao"]) ?></p>

        <strong class="price"><?= preco($produto["preco"]) ?></strong>

        <?php if ((int) $produto["estoque"] > 0): ?>
            <p>Em estoque: <?= (int) $produto["estoque"]; ?> unidades</p>
            <a class="btn" href="carrinho.php?adicionar=<?= (int) $produto["id"] ?>">Adicionar ao carrinho</a>
        <?php else: ?>
            <p class="stock out">Produto esgotado</p>
        <?php endif; ?>

        <a class="btn secondary" href="<?= url($voltaPara) ?>">← Voltar</a>
    </div>
</section>

<section class="section">
    <div class="section-title">
        <div>
            <span class="section-tag">RELACIONADOS</span>
            <h2>Veja também</h2>
            <p>Outros itens de <?= e(strtolower($produto["categoria"])) ?>.</p>
        </div>
    </div>

    <div class="grid">
        <?php while ($outro = $relacionados->fetch_assoc()): ?>
            <article class="card">
                <?= placeholderImagem($outro, "small-image") ?>
                <h3><?= e($outro["nome"]) ?></h3>
                <strong><?= preco($outro["preco"]) ?></strong>
                <a class="btn small" href="produto.php?id=<?= (int) $outro["id"] ?>">Detalhes</a>
                <a class="btn small secondary" href="carrinho.php?adicionar=<?= (int) $outro["id"] ?>">Comprar</a>
            </article>
        <?php endwhile; ?>
    </div>
</section>
<?php require_once __DIR__ . "/../includes/footer.php"; ?>
