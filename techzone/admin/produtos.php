<?php
require_once __DIR__ . "/../includes/functions.php";
require_once __DIR__ . "/../includes/conexao.php";
exigirAdmin();

$erro = "";

// ------------------------------------------------------------------ Excluir
if (isset($_GET["excluir"])) {
    $id = (int) $_GET["excluir"];

    $stmt = $conexao->prepare("DELETE FROM produtos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    redirecionar("admin/produtos.php");
}

// --------------------------------------------------------- Cadastrar/editar
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id        = (int) ($_POST["id"] ?? 0);
    $nome      = trim($_POST["nome"] ?? "");
    $descricao = trim($_POST["descricao"] ?? "");
    $preco     = (float) ($_POST["preco"] ?? 0);
    $estoque   = (int) ($_POST["estoque"] ?? 0);
    $categoria = (int) ($_POST["categoria_id"] ?? 0);
    $icone     = trim($_POST["icone"] ?? "");
    $destaque  = isset($_POST["destaque"]) ? 1 : 0;

    if ($nome === "" || $descricao === "" || $preco <= 0 || $categoria <= 0) {
        $erro = "Preencha nome, descrição, preço e categoria.";
    } else {
        if ($id > 0) {
            $stmt = $conexao->prepare(
                "UPDATE produtos
                 SET categoria_id = ?, nome = ?, descricao = ?, preco = ?, estoque = ?, icone = ?, destaque = ?
                 WHERE id = ?"
            );
            $stmt->bind_param("issdisii", $categoria, $nome, $descricao, $preco, $estoque, $icone, $destaque, $id);
        } else {
            $stmt = $conexao->prepare(
                "INSERT INTO produtos (categoria_id, nome, descricao, preco, estoque, icone, destaque)
                 VALUES (?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->bind_param("issdisi", $categoria, $nome, $descricao, $preco, $estoque, $icone, $destaque);
        }

        $stmt->execute();

        redirecionar("admin/produtos.php");
    }
}

// ------------------------------------------------------------------ Editar
$editando = null;

if (isset($_GET["editar"])) {
    $stmt = $conexao->prepare("SELECT * FROM produtos WHERE id = ?");
    $stmt->bind_param("i", $id = (int) $_GET["editar"]);
    $stmt->execute();
    $editando = $stmt->get_result()->fetch_assoc();
}

// ---------------------------------------------------------------- Listagem
$busca = trim($_GET["busca"] ?? "");

$sql = "SELECT p.*, c.nome categoria, c.tipo categoria_tipo
        FROM produtos p
        INNER JOIN categorias c ON c.id = p.categoria_id
        WHERE 1=1";

$params = [];

if ($busca !== "") {
    $sql .= " AND p.nome ILIKE ?";
    $params[] = "%$busca%";
}

$sql .= " ORDER BY c.tipo DESC, p.id DESC";

$stmt = $conexao->prepare($sql);

if ($params) {
    $stmt->bind_param("s", ...$params);
}

$stmt->execute();
$produtos = $stmt->get_result();

// Lista completa (peças + PCs) para o <select> do formulário.
// O resultado não expõe fetch_all(), então as linhas viram array aqui.
$opcoesCategoria = [];
$resultadoCategorias = $conexao->query(
    "SELECT c.*, COUNT(p.id) total
     FROM categorias c
     LEFT JOIN produtos p ON p.categoria_id = c.id
     GROUP BY c.id
     ORDER BY c.tipo DESC, c.nome"
);
while ($linha = $resultadoCategorias->fetch_assoc()) {
    $opcoesCategoria[] = $linha;
}
$resultadoCategorias->free();

$titulo = "CRUD de Produtos - " . NOME_PROJETO;
require_once __DIR__ . "/../includes/header.php";
?>
<section class="page-header"><span class="tag">ADMIN</span><h1>CRUD de produtos</h1></section>

<?php if ($erro): ?>
    <div class="alert"><?= e($erro) ?></div>
<?php endif; ?>

<div class="form-card">
    <form method="POST">
        <?php if ($editando): ?>
            <input type="hidden" name="id" value="<?= (int) $editando["id"] ?>">
            <p class="form-title">Editando: <?= e($editando["nome"]) ?></p>
        <?php else: ?>
            <p class="form-title">Cadastrar novo produto</p>
        <?php endif; ?>

        <label>Nome</label>
        <input name="nome" required value="<?= e($editando["nome"] ?? "") ?>">

        <label>Descrição</label>
        <textarea name="descricao" required><?= e($editando["descricao"] ?? "") ?></textarea>

        <label>Preço (R$)</label>
        <input type="number" step="0.01" min="0.01" name="preco" required value="<?= e($editando["preco"] ?? "") ?>">

        <label>Estoque</label>
        <input type="number" min="0" name="estoque" required value="<?= (int) ($editando["estoque"] ?? 0) ?>">

        <label>Categoria</label>
        <select name="categoria_id" required>
            <optgroup label="Peças (componentes)">
                <?php foreach ($opcoesCategoria as $c): ?>
                    <?php if ($c["tipo"] === "pc") { continue; } ?>
                    <option value="<?= (int) $c["id"] ?>"
                        <?= (int) ($editando["categoria_id"] ?? 0) === (int) $c["id"] ? "selected" : "" ?>>
                        <?= e(iconeProduto($c, "")) ?> <?= e($c["nome"]) ?> (<?= (int) $c["total"] ?>)
                    </option>
                <?php endforeach; ?>
            </optgroup>
            <optgroup label="PCs prontos">
                <?php foreach ($opcoesCategoria as $c): ?>
                    <?php if ($c["tipo"] !== "pc") { continue; } ?>
                    <option value="<?= (int) $c["id"] ?>"
                        <?= (int) ($editando["categoria_id"] ?? 0) === (int) $c["id"] ? "selected" : "" ?>>
                        <?= e(iconeProduto($c, "")) ?> <?= e($c["nome"]) ?> (<?= (int) $c["total"] ?>)
                    </option>
                <?php endforeach; ?>
            </optgroup>
        </select>
        <small>Escolha "PCs prontos" para vender um computador completo.</small>

        <label>Ícone (emoji) — opcional</label>
        <input name="icone" maxlength="8" placeholder="Ex.: 🧩 🎮 💾" value="<?= e($editando["icone"] ?? "") ?>">

        <label class="checkbox">
            <input type="checkbox" name="destaque" value="1" <?= !empty($editando["destaque"]) ? "checked" : "" ?>>
            Mostrar na vitrine da página inicial
        </label>

        <button class="btn"><?= $editando ? "Salvar alterações" : "Cadastrar produto" ?></button>

        <?php if ($editando): ?>
            <a class="btn secondary" href="produtos.php">Cancelar edição</a>
        <?php endif; ?>
    </form>
</div>

<form class="search" method="GET">
    <input type="search" name="busca" placeholder="Buscar produto pelo nome..." value="<?= e($busca) ?>">
    <button class="btn" type="submit">Buscar</button>
</form>

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>ID</th><th>Produto</th><th>Categoria</th>
                <th>Preço</th><th>Estoque</th><th>Destaque</th><th>Ação</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($p = $produtos->fetch_assoc()): ?>
                <tr>
                    <td><?= (int) $p["id"] ?></td>
                    <td><?= e(iconeProduto($p)) ?> <?= e($p["nome"]) ?></td>
                    <td><?= e($p["categoria"]) ?><?= $p["categoria_tipo"] === "pc" ? " (PC)" : "" ?></td>
                    <td><?= preco($p["preco"]) ?></td>
                    <td><?= (int) $p["estoque"] ?></td>
                    <td><?= $p["destaque"] ? "Sim" : "—" ?></td>
                    <td class="row-actions">
                        <a class="btn small" href="?editar=<?= (int) $p["id"] ?>">Editar</a>
                        <a class="btn danger small" href="?excluir=<?= (int) $p["id"] ?>">Excluir</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php require_once __DIR__ . "/../includes/footer.php"; ?>
