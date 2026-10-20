<?php
require_once __DIR__ . "/../includes/functions.php";
require_once __DIR__ . "/../includes/conexao.php";

// ------------------------------------------------------------------- Filtros
$busca     = trim($_GET["busca"] ?? "");
$categoria = (int) ($_GET["categoria"] ?? 0);
$ordem     = $_GET["ordem"] ?? "recentes";

$sql = "SELECT p.*, c.nome categoria
        FROM produtos p
        INNER JOIN categorias c ON c.id = p.categoria_id
        WHERE c.tipo = 'peca'";

$params = [];
$types  = "";

if ($busca !== "") {
    $sql .= " AND (p.nome ILIKE ? OR c.nome ILIKE ?)";
    $params[] = "%$busca%";
    $types .= "s";
}

if ($categoria > 0) {
    $sql .= " AND p.categoria_id = ?";
    $params[] = $categoria;
    $types .= "i";
}

$ordens = [
    "recentes" => "p.data_cadastro DESC, p.id DESC",
    "menor"    => "p.preco ASC",
    "maior"    => "p.preco DESC",
    "nome"     => "p.nome ASC",
];

$sql .= " ORDER BY " . ($ordens[$ordem] ?? $ordens["recentes"]);

$stmt = $conexao->prepare($sql);

if ($params) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$produtos = $stmt->get_result();

$categorias = $conexao->query(
    "SELECT c.*, COUNT(p.id) total
     FROM categorias c
     LEFT JOIN produtos p ON p.categoria_id = c.id
     WHERE c.tipo = 'peca'
     GROUP BY c.id
     ORDER BY c.nome"
);

$total = $conexao->query(
    "SELECT COUNT(*) total
     FROM produtos p
     INNER JOIN categorias c ON c.id = p.categoria_id
     WHERE c.tipo = 'peca'"
)->fetch_assoc()["total"];

$titulo = "Produtos - " . NOME_PROJETO;
require_once __DIR__ . "/../includes/header.php";
?>
<section class="page-header">
    <span class="tag">CATALOGO</span>
    <h1>Produtos</h1>
    <p><?= (int) $total; ?> componentes para montar o seu setup.</p>
</section>

<nav class="chips">
    <a class="chip <?= $categoria === 0 ? "active" : "" ?>" href="<?= url("paginas/produtos.php") ?>?<?= http_build_query($qsBase ?? []) ?>">Todas</a>
    <?php while ($c = $categorias->fetch_assoc()): ?>
        <a class="chip <?= $categoria === (int) $c["id"] ? "active" : "" ?>"
           href="<?= url("paginas/produtos.php") ?>?<?= http_build_query(array_merge($qsBase ?? [], ["categoria" => (int)$c["id"]])) ?>">
            <?= e(iconeProduto($c, "")) ?> <?= e($c["nome"]) ?>
            <small><?= (int) $c["total"]; ?></small>
        </a>
    <?php endwhile; ?>
</nav>

<form class="search" method="GET">
    <?php if ($categoria > 0): ?>
        <input type="hidden" name="categoria" value="<?= $categoria ?>">
    <?php endif; ?>

    <input type="search" name="busca" placeholder="Buscar produto..." value="<?= e($busca) ?>">

    <select name="ordem">
        <option value="recentes" <?= $ordem === "recentes" ? "selected" : "" ?>>Mais recentes</option>
        <option value="menor"    <?= $ordem === "menor"    ? "selected" : "" ?>>Menor preco</option>
        <option value="maior"    <?= $ordem === "maior"    ? "selected" : "" ?>>Maior preco</option>
        <option value="nome"     <?= $ordem === "nome"     ? "selected" : "" ?>>Nome (A-Z)</option>
    </select>

    <button class="btn" type="submit">Filtrar</button>
</form>

<div class="grid">
    <?php while ($produto = $produtos->fetch_assoc()): ?>
        <article class="card">
            <?= placeholderImagem($produto, "small-image") ?>
            <small><?= e($produto["categoria"] ?? "Sem categoria") ?></small>
            <h3><?= e($produto["nome"]) ?></h3>
            <p><?= e($produto["descricao"]) ?></p>
            <strong><?= preco($produto["preco"]) ?></strong>
            <?php if ((int) $produto["estoque"] > 0): ?>
                <small class="stock"><?= (int) $produto["estoque"]; ?> em estoque</small>
            <?php else: ?>
                <small class="stock out">Esgotado</small>
            <?php endif; ?>
            <a class="btn small" href="produto.php?id=<?= (int) $produto["id"] ?>">Detalhes</a>
            <a class="btn small secondary" href="carrinho.php?adicionar=<?= (int) $produto["id"] ?>">Comprar</a>
        </article>
    <?php endwhile; ?>
</div>
<?php require_once __DIR__ . "/../includes/footer.php"; ?>











