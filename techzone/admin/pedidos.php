<?php
require_once __DIR__ . "/../includes/functions.php";
require_once __DIR__ . "/../includes/conexao.php";
exigirAdmin();

$pedidos = $conexao->query(
    "SELECT p.id, u.nome cliente, p.data_pedido, p.status, p.total
     FROM pedidos p
     INNER JOIN usuarios u ON u.id = p.usuario_id
     ORDER BY p.id DESC"
);

$titulo = "Pedidos - " . NOME_PROJETO;
require_once __DIR__ . "/../includes/header.php";
?>
<section class="page-header"><span class="tag">ADMIN</span><h1>Pedidos</h1></section>

<div class="table-wrapper">
    <table>
        <thead>
            <tr><th>ID</th><th>Cliente</th><th>Data</th><th>Status</th><th>Total</th></tr>
        </thead>
        <tbody>
            <?php while ($p = $pedidos->fetch_assoc()): ?>
                <tr>
                    <td>#<?= (int) $p["id"] ?></td>
                    <td><?= e($p["cliente"]) ?></td>
                    <td><?= e($p["data_pedido"]) ?></td>
                    <td><?= e($p["status"]) ?></td>
                    <td>R$ <?= preco($p["total"]) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php require_once __DIR__ . "/../includes/footer.php"; ?>
