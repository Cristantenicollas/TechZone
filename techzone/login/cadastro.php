<?php
require_once __DIR__ . "/../includes/functions.php";
require_once __DIR__ . "/../includes/conexao.php";

$erro    = "";
$sucesso = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome      = trim($_POST["nome"] ?? "");
    $email     = trim($_POST["email"] ?? "");
    $senha     = $_POST["senha"] ?? "";
    $confirmar = $_POST["confirmar"] ?? "";

    if ($senha !== $confirmar) {
        $erro = "As senhas não coincidem.";
    } else {
        $check = $conexao->prepare("SELECT id FROM usuarios WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();

        if ($check->get_result()->num_rows > 0) {
            $erro = "Este e-mail já está cadastrado.";
        } else {
            $hash = password_hash($senha, PASSWORD_DEFAULT);

            $stmt = $conexao->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nome, $email, $hash);
            $stmt->execute();

            $sucesso = "Cadastro realizado. Agora faça login.";
        }
    }
}

$titulo = "Cadastro - " . NOME_PROJETO;
require_once __DIR__ . "/../includes/header.php";
?>
<section class="form-page">
    <div class="form-card">
        <span class="tag">TECHZONE</span>
        <h1>Criar conta</h1>

        <?php if ($erro): ?>
            <div class="alert"><?= e($erro) ?></div>
        <?php endif; ?>

        <?php if ($sucesso): ?>
            <div class="success"><?= e($sucesso) ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>Nome</label>
            <input type="text" name="nome" required>

            <label>E-mail</label>
            <input type="email" name="email" required>

            <label>Senha</label>
            <input type="password" name="senha" required>

            <label>Confirmar senha</label>
            <input type="password" name="confirmar" required>

            <button class="btn" type="submit">Cadastrar</button>
        </form>
    </div>
</section>
<?php require_once __DIR__ . "/../includes/footer.php"; ?>
