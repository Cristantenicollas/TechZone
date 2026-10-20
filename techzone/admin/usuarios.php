<?php
require_once __DIR__ . "/../includes/functions.php";
require_once __DIR__ . "/../includes/conexao.php";
exigirAdmin();

$usuarios = $conexao->query("SELECT id, nome, email, tipo, data_cadastro FROM usuarios ORDER BY id DESC");

$titulo = "Usuários - " . NOME_PROJETO;
require_once __DIR__ . "/../includes/header.php";
?>
<section class="page-header"><span class="tag">ADMIN</span><h1>Usuários</h1></section>

<div class="table-wrapper">
    <table>
        <thead>
            <tr><th>ID</th><th>Nome</th><th>E-mail</th><th>Tipo</th><th>Cadastro</th></tr>
        </thead>
        <tbody>
            <?php while ($u = $usuarios->fetch_assoc()): ?>
                <tr>
                    <td><?= (int) $u["id"] ?></td>
                    <td><?= e($u["nome"]) ?></td>
                    <td><?= e($u["email"]) ?></td>
                    <td><?= e($u["tipo"]) ?></td>
                    <td><?= e($u["data_cadastro"]) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php require_once __DIR__ . "/../includes/footer.php"; ?>
