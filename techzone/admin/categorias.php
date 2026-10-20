<?php
require_once __DIR__ . "/../includes/functions.php";
require_once __DIR__ . "/../includes/conexao.php";
exigirAdmin();

$erro = "";

// ------------------------------------------------------------------ Excluir
if (isset($_GET["excluir"])) {
    $id = (int) $_GET["excluir"];

    $emUso = $conexao->prepare("SELECT COUNT(*) total FROM produtos WHERE categoria_id = ?");
    $emUso->bind_param("i", $id);
    $emUso->execute();
    $total = (int) $emUso->get_result()->fetch_assoc()["total"];

    if ($total > 0) {
        $erro = "Esta categoria tem $total produto(s) vinculado(s). Exclua ou mude a categoria deles antes.";
    } else {
        $stmt = $conexao->prepare("DELETE FROM categorias WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        redirecionar("admin/categorias.php");
    }
}

// --------------------------------------------------------- Cadastrar/editar
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id        = (int) ($_POST["id"] ?? 0);
    $nome      = trim($_POST["nome"] ?? "");
    $descricao = trim($_POST["descricao"] ?? "");
    $icone     = trim($_POST["icone"] ?? "");
    $tipo      = ($_POST["tipo"] ?? "peca") === "pc" ? "pc" : "peca";

    if ($nome === "") {
        $erro = "Informe o nome da categoria.";
    } else {
        if ($id > 0) {
            $stmt = $conexao->prepare("UPDATE categorias SET nome = ?, descricao = ?, icone = ?, tipo = ? WHERE id = ?");
            $stmt->bind_param("ssssi", $nome, $descricao, $icone, $tipo, $id);
        } else {
            $stmt = $conexao->prepare("INSERT INTO categorias (nome, descricao, icone, tipo) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nome, $descricao, $icone, $tipo);
        }

        $stmt->execute();

        redirecionar("admin/categorias.php");
    }
}

// ------------------------------------------------------------------ Editar
$editando = null;

if (isset($_GET["editar"])) {
    $stmt = $conexao->prepare("SELECT * FROM categorias WHERE id = ?");
    $stmt->bind_param("i", $id = (int) $_GET["editar"]);
    $stmt->execute();
    $editando = $stmt->get_result()->fetch_assoc();
}

// ---------------------------------------------------------------- Listagem
$categorias = $conexao->query(
    "SELECT c.*, COUNT(p.id) total
     FROM categorias c
     LEFT JOIN produtos p ON p.categoria_id = c.id
     GROUP BY c.id
     ORDER BY c.tipo DESC, c.nome"
);

$titulo = "CRUD de Categorias - " . NOME_PROJETO;
require_once __DIR__ . "/../includes/header.php";
?>
<section class="page-header"><span class="tag">ADMIN</span><h1>CRUD de categorias</h1></section>

<?php if ($erro): ?>
    <div class="alert"><?= e($erro) ?></div>
<?php endif; ?>

<div class="form-card">
    <form method="POST">
        <?php if ($editando): ?>
            <input type="hidden" name="id" value="<?= (int) $editando["id"] ?>">
            <p class="form-title">Editando: <?= e($editando["nome"]) ?></p>
        <?php else: ?>
            <p class="form-title">Cadastrar nova categoria</p>
        <?php endif; ?>

        <label>Nome</label>
        <input name="nome" required value="<?= e($editando["nome"] ?? "") ?>">

        <label>Descrição</label>
        <textarea name="descricao"><?= e($editando["descricao"] ?? "") ?></textarea>

        <label>Ícone (emoji) — opcional</label>
        <input name="icone" maxlength="8" placeholder="Ex.: ⚙️ 🎮 ❄️" value="<?= e($editando["icone"] ?? "") ?>">

        <label>Tipo</label>
        <select name="tipo">
            <option value="peca" <?= ($editando["tipo"] ?? "peca") === "peca" ? "selected" : "" ?>>
                Peça — aparece no catálogo e no Monte seu PC
            </option>
            <option value="pc" <?= ($editando["tipo"] ?? "") === "pc" ? "selected" : "" ?>>
                PC pronto — aparece na página de PCs prontos
            </option>
        </select>

        <button class="btn"><?= $editando ? "Salvar alterações" : "Cadastrar categoria" ?></button>

        <?php if ($editando): ?>
            <a class="btn secondary" href="categorias.php">Cancelar edição</a>
        <?php endif; ?>
    </form>
</div>

<div class="table-wrapper">
    <table>
        <thead>
            <tr><th>ID</th><th>Categoria</th><th>Tipo</th><th>Produtos</th><th>Ação</th></tr>
        </thead>
        <tbody>
            <?php while ($c = $categorias->fetch_assoc()): ?>
                <tr>
                    <td><?= (int) $c["id"] ?></td>
                    <td><?= e(iconeProduto($c, "")) ?> <?= e($c["nome"]) ?></td>
                    <td><?= $c["tipo"] === "pc" ? "PC pronto" : "Peça" ?></td>
                    <td><?= (int) $c["total"] ?></td>
                    <td class="row-actions">
                        <a class="btn small" href="?editar=<?= (int) $c["id"] ?>">Editar</a>
                        <a class="btn danger small" href="?excluir=<?= (int) $c["id"] ?>">Excluir</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php require_once __DIR__ . "/../includes/footer.php"; ?>
