<?php
require_once __DIR__ . "/../includes/functions.php";
require_once __DIR__ . "/../includes/conexao.php";

$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $senha = $_POST["senha"] ?? "";

    $stmt = $conexao->prepare("SELECT id, nome, email, senha, tipo FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $usuario = $stmt->get_result()->fetch_assoc();

    if ($usuario && password_verify($senha, $usuario["senha"])) {
        $_SESSION["id"]    = $usuario["id"];
        $_SESSION["nome"]  = $usuario["nome"];
        $_SESSION["email"] = $usuario["email"];
        $_SESSION["tipo"]  = $usuario["tipo"];

        redirecionar($_SESSION["tipo"] === "admin" ? "admin/painel.php" : "index.php");
    }

    $erro = "E-mail ou senha inválidos.";
}

$titulo = "Login - " . NOME_PROJETO;
require_once __DIR__ . "/../includes/header.php";
?>
<section class="form-page">
    <div class="form-card">
        <span class="tag">TECHZONE</span>
        <h1>Login</h1>

        <?php if ($erro): ?>
            <div class="alert"><?= e($erro) ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>E-mail</label>
            <input type="email" name="email" required>

            <label>Senha</label>
            <input type="password" name="senha" required>

            <button class="btn" type="submit">Entrar</button>
        </form>

        <p>Não possui conta? <a href="cadastro.php">Cadastre-se</a></p>
    </div>
</section>
<?php require_once __DIR__ . "/../includes/footer.php"; ?>
