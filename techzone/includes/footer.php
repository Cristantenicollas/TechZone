</main>
<footer>
    <div class="footer-container">
        <div>
            <a class="logo" href="<?= url("index.php") ?>"><strong>TZ</strong><span style="color:#fff">Tech<span>Zone</span></span></a>
            <p>Tecnologia para quem joga, trabalha e sonha.</p>
            <p>Projeto educacional desenvolvido com HTML, CSS, PHP e PostgreSQL.</p>
        </div>
        <div>
            <h3>Explorar</h3>
            <a href="<?= url("index.php") ?>">Página inicial</a>
            <a href="<?= url("paginas/pcs_prontos.php") ?>">PCs prontos</a>
            <a href="<?= url("paginas/produtos.php") ?>">Produtos</a>
            <a href="<?= url("paginas/monte_pc.php") ?>">Monte seu PC</a>
        </div>
        <div>
            <h3>Conta</h3>
            <a href="<?= url("login/login.php") ?>">Entrar</a>
            <a href="<?= url("login/cadastro.php") ?>">Criar cadastro</a>
            <a href="<?= url("paginas/carrinho.php") ?>">Carrinho</a>
        </div>
    </div>
    <div class="footer-bottom">© <?= date("Y") ?> <?= NOME_PROJETO ?> — Mais que uma loja, é o seu setup ideal.</div>
</footer>
</body>
</html>
