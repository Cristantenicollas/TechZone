# 🖥️ TechZone

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8%2B-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/PostgreSQL-Supported-4169E1?style=for-the-badge&logo=postgresql&logoColor=white" alt="PostgreSQL">
  <img src="https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white" alt="HTML5">
  <img src="https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white" alt="CSS3">
  <img src="https://img.shields.io/badge/Status-Acadêmico-success?style=for-the-badge" alt="Status">
</p>

<p align="center">
  <strong>🛒 E-commerce de componentes e equipamentos para computadores</strong>
</p>

<p align="center">
  Sistema web desenvolvido como Projeto Final, com foco em comércio eletrônico,
  gerenciamento de produtos, autenticação de usuários, carrinho de compras,
  pedidos e administração da loja.
</p>

---

## 📌 Sobre o projeto

A **TechZone** é uma plataforma de comércio eletrônico voltada para o mercado de hardware e tecnologia.

O sistema foi desenvolvido com o objetivo de simular o funcionamento de uma loja virtual completa, permitindo que usuários naveguem pelos produtos, consultem informações, adicionem itens ao carrinho e realizem pedidos.

Além da área destinada aos clientes, o projeto conta com uma **área administrativa**, responsável pelo gerenciamento dos principais dados da plataforma.

O projeto foi desenvolvido utilizando **PHP, HTML5, CSS3 e banco de dados relacional**, aplicando conceitos de programação web, modelagem de banco de dados, autenticação, sessões, CRUD e organização modular do código.

---

## 🎯 Objetivos

O projeto teve como principais objetivos:

- Desenvolver um sistema de e-commerce funcional;
- Aplicar conceitos de desenvolvimento web;
- Trabalhar com PHP no processamento das informações;
- Utilizar banco de dados relacional;
- Implementar sistema de cadastro e login;
- Trabalhar com sessões de usuários;
- Desenvolver um carrinho de compras;
- Criar um fluxo de pedidos;
- Desenvolver um painel administrativo;
- Implementar operações CRUD;
- Organizar o projeto de forma modular;
- Criar uma interface adequada para uma loja de informática;
- Aplicar conceitos básicos de segurança;
- Desenvolver uma estrutura que permita futuras expansões.

---

# 🚀 Funcionalidades

## 👤 Área do usuário

O sistema possui funcionalidades destinadas ao cliente da loja.

### Cadastro

O usuário pode realizar seu cadastro informando seus dados para posteriormente acessar as funcionalidades que exigem autenticação.

### Login

O sistema possui autenticação de usuários através de sessão.

Após realizar o login, os dados necessários são armazenados na sessão para que o sistema consiga identificar o usuário durante sua navegação.

### Logout

O usuário pode encerrar sua sessão através da funcionalidade de logout.

---

## 🛍️ Catálogo de produtos

A TechZone possui uma área destinada à apresentação dos produtos disponíveis na loja.

Os produtos possuem informações como:

- Nome;
- Descrição;
- Preço;
- Estoque;
- Imagem;
- Categoria.

Os produtos são armazenados no banco de dados e carregados dinamicamente pela aplicação.

Isso permite que novos produtos sejam cadastrados sem a necessidade de alterar manualmente o código das páginas.

---

## 📦 Categorias

Os produtos são organizados através de categorias.

Entre os exemplos utilizados no projeto estão categorias relacionadas a:

- Processadores;
- Placas de vídeo;
- Memórias;
- Componentes de computador;
- Outros produtos de hardware.

A utilização de categorias facilita a organização e apresentação do catálogo.

---

## 🛒 Carrinho de compras

O sistema possui um carrinho de compras baseado em sessão.

O usuário pode:

- Adicionar produtos;
- Definir quantidade;
- Adicionar novamente um produto já existente;
- Visualizar os produtos selecionados;
- Alterar a quantidade;
- Remover produtos;
- Continuar comprando;
- Avançar para o checkout.

Os itens do carrinho são mantidos na sessão enquanto o usuário navega pelo sistema.

### Funcionamento

A estrutura utiliza a sessão do PHP para armazenar os produtos selecionados.

De forma simplificada:

```php
$_SESSION['carrinho']
```

