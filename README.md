# 🖥️ TechZone — Documentação Técnica

> 🛒 Sistema web de e-commerce para venda de componentes e equipamentos de informática.

A **TechZone** é uma aplicação web desenvolvida em PHP para simular o funcionamento de uma loja virtual especializada em hardware e tecnologia.

O sistema possui área pública para clientes, autenticação de usuários, catálogo de produtos, categorias, carrinho de compras, checkout, pedidos e um painel administrativo para gerenciamento das informações da loja.

---

## 📑 Sumário

1. [Visão geral do projeto](#-1-visão-geral-do-projeto)
2. [Tecnologias utilizadas](#-2-tecnologias-utilizadas)
3. [Estrutura do projeto](#-3-estrutura-do-projeto)
4. [Arquitetura e fluxo do sistema](#-4-arquitetura-e-fluxo-do-sistema)
5. [Funcionalidades](#-5-funcionalidades)
6. [Banco de dados](#-6-banco-de-dados)
7. [Dump e restauração do banco](#-7-dump-e-restauração-do-banco)
8. [Configuração](#-8-configuração)
9. [Como executar o projeto](#-9-como-executar-o-projeto)
10. [Segurança](#-10-segurança)
11. [Controle de acesso](#-11-controle-de-acesso)
12. [Testes recomendados](#-12-testes-recomendados)
13. [Conceitos aplicados](#-13-conceitos-aplicados)
14. [Possíveis melhorias](#-14-possíveis-melhorias)
15. [Desenvolvedor](#-15-desenvolvedor)

---

# 1. Visão geral do projeto

## 1.1 Objetivo

A TechZone foi desenvolvida com o objetivo de criar uma aplicação de e-commerce funcional, aplicando conceitos de desenvolvimento web, programação em PHP, banco de dados relacional, autenticação, sessões, CRUD e organização modular.

O sistema permite que um visitante navegue pelos produtos e que usuários cadastrados realizem compras através do carrinho e do checkout.

Além disso, existe uma área administrativa responsável pelo gerenciamento dos principais dados da plataforma.

---

## 1.2 Principais funcionalidades

### Área do cliente

- Cadastro de usuário;
- Login;
- Logout;
- Autenticação através de sessões;
- Visualização de produtos;
- Visualização de categorias;
- Carrinho de compras;
- Alteração de quantidade;
- Remoção de produtos do carrinho;
- Checkout;
- Criação de pedidos;
- Consulta das informações relacionadas à compra.

### Área administrativa

- Login administrativo;
- Gerenciamento de usuários;
- Gerenciamento de produtos;
- Gerenciamento de categorias;
- Gerenciamento de pedidos;
- Cadastro de registros;
- Consulta de registros;
- Atualização de registros;
- Exclusão de registros;
- Controle de permissões.

### Montador de PC

A aplicação também possui um **Montador de PC**, permitindo selecionar componentes disponíveis na loja para montar uma configuração personalizada.

---

# 2. Tecnologias utilizadas

| Camada             | Tecnologia       |
| ------------------ | ---------------- |
| Back-end           | PHP              |
| Front-end          | HTML5 + CSS3     |
| Banco de dados     | PostgreSQL       |
| Acesso ao banco    | PDO / PostgreSQL |
| Servidor web       | Apache           |
| Controle de versão | Git / GitHub     |
| Autenticação       | Sessões PHP      |

A aplicação utiliza PHP para processamento das requisições, autenticação, gerenciamento de sessões, comunicação com o banco de dados, carrinho, pedidos e funcionalidades administrativas.

O HTML5 é utilizado na estrutura das páginas e o CSS3 na criação do layout e estilização da interface.

---

# 3. Estrutura do projeto

A estrutura principal do repositório é organizada da seguinte maneira:

```text
ProjetoFinal/
│
├── index.php
├── README.md
│
└── techzone/
    │
    ├── admin/
    │   └── Painel administrativo
    │
    ├── database/
    │   ├── techzone.sql
    │   └── migrar-para-postgres.php
    │
    ├── includes/
    │   ├── config.php
    │   ├── conexao.php
    │   ├── functions.php
    │   ├── header.php
    │   └── footer.php
    │
    ├── login/
    │   ├── login.php
    │   ├── cadastro.php
    │   └── logout.php
    │
    ├── paginas/
    │   ├── produtos.php
    │   ├── carrinho.php
    │   ├── checkout.php
    │   └── monte_pc.php
    │
    └── style/
        └── style.css
```

A estrutura acima corresponde à organização apresentada atualmente no repositório da TechZone.

---

## 3.1 Principais diretórios

### `admin/`

Contém os recursos relacionados à área administrativa da aplicação.

### `database/`

Contém os arquivos relacionados ao banco de dados, incluindo o dump SQL.

```text
database/
├── techzone.sql
└── migrar-para-postgres.php
```

### `includes/`

Concentra arquivos compartilhados pela aplicação.

```text
includes/
├── config.php
├── conexao.php
├── functions.php
├── header.php
└── footer.php
```

### `login/`

Responsável pelas funcionalidades de autenticação.

```text
login/
├── login.php
├── cadastro.php
└── logout.php
```

### `paginas/`

Contém as principais páginas da aplicação.

```text
paginas/
├── produtos.php
├── carrinho.php
├── checkout.php
└── monte_pc.php
```

### `style/`

Contém os arquivos responsáveis pela estilização da aplicação.

---

# 4. Arquitetura e fluxo do sistema

A arquitetura da aplicação pode ser representada de forma simplificada:

```text
┌──────────────────────┐
│       Usuário        │
└──────────┬───────────┘
           │
           ▼
┌──────────────────────┐
│    Interface Web     │
│     PHP / HTML /     │
│        CSS           │
└──────────┬───────────┘
           │
           ▼
┌──────────────────────┐
│ Lógica da aplicação  │
│      functions.php   │
└──────────┬───────────┘
           │
           ▼
┌──────────────────────┐
│      conexao.php     │
│        PDO/PGSQL     │
└──────────┬───────────┘
           │
           ▼
┌──────────────────────┐
│      PostgreSQL      │
└──────────────────────┘
```

O fluxo geral segue a comunicação entre a interface, as regras da aplicação, a conexão com o banco e o PostgreSQL.

---

# 5. Funcionalidades

## 5.1 Cadastro e login

O usuário pode criar uma conta através da página de cadastro.

Após o cadastro, pode realizar login para acessar funcionalidades que exigem autenticação.

A aplicação utiliza sessões PHP para manter o usuário autenticado durante a navegação.

Exemplo:

```php
session_start();
```

As informações da sessão são utilizadas para identificar o usuário conectado.

---

## 5.2 Catálogo de produtos

Os produtos são armazenados no banco de dados e apresentados dinamicamente pela aplicação.

Cada produto pode possuir informações como:

- Nome;
- Descrição;
- Preço;
- Estoque;
- Imagem;
- Categoria.

A utilização do banco de dados permite gerenciar os produtos sem precisar alterar manualmente o código das páginas.

---

## 5.3 Categorias

Os produtos são organizados através de categorias.

Isso facilita a organização do catálogo e permite separar os diferentes tipos de componentes e equipamentos disponíveis.

---

## 5.4 Carrinho

O carrinho utiliza a sessão do PHP para manter os produtos selecionados pelo usuário.

O usuário pode:

- Adicionar produtos;
- Alterar quantidades;
- Remover produtos;
- Visualizar os produtos selecionados;
- Continuar comprando;
- Avançar para o checkout.

Fluxo:

```text
Produto
   │
   ▼
Adicionar ao carrinho
   │
   ▼
Carrinho
   │
   ├── Alterar quantidade
   │
   ├── Remover produto
   │
   ▼
Checkout
```

---

## 5.5 Checkout e pedidos

Após selecionar os produtos, o usuário pode avançar para o checkout.

O pedido relaciona o usuário aos produtos adquiridos.

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

Cada item do pedido representa um produto e sua respectiva quantidade.

---

## 5.6 Montador de PC

O Montador de PC permite que o usuário selecione componentes para montar uma configuração personalizada.

O objetivo é centralizar a seleção das peças em uma única interface.

Fluxo:

```text
Montador de PC
      │
      ├── Processador
      ├── Placa de vídeo
      ├── Memória
      ├── Outros componentes
      │
      ▼
Configuração final
```

---

## 5.7 Painel administrativo

O painel administrativo é responsável pelo gerenciamento das informações da loja.

As operações incluem:

- Criar;
- Consultar;
- Atualizar;
- Excluir.

Essas operações correspondem ao conceito de **CRUD**.

---

# 6. Banco de dados

A TechZone utiliza **PostgreSQL** como banco de dados.

O banco armazena as principais informações necessárias para o funcionamento da aplicação.

Entre elas:

- Usuários;
- Categorias;
- Produtos;
- Pedidos;
- Itens dos pedidos.

A aplicação utiliza uma conexão através do PDO para realizar as operações com o PostgreSQL.

---

## 6.1 Relacionamento dos dados

A estrutura pode ser representada de forma simplificada:

```text
┌──────────────┐
│   Usuários   │
└──────┬───────┘
       │
       │ 1:N
       ▼
┌──────────────┐
│   Pedidos    │
└──────┬───────┘
       │
       │ 1:N
       ▼
┌──────────────────┐
│ Itens do pedido  │
└────────┬─────────┘
         │
         │ N:1
         ▼
┌──────────────┐
│   Produtos   │
└──────┬───────┘
       │
       │ N:1
       ▼
┌──────────────┐
│  Categorias  │
└──────────────┘
```

---

# 7. Dump e restauração do banco

O projeto utiliza um arquivo SQL para permitir a restauração da estrutura e dos dados do banco.

O dump está localizado em:

```text
database/techzone.sql
```

### Restauração

Com o PostgreSQL instalado e o banco criado, utilize:

```bash
psql -U techzone -h localhost -d techzone -f database/techzone.sql
```

### O que o comando faz

| Parâmetro                 | Função                                    |
| ------------------------- | ----------------------------------------- |
| `psql`                    | Cliente de linha de comando do PostgreSQL |
| `-U techzone`              | Usuário utilizado para acessar o banco    |
| `-h localhost`            | Servidor PostgreSQL local                 |
| `-d techzone`              | Banco de dados que receberá os dados      |
| `-f database/nortrek.sql` | Arquivo SQL utilizado na restauração      |

> **Importante:** o banco `techzone` e o usuário `techzone` precisam existir antes da execução do comando.

### Exemplo

```text
PostgreSQL
    │
    ▼
Banco: techzone
    │
    ▼
psql
    │
    ▼
database/techzone.sql
    │
    ▼
Estrutura + dados restaurados
```

O modelo de documentação do techzone também utiliza a abordagem de versionar um dump SQL dentro de `database/` e restaurá-lo através do `psql`, seguindo o mesmo padrão que estamos adotando aqui.

---

# 8. Configuração

## 8.1 Configuração da aplicação

As principais configurações ficam em:

```text
techzone/includes/config.php
```

Entre as configurações estão:

- Caminho base da aplicação;
- Host do banco;
- Porta;
- Nome do banco;
- Usuário;
- Senha.

Exemplo de caminho base:

```php
define("BASE_URL", "/techzone");
```

O valor deve ser ajustado de acordo com o local onde a aplicação estiver instalada.

---

## 8.2 Configuração do banco

A conexão deve ser configurada de acordo com o ambiente utilizado.

Exemplo:

```text
Host: localhost
Porta: 5432
Banco: techzone
Usuário: techzone
Senha: sua_senha
```

> Utilize os valores correspondentes ao ambiente em que a TechZone estiver sendo executada.

---

# 9. Como executar o projeto

## 9.1 Requisitos

Para executar a TechZone, é necessário possuir:

- PHP;
- PostgreSQL;
- Apache ou servidor web compatível;
- Extensão PostgreSQL do PHP;
- Git;
- Navegador web.

---

## 9.2 Clonar o repositório

O repositório oficial é:

```text
https://github.com/Cristantenicollas/TechZone
```

Clone utilizando:

```bash
git clone https://github.com/Cristantenicollas/TechZone.git
```

Entre na pasta:

```bash
cd TechZone
```

> `ProjetoFinal` é apenas o nome utilizado no caminho do ambiente onde a aplicação está hospedada. O repositório atual do GitHub se chama **TechZone**, portanto o comando de clone utiliza `TechZone`. O repositório público atualmente possui `techzone/`, `index.php` e `README.md` em sua raiz.

---

## 9.3 Configurar o banco

Crie o banco PostgreSQL utilizado pela aplicação.

Depois, restaure o dump:

```bash
psql -U techzone -h localhost -d techzone -f database/techzone.sql
```

> Execute o comando a partir do diretório em que a pasta `database/` está localizada.

Se o arquivo estiver dentro de `techzone/database/`, execute:

```bash
psql -U techzone -h localhost -d techzone -f techzone/database/techzone.sql
```

---

## 9.4 Configurar a conexão

Edite:

```text
techzone/includes/config.php
```

e/ou:

```text
techzone/includes/conexao.php
```

Configure as credenciais correspondentes ao PostgreSQL do ambiente.

---

## 9.5 Configurar o Apache

Coloque o projeto dentro do diretório utilizado pelo Apache.

Exemplo:

```text
/var/www/html/
└── TechZone/
    ├── index.php
    ├── README.md
    └── techzone/
```

Depois, inicie o Apache e o PostgreSQL.

---

## 9.6 Acessar a aplicação

Depois de iniciar os serviços, abra a aplicação pelo navegador.

Exemplo:

```text
http://localhost/TechZone/
```

Caso o projeto esteja hospedado em outro servidor, utilize o endereço correspondente ao ambiente.

---

# 10. Segurança

A aplicação utiliza mecanismos básicos de segurança para controle dos usuários.

Entre eles:

- Sessões PHP;
- Controle de autenticação;
- Restrição da área administrativa;
- Controle de permissões;
- Hash de senhas;
- Separação entre área pública e administrativa;
- Validação de acesso.

A autenticação utiliza:

```php
$_SESSION
```

---

# 11. Controle de acesso

A aplicação diferencia usuários comuns de usuários administrativos.

De forma simplificada:

```text
                    Usuário
                       │
                       ▼
                    Login
                       │
             ┌─────────┴─────────┐
             │                   │
             ▼                   ▼
          Cliente             Admin
             │                   │
             ▼                   ▼
       Área da loja       Painel administrativo
```

Usuários sem permissão administrativa não devem conseguir acessar diretamente funcionalidades restritas ao administrador.

---

# 12. Testes recomendados

## Usuário

- [ ] Criar uma conta;
- [ ] Realizar login;
- [ ] Realizar logout;
- [ ] Visualizar produtos;
- [ ] Visualizar categorias;
- [ ] Adicionar produto ao carrinho;
- [ ] Alterar quantidade;
- [ ] Remover produto;
- [ ] Realizar checkout;
- [ ] Criar pedido.

## Administrador

- [ ] Realizar login administrativo;
- [ ] Cadastrar produto;
- [ ] Consultar produto;
- [ ] Editar produto;
- [ ] Excluir produto;
- [ ] Cadastrar categoria;
- [ ] Editar categoria;
- [ ] Excluir categoria;
- [ ] Consultar usuários;
- [ ] Consultar pedidos;
- [ ] Verificar controle de acesso.

## Banco de dados

- [ ] Criar banco PostgreSQL;
- [ ] Restaurar o dump;
- [ ] Verificar as tabelas;
- [ ] Verificar os relacionamentos;
- [ ] Testar conexão PHP/PostgreSQL;
- [ ] Testar inserção e consulta de dados.

---

# 13. Conceitos aplicados

Durante o desenvolvimento foram aplicados conceitos de:

- Programação em PHP;
- Programação procedural;
- Desenvolvimento web;
- HTML5;
- CSS3;
- CRUD;
- Banco de dados relacional;
- PostgreSQL;
- PDO;
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
- Modularização;
- Separação de responsabilidades;
- E-commerce;
- Gerenciamento de estoque;
- Gerenciamento de pedidos.

---

# 14. Possíveis melhorias

Algumas funcionalidades podem ser adicionadas em versões futuras:

- Integração com meios de pagamento;
- Integração com serviços de frete;
- Recuperação de senha;
- Sistema de favoritos;
- Avaliação de produtos;
- Cupons de desconto;
- Filtros avançados;
- Melhorias no Montador de PC;
- Verificação automática de compatibilidade entre componentes;
- Dashboard administrativo;
- Gráficos de vendas;
- Histórico detalhado de pedidos;
- Melhorias de responsividade.

---

# 15. Desenvolvedor

## 👨‍💻 Nicollas Cristante Gonçalves

Projeto desenvolvido para fins acadêmicos, com foco na aplicação prática de conceitos de desenvolvimento web, banco de dados e programação.

### Repositório

🔗 **GitHub:**
[https://github.com/Cristantenicollas/TechZone](https://github.com/Cristantenicollas/TechZone)

---

## 📄 Licença

Projeto desenvolvido para fins acadêmicos e de aprendizado.
