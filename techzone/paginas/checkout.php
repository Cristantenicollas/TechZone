<?php
require_once __DIR__ . "/../includes/functions.php";
require_once __DIR__ . "/../includes/conexao.php";
exigirLogin();

if (empty($_SESSION["carrinho"])) {
    redirecionar("paginas/carrinho.php");
}

// ------------------------------------------------------------------- Resumo
/**
 * Relê o carrinho no banco. O preço e o estoque podem ter mudado desde que
 * o item foi adicionado, então o total exibido e cobrado é sempre o atual.
 */
function resumoCarrinho(): array
{
    global $conexao;

    $itens = [];
    $total = 0.0;
    $problemas = [];

    foreach ($_SESSION["carrinho"] as $item) {
        $stmt = $conexao->prepare(
            "SELECT id, nome, preco, estoque
             FROM produtos
             WHERE id = ?"
        );
        $stmt->bind_param("i", $item["id"]);
        $stmt->execute();
        $produto = $stmt->get_result()->fetch_assoc();

        if (!$produto) {
            $problemas[] = "'{$item['nome']}' saiu do catálogo e foi removido.";
            continue;
        }

        if ((int) $produto["estoque"] < 1) {
            $problemas[] = "'{$produto['nome']}' está sem estoque.";
            continue;
        }

        // A quantidade nunca passa do que existe no estoque.
        $quantidade = min((int) $item["quantidade"], (int) $produto["estoque"]);

        if ($quantidade < (int) $item["quantidade"]) {
            $problemas[] = "Ajustamos '{$produto['nome']}' para {$quantidade} un. (estoque disponível).";
        }

        $itens[] = [
            "id"        => (int) $produto["id"],
            "nome"      => $produto["nome"],
            "quantidade" => $quantidade,
            "preco"     => (float) $produto["preco"],
            "estoque"   => (int) $produto["estoque"],
        ];

        $total += $quantidade * (float) $produto["preco"];
    }

    return ["itens" => $itens, "total" => $total, "problemas" => $problemas];
}

$resumo = resumoCarrinho();
$erro = $resumo["problemas"][0] ?? "";

// ------------------------------------------------------------------- Finalizar
if ($_SERVER["REQUEST_METHOD"] === "POST" && !$erro) {
    $usuarioId = (int) $_SESSION["id"];

    // Trava as linhas e confere o estoque de novo: entre a leitura acima e
    // esta transação outro cliente pode ter comprado o mesmo item.
    $conexao->begin_transaction();

    try {
        foreach ($resumo["itens"] as $item) {
            $stmt = $conexao->prepare("SELECT nome, preco, estoque FROM produtos WHERE id = ? FOR UPDATE");
            $stmt->bind_param("i", $item["id"]);
            $stmt->execute();
            $produto = $stmt->get_result()->fetch_assoc();

            if (!$produto) {
                throw new RuntimeException("Produto do carrinho não existe mais.");
            }

            if ((int) $produto["estoque"] < $item["quantidade"]) {
                throw new RuntimeException(
                    "'{$produto['nome']}' tem só {$produto['estoque']} un. em estoque."
                );
            }

            // Preço cobrado é o do banco, não o que estava na sessão.
            $item["preco"] = (float) $produto["preco"];
        }

        $total = array_reduce($resumo["itens"], fn($soma, $i) => $soma + $i["preco"] * $i["quantidade"], 0.0);

        $stmt = $conexao->prepare("INSERT INTO pedidos (usuario_id, status, total) VALUES (?, 'Pendente', ?)");
        $stmt->bind_param("id", $usuarioId, $total);
        $stmt->execute();
        $pedidoId = $stmt->insert_id;

        foreach ($resumo["itens"] as $item) {
            $itemStmt = $conexao->prepare(
                "INSERT INTO itens_pedido (pedido_id, produto_id, quantidade, preco_unitario)
                 VALUES (?, ?, ?, ?)"
            );
            $itemStmt->bind_param("iiid", $pedidoId, $item["id"], $item["quantidade"], $item["preco"]);
            $itemStmt->execute();

            $baixaStmt = $conexao->prepare("UPDATE produtos SET estoque = estoque - ? WHERE id = ?");
            $baixaStmt->bind_param("ii", $item["quantidade"], $item["id"]);
            $baixaStmt->execute();
        }

        $conexao->commit();
        $_SESSION["carrinho"] = [];

        redirecionar("paginas/pedidos.php");
    } catch (Throwable $e) {
        $conexao->rollback();
        $erro = $e->getMessage();
    }
}

$itens = $resumo["itens"];
$total = $resumo["total"];

$titulo = "Checkout - " . NOME_PROJETO;
require_once __DIR__ . "/../includes/header.php";
?>
<section class="form-page">
    <div class="form-card">
        <span class="tag">PEDIDO</span>
        <h1>Finalizar compra</h1>

        <?php if ($erro): ?>
            <div class="alert"><?= e($erro) ?></div>
        <?php endif; ?>

        <?php if ($itens): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Qtd.</th>
                        <th>Unitário</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($itens as $item): ?>
                        <tr>
                            <td><?= e($item["nome"]) ?></td>
                            <td><?= $item["quantidade"] ?></td>
                            <td>R$ <?= preco($item["preco"]) ?></td>
                            <td>R$ <?= preco($item["preco"] * $item["quantidade"]) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3">Total</th>
                        <th>R$ <?= preco($total) ?></th>
                    </tr>
                </tfoot>
            </table>
        <?php endif; ?>

        <?php if ($itens && !$erro): ?>
            <form method="POST">
                <button class="btn">Confirmar pedido</button>
            </form>
        <?php elseif (!$itens): ?>
            <p><a href="<?= url("paginas/produtos.php") ?>">Ver o catálogo</a></p>
        <?php endif; ?>
    </div>
</section>
<?php require_once __DIR__ . "/../includes/footer.php"; ?>