é utilizada para manter os produtos e suas respectivas quantidades antes da finalização do pedido.

---

# 💳 Checkout e pedidos

Após selecionar os produtos, o usuário pode avançar para o processo de checkout.

Nesse processo são reunidas as informações necessárias para registrar a compra.

O sistema relaciona:

```text
Usuário
   ↓
Pedido
   ↓
Itens do pedido
   ↓
Produtos
```

Dessa maneira, um pedido pode possuir diversos produtos e cada produto pode aparecer em diferentes pedidos.

---

# 🖥️ Montador de PC

Uma das funcionalidades desenvolvidas para a TechZone é o **Montador de PC**.

A funcionalidade foi pensada para permitir que o usuário monte uma configuração de computador utilizando os componentes disponíveis no sistema.

A ideia é centralizar a escolha dos componentes em uma única interface, tornando a experiência mais próxima de uma loja especializada em hardware.

---

# 🔐 Sistema de autenticação

A aplicação possui controle de acesso baseado em sessões do PHP.

Exemplo simplificado da verificação:

```php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../login/login.php');
    exit;
}
```

Dessa forma, páginas que exigem autenticação podem verificar se existe um usuário conectado antes de permitir o acesso.

---

# 👨‍💼 Painel administrativo

A TechZone possui uma área administrativa separada da área pública da loja.

O painel foi desenvolvido para permitir o gerenciamento das informações utilizadas pelo sistema.

Entre as operações administrativas estão:

- Gerenciamento de usuários;
- Gerenciamento de categorias;
- Gerenciamento de produtos;
- Gerenciamento de pedidos;
- Cadastro de informações;
- Edição de registros;
- Exclusão de registros;
- Consulta dos dados cadastrados.

---

# 🔄 CRUD

O projeto aplica o conceito de **CRUD**:

| Operação | Significado | Aplicação |
|---|---|---|
| Create | Criar | Cadastro de produtos, categorias e usuários |
| Read | Ler | Consulta dos registros |
| Update | Atualizar | Edição dos registros |
| Delete | Excluir | Remoção dos registros |

Esse conceito é utilizado principalmente na área administrativa.

---

# 🗄️ Banco de dados

O sistema utiliza um banco de dados relacional para armazenar as informações da aplicação.

A estrutura principal é composta por tabelas relacionadas entre si.

## Principais tabelas

### `usuarios`

Responsável pelo armazenamento dos usuários do sistema.

Principais informações:

- ID;
- Nome;
- E-mail;
- Senha;
- Nível/permissão de acesso.

---

### `categorias`

Responsável por armazenar as categorias dos produtos.

Exemplos:

```text
Processadores
Placas de Vídeo
Memórias
```

---

### `produtos`

Responsável pelo armazenamento dos produtos disponíveis na loja.

Principais informações:

- ID;
- Nome;
- Descrição;
- Preço;
- Estoque;
- Imagem;
- Categoria.

A tabela possui relacionamento com `categorias`.

Exemplo de estrutura:

```sql
CREATE TABLE produtos (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10, 2) NOT NULL,
    estoque INT DEFAULT 0,
    imagem VARCHAR(255),
    categoria_id INT REFERENCES categorias(id)
);
```

---

### `pedidos`

Responsável por registrar as compras realizadas.

Entre as informações armazenadas estão:

- ID do pedido;
- Usuário responsável;
- Data;
- Status;
- Valor total.

---

### `itens_pedido`

Essa tabela funciona como uma tabela associativa entre pedidos e produtos.

Ela permite representar a relação:

```text
Pedido 1 ──────── N Itens
                     │
                     N
                     │
                     1
                  Produto
```

Cada item pode armazenar:

- Produto;
- Pedido;
- Quantidade;
- Preço unitário no momento da compra.

Guardar o preço no momento da compra é importante porque o preço atual de um produto pode mudar posteriormente.

---

# 🧩 Arquitetura do sistema

A aplicação foi organizada de maneira modular, separando responsabilidades.

