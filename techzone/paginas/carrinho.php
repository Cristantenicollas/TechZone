<?php
require_once __DIR__ . "/../includes/functions.php";
require_once __DIR__ . "/../includes/conexao.php";

$_SESSION["carrinho"] ??= [];
$aviso = "";

// -------------------------------------------------------------------- Adicionar
if (isset($_GET["adicionar"])) {
    $id = (int) $_GET["adicionar"];

    $stmt = $conexao->prepare("SELECT id, nome, preco, icone, imagem, estoque FROM produtos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $produto = $stmt->get_result()->fetch_assoc();

    if (!$produto) {
        $aviso = "Produto não encontrado.";
    } elseif ((int) $produto["estoque"] <= 0) {
        $aviso = "Produto sem estoque no momento.";
    } else {
        $noCarrinho = (int) ($_SESSION["carrinho"][$id]["quantidade"] ?? 0);

        if ($noCarrinho >= (int) $produto["estoque"]) {
            $aviso = "Você já adicionou todo o estoque disponível.";
        } else {
            adicionarAoCarrinho($produto);
            redirecionar("paginas/carrinho.php");
        }
    }
}

// ------------------------------------------------------- Alterar quantidade
if (isset($_GET["qtd"], $_GET["item"])) {
    $item = (int) $_GET["item"];
    $qtd  = (int) $_GET["qtd"];

    if (isset($_SESSION["carrinho"][$item])) {
        if ($qtd <= 0) {
            unset($_SESSION["carrinho"][$item]);
        } else {
            $stmt = $conexao->prepare("SELECT estoque FROM produtos WHERE id = ?");
            $stmt->bind_param("i", $item);
            $stmt->execute();
            $estoque = (int) ($stmt->get_result()->fetch_assoc()["estoque"] ?? 0);

            if ($estoque <= 0) {
                unset($_SESSION["carrinho"][$item]);
            } else {
                $_SESSION["carrinho"][$item]["quantidade"] = min($qtd, $estoque);
            }
        }
    }

    redirecionar("paginas/carrinho.php");
}

// ---------------------------------------------------------------------- Remover
if (isset($_GET["remover"])) {
    unset($_SESSION["carrinho"][(int) $_GET["remover"]]);

    redirecionar("paginas/carrinho.php");
}

$total = carrinhoTotal();

$titulo = "Carrinho - " . NOME_PROJETO;
require_once __DIR__ . "/../includes/header.php";
?>
<section class="page-header"><span class="tag">CARRINHO</span><h1>Seu carrinho</h1></section>

<?php if ($aviso): ?>
    <div class="alert"><?= e($aviso) ?></div>
<?php endif; ?>

<?php if (!$_SESSION["carrinho"]): ?>
    <div class="empty">
        <h2>Seu carrinho está vazio.</h2>
        <p>Escolha um PC pronto ou componentes para montar o seu.</p>
        <a class="btn" href="pcs_prontos.php">Ver PCs prontos</a>
        <a class="btn secondary" href="produtos.php">Ver componentes</a>
    </div>
<?php else: ?>
    <div class="cart">
        <?php foreach ($_SESSION["carrinho"] as $item): ?>
            <div class="cart-item">
                <?= placeholderImagem($item, "small-image") ?>

                <div>
                    <h3><?= e($item["nome"]) ?></h3>
                    <p><?= preco($item["preco"]) ?> cada</p>
                </div>

                <form class="qty" method="GET">
                    <input type="hidden" name="item" value="<?= (int) $item["id"] ?>">
                    <button class="btn small secondary" name="qtd" value="<?= (int) $item["quantidade"] - 1 ?>" type="submit">−</button>
                    <span><?= (int) $item["quantidade"] ?></span>
                    <button class="btn small secondary" name="qtd" value="<?= (int) $item["quantidade"] + 1 ?>" type="submit">+</button>
                </form>

                <strong><?= preco($item["preco"] * $item["quantidade"]) ?></strong>

                <a class="btn danger small" href="?remover=<?= (int) $item["id"] ?>">Remover</a>
            </div>
        <?php endforeach; ?>

        <div class="cart-total">
            <strong>Total: <?= preco($total) ?></strong>
            <a class="btn" href="checkout.php">Finalizar compra</a>
        </div>
    </div>
<?php endif; ?>
<?php require_once __DIR__ . "/../includes/footer.php"; ?>
