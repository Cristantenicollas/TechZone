<?php
require_once __DIR__ . "/includes/functions.php";
require_once __DIR__ . "/includes/conexao.php";

// Destaques: PCs prontos marcados como destaque, os mais recentes primeiro.
// Vitrine: 4 PCs marcados como destaque.
$destaques = $conexao->query(
    "SELECT p.*, c.nome categoria
     FROM produtos p
     INNER JOIN categorias c ON c.id = p.categoria_id
     WHERE c.tipo = 'pc' AND p.destaque = 1
     ORDER BY p.id
     LIMIT 4"
);

$maisVendidos = $conexao->query(
    "SELECT p.*, c.nome categoria
     FROM produtos p
     INNER JOIN categorias c ON c.id = p.categoria_id
     WHERE c.tipo = 'peca' AND p.estoque > 0
     ORDER BY p.destaque DESC, p.id DESC
     LIMIT 8"
);

$categorias = $conexao->query(
    "SELECT c.*, COUNT(p.id) total
     FROM categorias c
     LEFT JOIN produtos p ON p.categoria_id = c.id
     WHERE c.tipo = 'peca'
     GROUP BY c.id
     ORDER BY c.nome"
);

$totalPecas = $conexao->query(
    "SELECT COUNT(*) total
     FROM produtos p
     INNER JOIN categorias c ON c.id = p.categoria_id
     WHERE c.tipo = 'peca'"
)->fetch_assoc()["total"];

$totalPcs = $conexao->query(
    "SELECT COUNT(*) total
     FROM produtos p
     INNER JOIN categorias c ON c.id = p.categoria_id
     WHERE c.tipo = 'pc'"
)->fetch_assoc()["total"];

$titulo = NOME_PROJETO . " - Tecnologia para quem joga, trabalha e sonha";
require_once __DIR__ . "/includes/header.php";
?>
<section class="hero">
    <div class="hero-content">
        <span class="tag">TECNOLOGIA - PERFORMANCE - SETUP</span>
        <h1>Monte o PC <span>dos seus sonhos.</span></h1>
        <p><?= (int) $totalPecas; ?> componentes a sua escolha e PCs prontos com tudo configurado. Monte ou compre.</p>
        <a class="btn" href="<?= url("paginas/pcs_prontos.php") ?>">Ver <?= (int) $totalPcs; ?> PCs prontos </a>
        <a class="btn secondary" href="<?= url("paginas/monte_pc.php") ?>">Monte seu PC</a>
    </div>
    <div class="hero-pc">
        <div class="pc-glow"></div>
        <div class="fake-case"></div>
        <strong>TECHZONE</strong>
        <small>Performance para o seu proximo nivel.</small>
    </div>
</section>

<section class="section">
    <div class="section-title">
        <div>
            <span class="section-tag">DESTAQUES</span>
            <h2>PCs prontos</h2>
            <p>Configuracoes completas, testadas e prontas para usar.</p>
        </div>
        <a href="<?= url("paginas/pcs_prontos.php") ?>">Ver todos</a>
    </div>

    <div class="grid">
        <?php while ($pc = $destaques->fetch_assoc()): ?>
            <article class="card">
                <?= placeholderImagem($pc) ?>
                <small><?= e($pc["categoria"]) ?></small>
                <h3><?= e($pc["nome"]) ?></h3>
                <p><?= e($pc["descricao"]) ?></p>
                <strong><?= preco($pc["preco"]) ?></strong>
                <a class="btn small" href="<?= url("paginas/produto.php") ?>?id=<?= (int) $pc["id"] ?>">Detalhes</a>
                <a class="btn small secondary" href="<?= url("paginas/carrinho.php") ?>?adicionar=<?= (int) $pc["id"] ?>">Comprar</a>
            </article>
        <?php endwhile; ?>
    </div>
</section>

<section class="section">
    <div class="section-title">
        <div>
            <span class="section-tag">COMPONENTES</span>
            <h2>Escolha suas pecas</h2>
            <p><?= $totalPecas; ?> itens disponÃ­veis em <?= $categorias->num_rows; ?> categorias.</p>
        </div>
        <a href="<?= url("paginas/produtos.php") ?>">Ver catalogo</a>\n        </div>

    <div class="categories">
        <?php while ($categoria = $categorias->fetch_assoc()): ?>
            <a href="<?= url("paginas/produtos.php") ?>?categoria=<?= (int) $categoria["id"] ?>">
                <strong><?= e(iconeProduto($categoria, "")) ?> <?= e($categoria["nome"]) ?></strong>
                <p><?= e($categoria["descricao"]) ?></p>
                <small><?= (int) $categoria["total"]; ?> itens</small>
            </a>
        <?php endwhile; ?>
    </div>
</section>

<section class="section">
    <div class="section-title">
        <div>
            <span class="section-tag">MAIS VENDIDOS</span>
            <h2>Pecas em alta</h2>
            <p>Os componentes mais procurados do momento.</p>
        </div>
        <a href="<?= url("paginas/produtos.php") ?>">Ver todos</a>
    </div>

    <div class="grid">
        <?php while ($p = $maisVendidos->fetch_assoc()): ?>
            <article class="card">
                <?= placeholderImagem($p, "small-image") ?>
                <small><?= e($p["categoria"]) ?></small>
                <h3><?= e($p["nome"]) ?></h3>
                <strong><?= preco($p["preco"]) ?></strong>
                <a class="btn small secondary" href="<?= url("paginas/carrinho.php") ?>?adicionar=<?= (int) $p["id"] ?>">Comprar</a>
            </article>
        <?php endwhile; ?>
    </div>
</section>

<section class="section">
    <div class="card cta">
        <div>
            <span class="section-tag">SEU SETUP</span>
            <h2>Quer montar do zero?</h2>
            <p>Escolha cada componente e monte uma configuracao personalizada.</p>
        </div>
        <a class="btn" href="<?= url("paginas/monte_pc.php") ?>">Comecar montagem</a>
    </div>
</section>
<?php require_once __DIR__ . "/includes/footer.php"; ?>