```text
                    ┌──────────────────┐
                    │      Usuário     │
                    └────────┬─────────┘
                             │
                             ▼
                    ┌──────────────────┐
                    │   Interface Web  │
                    │   PHP / HTML /   │
                    │       CSS        │
                    └────────┬─────────┘
                             │
                             ▼
                    ┌──────────────────┐
                    │ Lógica da        │
                    │ aplicação        │
                    └────────┬─────────┘
                             │
                             ▼
                    ┌──────────────────┐
                    │ Banco de dados   │
                    │ MySQL/PostgreSQL │
                    └──────────────────┘
```

A estrutura separa principalmente:

- Interface;
- Regras e processamento;
- Autenticação;
- Banco de dados;
- Administração;
- Recursos compartilhados.

---

# 📁 Estrutura do projeto

A estrutura principal do projeto está organizada da seguinte forma:

```text
ProjetoFinal/
│
├── techzone/
│   │
│   ├── admin/
│   │   └── Painel administrativo
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
├── documentacao.md
└── index.php
```

---

# 🧱 Modularização

Um dos princípios utilizados no projeto foi evitar a repetição desnecessária de código.

Por exemplo, elementos comuns da interface são centralizados em arquivos reutilizáveis.

### Header

O arquivo:

```text
includes/header.php
```

é utilizado para centralizar elementos presentes no início das páginas.

### Footer

O arquivo:

```text
includes/footer.php
```

centraliza o conteúdo presente no final das páginas.

### Conexão

O arquivo:

```text
includes/conexao.php
```

é responsável pela conexão da aplicação com o banco de dados.

### Funções

O arquivo:

```text
includes/functions.php
```

concentra funções auxiliares utilizadas em diferentes partes do sistema.

Essa organização facilita a manutenção e evita que o mesmo código precise ser repetido em várias páginas.

---

# 🎨 Interface

A interface foi desenvolvida utilizando:

- HTML5;
- CSS3;
- PHP para conteúdo dinâmico.

O CSS global está concentrado em:

```text
style/style.css
```

A separação do estilo permite que diferentes páginas compartilhem a mesma identidade visual.

---

# 🔄 PHP + HTML

A aplicação utiliza PHP integrado ao HTML para gerar conteúdo dinamicamente.

Exemplo:

```php
<?php foreach ($produtos as $produto): ?>

    <div class="card-produto">

        <img
            src="<?php echo $produto['imagem']; ?>"
            alt="<?php echo $produto['nome']; ?>"
        >

        <h3>
            <?php echo $produto['nome']; ?>
        </h3>

        <p>
            R$ <?php echo number_format(
                $produto['preco'],
                2,
                ',',
                '.'
            ); ?>
        </p>

    </div>

<?php endforeach; ?>
```

Isso permite que os dados do banco sejam apresentados automaticamente na interface.

---

# 🔒 Segurança

Durante o desenvolvimento foram aplicados conceitos básicos de segurança para aplicações web.

Entre eles:

- Controle de sessão;
- Restrição de acesso à área administrativa;
- Senhas armazenadas utilizando hash;
- Separação entre área pública e administrativa;
- Validação de acesso;
- Controle dos usuários através do banco de dados.

A autenticação é baseada em sessão utilizando:

```php
$_SESSION
```

---

# 🔑 Controle de permissões

A aplicação diferencia usuários comuns da área administrativa através de níveis/permissões.

Isso permite restringir determinadas funcionalidades para usuários autorizados.

O objetivo é impedir que qualquer visitante consiga acessar diretamente funções administrativas.

---

# 🔌 Banco de dados e migração

O projeto possui scripts relacionados ao banco de dados dentro de:

```text
database/
```

Incluindo:

```text
techzone.sql
```

e:

```text
migrar-para-postgres.php
```

O projeto foi estruturado considerando tanto **MySQL quanto PostgreSQL**, permitindo adaptação da aplicação para diferentes ambientes de banco de dados.

---

# ⚙️ Tecnologias utilizadas

## Backend

### PHP

Utilizado para:

- Processamento das requisições;
- Autenticação;
- Sessões;
- Comunicação com o banco;
- Processamento de produtos;
- Carrinho;
- Pedidos;
- Administração.

---

## Frontend

### HTML5

Utilizado para estruturar as páginas da aplicação.

### CSS3

Utilizado para:

- Layout;
- Componentes visuais;
- Organização das páginas;
- Estilização;
- Responsividade.

