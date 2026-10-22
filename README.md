# 🖥️ TechZone

### 🛒 E-commerce de hardware e tecnologia

A **TechZone** é uma aplicação web de e-commerce desenvolvida para simular uma loja virtual especializada em **componentes, peças e equipamentos para computadores**.

O sistema permite que usuários criem suas contas, façam login, naveguem pelos produtos, adicionem itens ao carrinho, realizem pedidos e montem configurações personalizadas de computador.

A aplicação também possui uma **área administrativa**, responsável pelo gerenciamento dos produtos, categorias, usuários e pedidos.

---

## 📌 Sobre o projeto

A TechZone foi desenvolvida como um projeto acadêmico com o objetivo de aplicar, na prática, conceitos de:

- Desenvolvimento web;
- PHP;
- HTML5 e CSS3;
- Banco de dados relacional;
- PostgreSQL;
- Autenticação e sessões;
- CRUD;
- Relacionamento entre tabelas;
- Controle de acesso;
- Organização e modularização de código.

A aplicação foi construída buscando representar o funcionamento básico de uma loja virtual real, desde a apresentação dos produtos até o registro dos pedidos.

---

# 🚀 Funcionalidades

## 👤 Sistema de usuários

O sistema possui autenticação de usuários através de sessões.

### Cadastro

Permite que novos usuários criem uma conta para utilizar as funcionalidades que exigem autenticação.

### Login

O usuário pode acessar sua conta através de e-mail e senha.

Após o login, a aplicação utiliza a sessão para identificar o usuário durante sua navegação.

### Logout

Permite encerrar a sessão do usuário com segurança.

---

## 🛍️ Catálogo de produtos

Os produtos são carregados dinamicamente a partir do banco de dados.

Cada produto pode possuir informações como:

- Nome;
- Descrição;
- Preço;
- Estoque;
- Imagem;
- Categoria.

A utilização do banco de dados permite adicionar e alterar produtos sem precisar modificar diretamente o código das páginas.

---

## 📦 Categorias

Os produtos são organizados através de categorias, facilitando a organização do catálogo.

Entre os tipos de produtos trabalhados pelo projeto estão componentes e peças de computador, como:

- Processadores;
- Placas de vídeo;
- Memórias;
- Componentes de hardware;
- Outros equipamentos de informática.

---

# 🛒 Carrinho de compras

A TechZone possui um carrinho de compras baseado em **sessões do PHP**.

O usuário pode:

- Adicionar produtos;
- Definir quantidades;
- Adicionar novamente produtos já presentes no carrinho;
- Visualizar os produtos selecionados;
- Alterar quantidades;
- Remover produtos;
- Continuar navegando pela loja;
- Avançar para o checkout.

Os produtos selecionados ficam armazenados na sessão enquanto o usuário navega pelo sistema.

Exemplo da estrutura utilizada:

```php
$_SESSION['carrinho']
```

---

# 💳 Checkout e pedidos

Depois de adicionar os produtos ao carrinho, o usuário pode avançar para o checkout e finalizar o pedido.

O sistema mantém o relacionamento entre:

```text
Usuário
   │
   ▼
Pedido
   │
   ▼
Itens do pedido
   │
   ▼
Produtos
```

Dessa forma, um pedido pode possuir diversos produtos e cada produto pode fazer parte de diferentes pedidos.

Os itens do pedido armazenam informações importantes, como:

- Produto;
- Pedido;
- Quantidade;
- Preço unitário no momento da compra.

Guardar o preço no momento da compra permite manter o valor original mesmo que o preço atual do produto seja alterado posteriormente.

---

# 🖥️ Montador de PC

A TechZone possui um **Montador de PC**, desenvolvido para permitir que o usuário monte uma configuração de computador utilizando os componentes disponíveis na loja.

A funcionalidade centraliza a escolha das peças em uma única interface, proporcionando uma experiência mais próxima de uma loja especializada em hardware.

---

# 👨‍💼 Painel administrativo

A aplicação possui uma área administrativa separada da área destinada aos clientes.

O administrador pode gerenciar os principais dados do sistema, incluindo:

- Usuários;
- Produtos;
- Categorias;
- Pedidos.

Também estão disponíveis operações de:

- Cadastro;
- Consulta;
- Edição;
- Exclusão.

O acesso ao painel administrativo é protegido através do sistema de autenticação e permissões.

---

# 🔄 CRUD

A área administrativa utiliza o conceito de **CRUD**:

| Operação | Significado | Aplicação |
|---|---|---|
| **Create** | Criar | Cadastro de registros |
| **Read** | Consultar | Visualização dos dados |
| **Update** | Atualizar | Edição de registros |
| **Delete** | Excluir | Remoção de registros |

