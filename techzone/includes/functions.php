<?php
/**
 * Funções auxiliares e controle de sessão.
 * Este arquivo é carregado por header.php, mas pode ser usado direto
 * por páginas que precisam de autenticação antes de imprimir o HTML.
 */

require_once __DIR__ . "/config.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ------------------------------------------------------------------ Formatação

/** Escapa um valor para saída segura em HTML. */
function e($valor)
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, "UTF-8");
}

/** Formata um valor no padrão brasileiro: 1234.5 -> "1.234,50". */
function preco($valor)
{
    return number_format((float) $valor, 2, ",", ".");
}

// ---------------------------------------------------------------------- URLs

/** Monta um caminho absoluto do site a partir de um caminho interno. */
function url($caminho = "")
{
    return BASE_URL . "/" . ltrim($caminho, "/");
}

/** Redireciona e encerra a execução. */
function redirecionar($caminho)
{
    header("Location: " . (str_starts_with($caminho, "http") ? $caminho : url($caminho)));
    exit;
}

// ------------------------------------------------------------- Autenticação

function usuarioLogado()
{
    return isset($_SESSION["id"]);
}

function exigirLogin()
{
    if (!usuarioLogado()) {
        redirecionar("login/login.php");
    }
}

function exigirAdmin()
{
    exigirLogin();

    if (($_SESSION["tipo"] ?? "") !== "admin") {
        redirecionar("index.php");
    }
}

// ------------------------------------------------------------------ Carrinho

/** Quantidade total de itens no carrinho. */
function carrinhoQtd()
{
    $total = 0;

    foreach ($_SESSION["carrinho"] ?? [] as $item) {
        $total += (int) $item["quantidade"];
    }

    return $total;
}

/** Soma do preço x quantidade de todos os itens do carrinho. */
function carrinhoTotal()
{
    $total = 0;

    foreach ($_SESSION["carrinho"] ?? [] as $item) {
        $total += (float) $item["preco"] * (int) $item["quantidade"];
    }

    return $total;
}

/**
 * Adiciona um produto ao carrinho da sessão.
 * Incrementa a quantidade se o item já estiver lá.
 * Nunca deixa a quantidade passar do estoque disponível.
 * Retorna a quantidade realmente adicionada (0 se não coube).
 */
function adicionarAoCarrinho($produto, $quantidade = 1)
{
    $id = (int) $produto["id"];
    $quantidade = max(1, (int) $quantidade);
    $estoque = (int) ($produto["estoque"] ?? 0);

    if ($estoque <= 0) {
        return 0;
    }

    $nova = $quantidade + (int) ($_SESSION["carrinho"][$id]["quantidade"] ?? 0);

    if ($nova > $estoque) {
        $nova = $estoque;
    }

    $adicionado = $nova - (int) ($_SESSION["carrinho"][$id]["quantidade"] ?? 0);

    if ($adicionado <= 0) {
        return 0;
    }

    $_SESSION["carrinho"][$id] = [
        "id"         => $id,
        "nome"       => $produto["nome"],
        "preco"      => $produto["preco"],
        "icone"      => $produto["icone"] ?? "",
        "imagem"     => $produto["imagem"] ?? "",
        "quantidade" => $nova,
    ];

    return $adicionado;
}

// --------------------------------------------------------------- Vitrine

/** Emoji do produto; usa as iniciais do nome quando não há ícone. */
function iconeProduto($produto, $fallback = "TZ")
{
    $icone = trim((string) ($produto["icone"] ?? ""));

    return $icone !== "" ? $icone : $fallback;
}

/** URL da imagem do produto; string vazia quando não há imagem. */
function imagemProduto($produto)
{
    $imagem = trim((string) ($produto["imagem"] ?? ""));

    return $imagem !== "" ? url($imagem) : "";
}

/**
 * Bloco de imagem dos cards: mostra o SVG do produto quando existe
 * e cai para o emoji dentro do placeholder quando não existe.
 */
function placeholderImagem($produto, $classe = "")
{
    $imagem = imagemProduto($produto);
    $classe = trim("image-placeholder " . $classe);

    if ($imagem !== "") {
        $alt = e($produto["nome"] ?? "");

        return '<div class="' . e($classe) . '">'
             . '<img class="placeholder-img" src="' . e($imagem) . '" alt="' . $alt . '">'
             . '</div>';
    }

    return '<div class="' . e($classe) . '">'
         . '<span class="pc-icon">' . e(iconeProduto($produto)) . '</span>'
         . '</div>';
}

/** Monta a querystring preservando os filtros já aplicados. */
function queryString(array $mudancas = [])
{
    $params = array_merge($_GET, $mudancas);
    $params = array_filter($params, fn($v) => $v !== "" && $v !== null);

    return $params ? "?" . http_build_query($params) : "";
}