---

## Banco de dados

### MySQL

Utilizado como uma das opções de banco relacional do projeto.

### PostgreSQL

O projeto também possui suporte/migração para PostgreSQL.

---

# 🛠️ Conceitos aplicados

Durante o desenvolvimento foram utilizados diversos conceitos de programação e desenvolvimento web:

- Programação procedural;
- Modularização;
- CRUD;
- Banco de dados relacional;
- Chaves primárias;
- Chaves estrangeiras;
- Relacionamentos entre tabelas;
- Sessões;
- Autenticação;
- Controle de acesso;
- Hash de senhas;
- Manipulação de formulários;
- Processamento de dados;
- HTML dinâmico;
- CSS;
- Separação de responsabilidades;
- Organização de arquivos;
- E-commerce;
- Gerenciamento de estoque;
- Gerenciamento de pedidos.

---

# 🔁 Fluxo principal da aplicação

O funcionamento básico da plataforma pode ser representado pelo seguinte fluxo:

```text
                    INÍCIO
                       │
                       ▼
                Página inicial
                       │
              ┌────────┴────────┐
              │                 │
              ▼                 ▼
          Produtos            Login
              │                 │
              ▼                 ▼
       Detalhes produto      Cadastro
              │
              ▼
      Adicionar ao carrinho
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

# 👨‍💻 Fluxo administrativo

```text
              Login administrativo
                       │
                       ▼
              Verificação de acesso
                       │
                       ▼
                Painel Admin
                       │
       ┌───────────────┼────────────────┐
       │               │                │
       ▼               ▼                ▼
   Produtos        Categorias        Usuários
       │
       ▼
    Pedidos
```

---

# 📦 Fluxo de um pedido

O processo de compra pode ser entendido da seguinte maneira:

```text
Produto
   ↓
Carrinho
   ↓
Quantidade
   ↓
Checkout
   ↓
Pedido
   ↓
Itens do pedido
   ↓
Banco de dados
```

O pedido fica associado ao usuário responsável pela compra e seus itens ficam relacionados aos produtos adquiridos.

---

# 💻 Como executar o projeto

## 1. Requisitos

Para executar o projeto localmente, é necessário possuir um ambiente de desenvolvimento PHP.

Recomendado:

- PHP;
- MySQL ou PostgreSQL;
- Servidor Apache ou equivalente;
- Navegador web;
- Git.

Ambientes como XAMPP, WAMP ou similares podem ser utilizados.

---

## 2. Clonar o repositório

```bash
git clone https://github.com/Cristantenicollas/ProjetoFinal.git
```

Entre no projeto:

```bash
cd ProjetoFinal
```

---

## 3. Configurar o banco de dados

Crie um banco de dados para a aplicação.

Depois, execute o arquivo:

```text
techzone/database/techzone.sql
```

Esse arquivo contém a estrutura necessária para criação das tabelas e configuração inicial do banco.

---

## 4. Configurar a conexão

Verifique as configurações presentes nos arquivos de conexão dentro de:

```text
techzone/includes/
```

Configure:

- Host;
- Porta;
- Nome do banco;
- Usuário;
- Senha.

---

## 5. Executar

Coloque o projeto no diretório correspondente ao seu servidor local.

Por exemplo, em ambientes XAMPP:

```text
htdocs/
└── ProjetoFinal/
```

Depois inicie:

```text
Apache
MySQL
```

e acesse o projeto pelo navegador.

---

# 🧪 Testes recomendados

Após iniciar o sistema, recomenda-se testar:

### Usuário

- [ ] Criar uma conta;
- [ ] Realizar login;
- [ ] Realizar logout;
- [ ] Visualizar produtos;
- [ ] Abrir detalhes de um produto;
- [ ] Adicionar produto ao carrinho;
- [ ] Alterar quantidade;
- [ ] Remover produto;
- [ ] Realizar checkout;
- [ ] Verificar criação do pedido.

### Administrador

- [ ] Realizar login administrativo;
- [ ] Cadastrar produto;
- [ ] Editar produto;
- [ ] Excluir produto;
- [ ] Cadastrar categoria;
- [ ] Editar categoria;
- [ ] Consultar usuários;
- [ ] Consultar pedidos;
- [ ] Verificar controle de acesso.

---

# 📚 Documentação adicional

O projeto possui um arquivo de documentação separado:

```text
documentacao.md
```

Nele estão descritos detalhes adicionais sobre:

- Arquitetura;
- Estrutura de arquivos;
- Banco de dados;
- Conceitos utilizados;
- Modularização;
- Sessões;
- Carrinho;
- Boas práticas.

---

# 📈 Possíveis melhorias futuras

Embora o projeto já possua uma estrutura completa de e-commerce, algumas funcionalidades podem ser adicionadas em versões futuras.

### 💳 Pagamentos

Integração com gateways de pagamento, como:

- PIX;
- Cartão de crédito;
- Boleto.

### 📦 Entregas

Implementação de:

- Cálculo de frete;
- Rastreamento;
- Integração com transportadoras;
- Consulta de CEP.

### 👤 Conta do cliente

Adicionar:

- Histórico de pedidos;
- Perfil;
- Alteração de dados;
- Endereços cadastrados;
- Acompanhamento do status do pedido.

### 🔎 Busca

Implementação de:

- Busca por nome;
- Filtro por categoria;
- Filtro por preço;
- Ordenação;
- Filtro por disponibilidade.

### 📊 Dashboard

O painel administrativo pode futuramente receber:

- Gráficos;
- Total de vendas;
- Produtos mais vendidos;
- Faturamento;
- Estoque baixo;
- Número de clientes;
- Relatórios.

### 🔐 Segurança avançada

Também podem ser implementados:

- CSRF Tokens;
- Rate limiting;
- Validação mais rigorosa de entradas;
- Logs de acesso;
- Recuperação de senha;
- Autenticação em dois fatores.

---

# 📸 Demonstração

> Recomenda-se adicionar aqui capturas de tela das principais páginas do sistema.

Sugestão:

```text
docs/
├── home.png
├── produtos.png
├── produto.png
├── carrinho.png
├── checkout.png
├── login.png
├── cadastro.png
├── montador.png
└── admin.png
```

Depois, elas podem ser apresentadas no README:

```markdown
## 🖥️ Interface

