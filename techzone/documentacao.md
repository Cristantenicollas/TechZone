# Documentação do Projeto Final - TechZone

Esta documentação traz uma visão abrangente e detalhada sobre o desenvolvimento do projeto **TechZone**, um sistema web completo de e-commerce construído com **PHP, PostgreSQL/MySQL, HTML5 e CSS3**.

O objetivo deste documento é explicar a estrutura de arquivos, a modelagem do banco de dados, os conceitos essenciais de programação utilizados e como os códigos se integram na prática.

---

## 1. Visão Geral e Arquitetura do Sistema

O sistema foi desenvolvido seguindo uma arquitetura modular em PHP procedural, dividindo as responsabilidades em camadas bem definidas:

* **Camada de Apresentação (View):** Telas em PHP/HTML localizadas na raiz e nas pastas `paginas/` e `admin/`.
* **Camada de Lógica/Inclusão (Includes):** Arquivos de cabeçalho (`header.php`), rodapé (`footer.php`), conexão com o banco (`conexao.php`) e funções globais (`functions.php`).
* **Camada de Autenticação (Login):** Módulos de controle de sessão, login, cadastro e logout de usuários.
* **Camada de Dados (Database):** Scripts SQL para criação de tabelas, inserção de dados e migração.

---

## 2. Estrutura de Pastas e Arquivos

| Diretório / Arquivo | Descrição e Responsabilidade |
| :--- | :--- |
| `index.php` / `index.html` | Página inicial da loja e direcionamento principal. |
| `includes/` | Contém scripts de configuração (`config.php`), conexão (`conexao.php`), funções auxiliares (`functions.php`), cabeçalho (`header.php`) e rodapé (`footer.php`). |
| `login/` | Scripts de autenticação e controle de acesso: `login.php`, `cadastro.php` e `logout.php`. |
| `paginas/` | Interfaces da loja para o cliente: catálogo de produtos (`produtos.php`), carrinho (`carrinho.php`), checkout (`checkout.php`), montador de PC (`monte_pc.php`). |
| `admin/` | Painel de controle restrito (CRUD) para gerenciar categorias, produtos, pedidos e usuários. |
| `database/` | Scripts de banco de dados SQL (`techzone.sql`) e rotina de migração para PostgreSQL (`migrar-para-postgres.php`). |
| `style/` | Folhas de estilo CSS globais (`style.css`) que definem o design e a responsividade da aplicação. |

---

## 3. Modelagem e Estrutura do Banco de Dados

O banco de dados relacional é composto por 5 tabelas principais interligadas:

1. **`usuarios`:** Armazena dados de acesso (nome, e-mail, senha criptografada e nível de acesso/permissão).
2. **`categorias`:** Classificação dos produtos (ex: Processadores, Placas de Vídeo, Memórias).
3. **`produtos`:** Armazena detalhes do produto, valor, estoque, imagem e chave estrangeira `categoria_id`.
4. **`pedidos`:** Registra a compra realizada pelo usuário (data, status do pedido, total e `usuario_id`).
5. **`itens_pedido`:** Tabela associativa (N:N) que liga cada pedido aos produtos comprados, gravando a quantidade e o preço unitário no momento da compra.

### Exemplo de DDL (Criação da Tabela Produtos)

```sql
CREATE TABLE produtos (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10, 2) NOT NULL,
    estoque INT DEFAULT 0,
    imagem VARCHAR(255),
    categoria_id INT REFERENCES categorias(id) ON DELETE SET NULL
);
```

---

## 4. Conceitos Fundamentais Usados no Código

### 4.1. Modularização com `include` e `require`

Em vez de duplicar o código HTML do topo e do rodapé em dezenas de páginas, utilizamos arquivos reutilizáveis:

```php
<?php
// Inclui as configurações globais e o cabeçalho visual com HTML / CSS
require_once '../includes/config.php';
include '../includes/header.php';
?>

<main class="container">
    <h1>Bem-vindo à TechZone</h1>
    <!-- Conteúdo específico da página -->
</main>

<?php
// Inclui o rodapé contendo o fechamento das tags </body> e </html>
include '../includes/footer.php';
?>
```

### 4.2. Manipulação Dinâmica HTML/PHP (A alternativa elegante ao `echo` em blocos)

Diferente de escrever todo o HTML dentro de comandos `echo`, abrimos e fechamos os blocos de PHP apenas quando necessário para injetar dados dinâmicos:

```php
<div class="grid-produtos">
    <?php foreach ($produtos as $produto): ?>
        <div class="card-produto">
            <img src="<?php echo $produto['imagem']; ?>" alt="<?php echo $produto['nome']; ?>">
            <h3><?php echo $produto['nome']; ?></h3>
            <p class="preco">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
            <a href="produto.php?id=<?php echo $produto['id']; ?>" class="btn">Ver Detalhes</a>
        </div>
    <?php endforeach; ?>
</div>
```

### 4.3. Gerenciamento de Sessão e Autenticação

A variável superglobal `$_SESSION` é utilizada para guardar dados do usuário conectado em todas as requisições:

```php
<?php
session_start();

// Exemplo de verificação de login no painel administrativo
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../login/login.php');
    exit;
}
?>
```

### 4.4. Carrinho de Compras em Memória

O carrinho armazena os itens selecionados dentro da própria sessão antes do fechamento do pedido:

```php
<?php
session_start();

// Adicionar item ao carrinho
$id_produto = $_POST['produto_id'];
$quantidade = $_POST['quantidade'];

if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

if (isset($_SESSION['carrinho'][$id_produto])) {
    $_SESSION['carrinho'][$id_produto] += $quantidade;
} else {
    $_SESSION['carrinho'][$id_produto] = $quantidade;
}
?>
```

---

## 5. Resumo da Apresentação e Boas Práticas

1. **Reutilização de Código:** O uso do `header.php` centraliza as tags iniciais do HTML (`<!DOCTYPE html>`, `<head>`, estilos e navegação) em um único local.
2. **Segurança:** Senhas registradas via algoritmo de hash seguro e controle rígido de acesso às páginas administrativas.
3. **Organização Comercial:** Separação limpa entre a área pública (compras, catálogo) e a área de gestão (painel admin).