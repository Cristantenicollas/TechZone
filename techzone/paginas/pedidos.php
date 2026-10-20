<?php
require_once __DIR__ . "/../includes/functions.php";
require_once __DIR__ . "/../includes/conexao.php";
exigirLogin();

$stmt = $conexao->prepare(
    "SELECT id, data_pedido, status, total
     FROM pedidos
     WHERE usuario_id = ?
     ORDER BY id DESC"
);
$stmt->bind_param("i", $_SESSION["id"]);
$stmt->execute();
$pedidos = $stmt->get_result();

$titulo = "Meus pedidos - " . NOME_PROJETO;
require_once __DIR__ . "/../includes/header.php";
?>
<section class="page-header"><span class="tag">CONTA</span><h1>Meus pedidos</h1></section>

<div class="table-wrapper">
    <table>
        <thead>
            <tr><th>ID</th><th>Data</th><th>Status</th><th>Total</th></tr>
        </thead>
        <tbody>
            <?php while ($pedido = $pedidos->fetch_assoc()): ?>
                <tr>
                    <td>#<?= (int) $pedido["id"] ?></td>
                    <td><?= e($pedido["data_pedido"]) ?></td>
                    <td><?= e($pedido["status"]) ?></td>
                    <td>R$ <?= preco($pedido["total"]) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php require_once __DIR__ . "/../includes/footer.php"; ?>