### Página inicial

![Página inicial](docs/home.png)

### Produtos

![Produtos](docs/produtos.png)

### Carrinho

![Carrinho](docs/carrinho.png)

### Painel administrativo

![Admin](docs/admin.png)
```

---

# 🧠 Aprendizados

O desenvolvimento da TechZone proporcionou a aplicação prática de conhecimentos relacionados a:

- Desenvolvimento de sistemas web;
- PHP;
- HTML;
- CSS;
- Banco de dados;
- SQL;
- CRUD;
- Sessões;
- Autenticação;
- Modelagem de dados;
- Relacionamentos;
- Organização de projetos;
- E-commerce;
- Controle de usuários;
- Administração de sistemas.

Além da implementação das funcionalidades, o projeto permitiu compreender como diferentes partes de uma aplicação web se conectam.

---

# 👥 Equipe

Projeto desenvolvido como **Projeto Final**.

### Desenvolvedor

**Cristantenicollas**

GitHub:

[![GitHub](https://img.shields.io/badge/GitHub-Cristantenicollas-181717?style=for-the-badge&logo=github)](https://github.com/Cristantenicollas)

---

# 📄 Licença

Este projeto foi desenvolvido para fins acadêmicos.

Caso seja definida uma licença específica para o projeto, esta seção poderá ser atualizada posteriormente.

---

# ⭐ Considerações finais

A **TechZone** representa a implementação de uma plataforma de e-commerce utilizando tecnologias fundamentais do desenvolvimento web.

O sistema reúne funcionalidades de cliente e administrador em uma única aplicação, trabalhando com autenticação, sessões, produtos, categorias, estoque, carrinho, pedidos e banco de dados.

A organização modular permite que o projeto seja expandido futuramente com novas funcionalidades, mantendo uma estrutura que facilita a manutenção e evolução do sistema.

---

<p align="center">
  Desenvolvido com 💻 e muita dedicação.
</p>

<p align="center">
  <strong>TechZone — Seu hardware, sua escolha.</strong>
</p>
