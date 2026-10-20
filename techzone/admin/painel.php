<?php
require_once __DIR__ . "/../includes/functions.php";
require_once __DIR__ . "/../includes/conexao.php";
exigirAdmin();

$produtos = $conexao->query("SELECT COUNT(*) total FROM produtos")->fetch_assoc()["total"];
$usuarios = $conexao->query("SELECT COUNT(*) total FROM usuarios")->fetch_assoc()["total"];
$pedidos  = $conexao->query("SELECT COUNT(*) total FROM pedidos")->fetch_assoc()["total"];

$titulo = "Painel Admin - " . NOME_PROJETO;
require_once __DIR__ . "/../includes/header.php";
?>
<section class="page-header"><span class="tag">ADMIN</span><h1>Painel administrativo</h1></section>
<div class="stats">
    <div class="stat-card"><span>Produtos</span><strong><?= $produtos ?></strong></div>
    <div class="stat-card"><span>Usuarios</span><strong><?= $usuarios ?></strong></div>
    <div class="stat-card"><span>Pedidos</span><strong><?= $pedidos ?></strong></div>
</div>
<div class="categories">
    <a href="produtos.php">Produtos</a>
    <a href="categorias.php">Categorias</a>
    <a href="usuarios.php">Usuarios</a>
    <a href="pedidos.php">Pedidos</a>
</div>
<?php require_once __DIR__ . "/../includes/footer.php"; ?>