Essas operações são utilizadas principalmente no gerenciamento dos dados da loja.

---

# 🗄️ Banco de dados

O projeto utiliza um banco de dados relacional para armazenar as informações da aplicação.

A estrutura principal possui entidades relacionadas entre si, como:

```text
Usuários
   │
   └────── Pedidos
              │
              └────── Itens do pedido
                           │
                           └────── Produtos
                                      │
                                      └────── Categorias
```

### Principais tabelas

#### `usuarios`

Armazena os usuários cadastrados e suas informações de acesso.

#### `categorias`

Armazena as categorias utilizadas para organizar os produtos.

#### `produtos`

Armazena os produtos disponíveis na loja.

Entre os dados armazenados estão:

- Nome;
- Descrição;
- Preço;
- Estoque;
- Imagem;
- Categoria.

#### `pedidos`

Registra as compras realizadas pelos usuários.

Entre as informações estão:

- ID do pedido;
- Usuário;
- Data;
- Status;
- Valor total.

#### `itens_pedido`

Relaciona os pedidos aos produtos adquiridos.

Armazena informações como:

- Pedido;
- Produto;
- Quantidade;
- Preço unitário.

---

# 🔐 Segurança e autenticação

O sistema utiliza sessões do PHP para controlar usuários autenticados.

As áreas protegidas verificam se existe uma sessão válida antes de permitir o acesso.

Também são utilizados conceitos como:

- Controle de sessões;
- Restrição de acesso;
- Controle de permissões;
- Hash de senhas;
- Separação entre área pública e administrativa;
- Validação de acesso.

Exemplo de controle de sessão:

```php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../login/login.php');
    exit;
}
```

A aplicação diferencia usuários comuns de usuários com permissões administrativas.

---

# 🧩 Organização do projeto

A estrutura principal do repositório é organizada da seguinte maneira:

```text
TechZone/
│
├── techzone/
│   ├── admin/
│   │   └── Área administrativa
│   │
│   ├── database/
│   │   ├── techzone.sql
│   │   └── migrar-para-postgres.php
│   │
│   ├── includes/
│   │   ├── config.php
│   │   ├── conexao.php
│   │   ├── functions.php
│   │   ├── header.php
│   │   └── footer.php
│   │
│   ├── login/
│   │   ├── login.php
│   │   ├── cadastro.php
│   │   └── logout.php
│   │
│   ├── paginas/
│   │   ├── produtos.php
│   │   ├── carrinho.php
│   │   ├── checkout.php
│   │   └── monte_pc.php
│   │
│   └── style/
│       └── style.css
│
├── index.php
└── README.md
```

Essa estrutura separa as principais responsabilidades da aplicação, facilitando a manutenção e a organização do código.

---

# 🧱 Modularização

O projeto utiliza arquivos compartilhados para evitar repetição de código.

### `config.php`

Centraliza configurações da aplicação, como caminho base e informações utilizadas na conexão.

### `conexao.php`

Responsável pela conexão da aplicação com o banco de dados.

### `functions.php`

Concentra funções auxiliares utilizadas em diferentes partes do sistema.

### `header.php`

Centraliza elementos comuns presentes no início das páginas.

### `footer.php`

Centraliza elementos comuns presentes no final das páginas.

Essa organização facilita alterações futuras e evita duplicação de código.

---

# 🎨 Interface

A interface da TechZone utiliza:

- **HTML5** para estrutura;
- **CSS3** para estilização;
- **PHP** para geração dinâmica dos conteúdos.

O estilo global da aplicação é centralizado no arquivo:

```text
techzone/style/style.css
```

Isso permite manter uma identidade visual consistente entre as diferentes páginas do sistema.

---

# ⚙️ Tecnologias utilizadas

| Tecnologia | Utilização |
|---|---|
| **PHP** | Backend e lógica da aplicação |
| **HTML5** | Estrutura das páginas |
| **CSS3** | Interface e estilização |
| **PostgreSQL** | Banco de dados |
| **Git** | Controle de versão |
| **Apache** | Servidor web local |

---

# 💻 Como executar

## 1. Requisitos

Para executar a TechZone localmente, você precisará de:

- PHP;
- PostgreSQL;
- Apache ou outro servidor compatível com PHP;
- Git;
- Navegador.

---

## 2. Clonar o repositório

Clone o repositório oficial:

```bash
git clone https://github.com/Cristantenicollas/TechZone.git
```

Entre na pasta:

```bash
cd TechZone
```

> O repositório atual é `TechZone`. A referência antiga a `ProjetoFinal` não deve mais ser utilizada.

---

## 3. Configurar o banco de dados

