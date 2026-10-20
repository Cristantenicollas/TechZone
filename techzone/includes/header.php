<?php
header('Content-Type: text/html; charset=UTF-8');
require_once __DIR__ . '/functions.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($titulo ?? NOME_PROJETO) ?></title>
    <link rel="stylesheet" href="<?= url("style/style.css") ?>">
</head>
<body>
<header>
    <div class="header-container">
        <a class="logo" href="<?= url("index.php") ?>">
            <strong>TZ</strong>
            <span style="color:#fff">Tech<span>Zone</span></span>
        </a>
        <nav>
            <a href="<?= url("index.php") ?>">Início</a>
            <a href="<?= url("paginas/pcs_prontos.php") ?>">PCs Prontos</a>
            <a href="<?= url("paginas/produtos.php") ?>">Produtos</a>
            <a href="<?= url("paginas/monte_pc.php") ?>">Monte seu PC</a>
            <a href="<?= url("paginas/categorias.php") ?>">Categorias</a>
        </nav>
        <div class="actions">
            <?php if (usuarioLogado()): ?>
                <a href="<?= url("paginas/pedidos.php") ?>">Minha conta</a>
                <?php if (($_SESSION["tipo"] ?? "") === "admin"): ?>
                    <a href="<?= url("admin/painel.php") ?>">Painel</a>
                <?php endif; ?>
                <a href="<?= url("login/logout.php") ?>">Sair</a>
            <?php else: ?>
                <a href="<?= url("login/login.php") ?>">Login</a>
                <a href="<?= url("login/cadastro.php") ?>">Cadastro</a>
            <?php endif; ?>
            <a class="cart-link" href="<?= url("paginas/carrinho.php") ?>">🛒 <?= carrinhoQtd() ?></a>
        </div>
    </div>
</header>
<main class="container">
