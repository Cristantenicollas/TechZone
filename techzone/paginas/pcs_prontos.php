<?php
require_once __DIR__ . "/../includes/functions.php";
require_once __DIR__ . "/../includes/conexao.php";

// ------------------------------------------------------------------- Filtros
$busca    = trim($_GET["busca"] ?? "");
$ordem    = $_GET["ordem"] ?? "destaque";
$faixaMax = (float) ($_GET["ate"] ?? 0);

$sql = "SELECT p.*, c.nome categoria
        FROM produtos p
        INNER JOIN categorias c ON c.id = p.categoria_id
        WHERE c.tipo = 'pc'";

$params = [];
$types  = "";

if ($busca !== "") {
    $sql .= " AND p.nome ILIKE ?";
    $params[] = "%$busca%";
    $types .= "s";
}

if ($faixaMax > 0) {
    $sql .= " AND p.preco <= ?";
    $params[] = $faixaMax;
    $types .= "d";
}

$ordens = [
    "destaque" => "p.destaque DESC, p.preco DESC",
    "menor"    => "p.preco ASC",
    "maior"    => "p.preco DESC",
    "novos"    => "p.data_cadastro DESC, p.id DESC",
];

$sql .= " ORDER BY " . ($ordens[$ordem] ?? $ordens["destaque"]);

$stmt = $conexao->prepare($sql);

if ($params) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$pcs = $stmt->get_result();

$total = $conexao->query(
    "SELECT COUNT(*) total
     FROM produtos p
     INNER JOIN categorias c ON c.id = p.categoria_id
     WHERE c.tipo = 'pc'"
)->fetch_assoc()["total"];

$titulo = "PCs Prontos - " . NOME_PROJETO;
require_once __DIR__ . "/../includes/header.php";
?>
<section class="page-header">
    <span class="tag">COMPUTADORES</span>
    <h1>PCs prontos</h1>
    <p><?= (int) $total; ?> configurações completas, montadas e testadas. Escolha e leve para casa.</p>
</section>

<form class="search" method="GET">
    <input type="search" name="busca" placeholder="Buscar PC pronto..." value="<?= e($busca) ?>">

    <select name="ate">
        <option value="">Qualquer faixa de preço</option>
        <option value="5000"   <?= (int) $faixaMax === 5000   ? "selected" : "" ?>>Até R$ 5.000</option>
        <option value="10000"  <?= (int) $faixaMax === 10000  ? "selected" : "" ?>>Até R$ 10.000</option>
        <option value="15000"  <?= (int) $faixaMax === 15000  ? "selected" : "" ?>>Até R$ 15.000</option>
        <option value="25000"  <?= (int) $faixaMax === 25000  ? "selected" : "" ?>>Até R$ 25.000</option>
    </select>

    <select name="ordem">
        <option value="destaque" <?= $ordem === "destaque" ? "selected" : "" ?>>Destaques</option>
        <option value="menor"    <?= $ordem === "menor"    ? "selected" : "" ?>>Menor preço</option>
        <option value="maior"    <?= $ordem === "maior"    ? "selected" : "" ?>>Maior preço</option>
        <option value="novos"    <?= $ordem === "novos"    ? "selected" : "" ?>>Mais novos</option>
    </select>

    <button class="btn" type="submit">Filtrar</button>
</form>

<div class="grid">
    <?php while ($pc = $pcs->fetch_assoc()): ?>
        <article class="card">
            <?= placeholderImagem($pc) ?>
            <?php if ($pc["destaque"]): ?>
                <span class="tag">DESTAQUE</span>
            <?php endif; ?>
            <h3><?= e($pc["nome"]) ?></h3>
            <p><?= e($pc["descricao"]) ?></p>
            <strong><?= preco($pc["preco"]) ?></strong>
            <a class="btn small" href="produto.php?id=<?= (int) $pc["id"] ?>">Detalhes</a>
            <a class="btn small secondary" href="carrinho.php?adicionar=<?= (int) $pc["id"] ?>">Comprar</a>
        </article>
    <?php endwhile; ?>
</div>
<?php require_once __DIR__ . "/../includes/footer.php"; ?>