O projeto possui arquivos relacionados ao banco dentro de:

```text
techzone/database/
```

O arquivo principal de estrutura do banco é:

```text
techzone/database/techzone.sql
```

Crie o banco de dados no PostgreSQL e execute o script SQL para criar as tabelas necessárias.

---

## 4. Configurar a conexão

As configurações de conexão ficam nos arquivos dentro de:

```text
techzone/includes/
```

Configure de acordo com o seu ambiente:

```text
Host
Porta
Nome do banco
Usuário
Senha
```

Também confira a configuração do caminho base da aplicação.

Exemplo:

```php
define("BASE_URL", "/techzone");
```

---

## 5. Executar a aplicação

Coloque o projeto no diretório utilizado pelo seu servidor web.

Por exemplo:

```text
htdocs/
└── TechZone/
    ├── index.php
    └── techzone/
```

Inicie o Apache e o PostgreSQL.

Depois, acesse o endereço correspondente ao local onde o projeto foi instalado.

Exemplo:

```text
http://localhost/TechZone/
```

---

# 🔁 Fluxo principal

O funcionamento básico da aplicação pode ser representado da seguinte maneira:

```text
                  INÍCIO
                     │
                     ▼
              Página inicial
                     │
             ┌───────┴───────┐
             │               │
             ▼               ▼
         Produtos          Login
             │               │
             ▼               ▼
     Detalhes do produto   Cadastro
             │
             ▼
       Adicionar ao
         carrinho
             │
             ▼
          Carrinho
             │
             ▼
          Checkout
             │
             ▼
        Criar pedido
             │
             ▼
         Finalização
```

---

# 👨‍💼 Fluxo administrativo

```text
       Login administrativo
                │
                ▼
       Verificação de acesso
                │
                ▼
          Painel Admin
                │
       ┌────────┼────────┐
       │        │        │
       ▼        ▼        ▼
   Produtos Categorias Usuários
       │
       ▼
    Pedidos
```

---

# 📚 Conceitos aplicados

Durante o desenvolvimento foram utilizados conceitos de:

- Programação em PHP;
- Desenvolvimento web;
- CRUD;
- Banco de dados relacional;
- PostgreSQL;
- Chaves primárias;
- Chaves estrangeiras;
- Relacionamentos entre tabelas;
- Sessões;
- Autenticação;
- Controle de permissões;
- Hash de senhas;
- Manipulação de formulários;
- Processamento de dados;
- HTML dinâmico;
- CSS;
- Modularização;
- E-commerce;
- Gerenciamento de estoque;
- Gerenciamento de pedidos.

---

# 🧪 Testes recomendados

Após configurar e executar o sistema, recomenda-se testar:

### Usuário

- [ ] Cadastro
- [ ] Login
- [ ] Logout
- [ ] Visualização de produtos
- [ ] Adição ao carrinho
- [ ] Alteração de quantidade
- [ ] Remoção do carrinho
- [ ] Checkout
- [ ] Criação de pedido

### Administrador

- [ ] Login administrativo
- [ ] Cadastro de produtos
- [ ] Edição de produtos
- [ ] Exclusão de produtos
- [ ] Cadastro de categorias
- [ ] Edição de categorias
- [ ] Exclusão de categorias
- [ ] Consulta de usuários
- [ ] Consulta de pedidos

### Montador

- [ ] Seleção de componentes
- [ ] Montagem da configuração
- [ ] Verificação dos produtos selecionados

---

# 📈 Melhorias futuras

Algumas funcionalidades que podem ser adicionadas futuramente:

- Sistema de pagamento online;
- Integração com serviços de frete;
- Recuperação de senha;
- Histórico de pedidos;
- Sistema de favoritos;
- Avaliação de produtos;
- Cupons de desconto;
- Filtros avançados;
- Verificação automática de compatibilidade no Montador de PC;
- Dashboard administrativo;
- Gráficos e estatísticas;
- Melhorias de responsividade.

---

# 🎓 Objetivo acadêmico

A TechZone foi desenvolvida como um projeto acadêmico para aplicar conhecimentos de **programação, desenvolvimento web e banco de dados** em uma aplicação prática.

O projeto busca representar uma loja virtual funcional, envolvendo desde a interface do usuário até o gerenciamento de dados, autenticação, carrinho e pedidos.

---

# 👨‍💻 Desenvolvedor

**Nicollas Cristante Gonçalves**

🔗 **Repositório:**  
[GitHub — Cristantenicollas/TechZone](https://github.com/Cristantenicollas/TechZone?utm_source=chatgpt.com)

---

## 📄 Licença

Projeto desenvolvido para fins acadêmicos e de aprendizado.
