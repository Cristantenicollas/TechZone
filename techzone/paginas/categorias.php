<?php
require_once __DIR__ . "/../includes/functions.php";
require_once __DIR__ . "/../includes/conexao.php";

$pecas = $conexao->query(
    "SELECT c.*, COUNT(p.id) total
     FROM categorias c
     LEFT JOIN produtos p ON p.categoria_id = c.id
     WHERE c.tipo = 'peca'
     GROUP BY c.id
     ORDER BY c.nome"
);

$pcs = $conexao->query(
    "SELECT COUNT(*) total
     FROM produtos p
     INNER JOIN categorias c ON c.id = p.categoria_id
     WHERE c.tipo = 'pc'"
)->fetch_assoc()["total"];

$titulo = "Categorias - " . NOME_PROJETO;
require_once __DIR__ . "/../includes/header.php";
?>
<section class="page-header">
    <span class="tag">CATEGORIAS</span>
    <h1>Componentes</h1>
    <p>Escolha uma categoria para ver as pecas disponiveis.</p>
</section>

<div class="categories">
    <?php while ($categoria = $pecas->fetch_assoc()): ?>
        <a href="<?= url("paginas/produtos.php") ?>?categoria=<?= (int) $categoria["id"] ?>">
            <strong><?= e(iconeProduto($categoria, "")) ?> <?= e($categoria["nome"]) ?></strong>
            <p><?= e($categoria["descricao"]) ?></p>
            <small><?= (int) $categoria["total"]; ?> itens</small>
        </a>
    <?php endwhile; ?>
</div>

<section class="section">
    <div class="card cta">
        <div>
            <span class="section-tag">NA PRONTO</span>
            <h2><?= (int) $pcs; ?> PCs prontos a venda</h2>
            <p>Configuracoes completas</p>
        </div>
        <a class="btn" href="<?= url("paginas/pcs_prontos.php") ?>">Ver PCs prontos</a>\n        </div>
</section>
<?php require_once __DIR__ . "/../includes/footer.php"; ?>








