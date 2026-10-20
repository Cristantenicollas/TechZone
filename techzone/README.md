# TechZone

Projeto de loja de tecnologia usando **PHP + PostgreSQL + HTML + CSS**, sem JavaScript.

## Estrutura do projeto

```text
techzone/
├── admin/           # Painel administrativo (CRUD e relatórios)
│   ├── categorias.php
│   ├── painel.php
│   ├── pedidos.php
│   ├── produtos.php
│   └── usuarios.php
├── assets/
│   └── images/      # Imagens do site (.gitkeep)
├── database/
│   ├── techzone.sql # Schema + catálogo no dialeto MySQL (fonte dos dados)
│   └── migrar-para-postgres.php # Recria as tabelas no PostgreSQL e copia as linhas
├── includes/
│   ├── config.php    # Configurações (BASE_URL, banco de dados)
│   ├── conexao.php   # Conexão com PostgreSQL (camada compatível com mysqli)
│   ├── functions.php # Funções auxiliares, sessão, auth e helpers
│   ├── header.php    # Cabeçalho reutilizável (carrega functions.php)
│   └── footer.php    # Rodapé reutilizável
├── login/
│   ├── cadastro.php  # Cadastro de usuários
│   ├── login.php     # Login de usuários/admin
│   └── logout.php    # Encerramento de sessão
├── paginas/
│   ├── carrinho.php  # Carrinho de compras
│   ├── categorias.php# Listagem de categorias
│   ├── checkout.php  # Finalização de pedido
│   ├── monte_pc.php  # Montador de PC por componentes
│   ├── pcs_prontos.php# Todos os PCs prontos à venda
│   ├── pedidos.php   # Meus pedidos (usuário logado)
│   ├── produto.php   # Detalhes de um produto
│   └── produtos.php  # Catálogo com busca/filtro por categoria
├── style/
│   └── style.css     # Estilos da aplicação
└── index.php         # Página inicial
```

## Banco de dados

Banco: PostgreSQL `techzone` em `192.168.10.37:5432`. Host, porta e credenciais
ficam em `includes/config.php`.

**Para rodar local sem Apache:** dê dois cliques em `iniciar-servidor.cmd`. Ele
sobe o servidor embutido do PHP e abre o projeto em
`http://localhost:8080/techzone/`. `Ctrl+C` na janela encerra.

> O catálogo inteiro vem do PostgreSQL. Sem acesso ao servidor do banco, todas as
> páginas mostram "Banco de dados indisponível" — inclusive a home. Não há
> versão estática com os dados.

**Recriar as tabelas e copiar os dados:** o script
`database/migrar-para-postgres.php` cria as 5 tabelas no PostgreSQL e copia as
linhas do MySQL local (XAMPP com `database/techzone.sql` importado):

```text
php database/migrar-para-postgres.php
```

O script apaga e recria as tabelas — rode-o só quando quiser sobrescrever o
conteúdo do PostgreSQL.

O catálogo que os dois arquivos entregam:

| | |
|---|---|
| Categorias | 11 (10 de peças + 1 de PCs prontos) |
| Peças | 84 |
| PCs prontos | 13 |
| PCs em destaque na home | 4 |

A diferenciação entre peça e PC não é uma coluna em `produtos`: quem decide é
`categorias.tipo`, com `peca` ou `pc`. As consultas da vitrine sempre filtram por
isso.

Duas constraints evitam catálogo duplicado:

- `categorias.nome` é `UNIQUE`
- `produtos` tem `UNIQUE KEY uniq_produto (nome, categoria_id)`

O mesmo nome pode existir em categorias diferentes, mas não se repetir dentro da
mesma categoria.

## Usuários de demonstração

```text
Admin:   admin@techzone.com   / admin123
Cliente: cliente@techzone.com / cliente123
```

As senhas são hashes bcrypt. **Troque as duas antes de usar em produção** —
`admin123` em especial está documentado aqui e em qualquer tutorial que use
este projeto.

## URL

Por padrão o projeto está configurado para rodar em `http://localhost/techzone/`.

Caso publique em outro caminho (subpasta ou domínio raiz), ajuste `BASE_URL` no arquivo `includes/config.php`.

## Funcionalidades

- Vitrine na página inicial com 4 PCs em destaque e 8 peças em estoque.
- Página `pcs_prontos.php` com todos os 13 PCs prontos.
- Catálogo de 84 componentes com busca e filtro por categoria.
- Categorias separadas por `tipo` (`peca` / `pc`) e organizadoras no menu.
- Página de produto com detalhes e botão para carrinho.
- Login e cadastro de clientes.
- Controle de sessão e permissões (cliente/admin).
- Carrinho de compras (sessão), com quantidade limitada ao estoque.
- Checkout que revalida preço e estoque dentro de uma transação e dá baixa no
  estoque. Se o estoque acabar entre a montagem do carrinho e a confirmação, o
  pedido é barrado e nada é gravado.
- Monte seu PC com peças separadas por categoria.
- Painel administrativo com resumo.
- CRUD de produtos e categorias, pelo próprio painel.
- Consulta de usuários e pedidos.
- Header e footer reutilizados em todas as páginas.
- Sem JavaScript.
- Código organizado, padronizado e com caminhos centralizados via `BASE_URL`.

## Convenções adotadas

- **Caminhos**: use a função `url($caminho)` para montar links internos (evita hardcodes).
- **Saída HTML**: use `e($valor)` para escapar dados dinâmicos.
- **Preços**: use `preco($valor)` para formatar em BRL.
- **Autenticação**: `exigirLogin()` e `exigirAdmin()` em páginas protegidas.
- **Sessão**: iniciada centralmente em `includes/functions.php`. Não é necessário chamar `session_start()` nos arquivos de páginas.
- **Conexão**: configurada em `includes/config.php` + `includes/conexao.php`.
  A conexão usa `pgsql`, com uma camada compatível com a API do `mysqli`
  (`query`, `prepare`, `bind_param`, `get_result`...), então o código das páginas
  não muda.
- **Redirecionamentos**: utilize `redirecionar($caminho)` (interno usa `url()`).
- **Consultas**: sempre `prepare` + `bind_param`, com os tipos na ordem dos `?`
  (`i` int, `d` double, `s` string). `prepare()` devolve um statement, não um
  result: para ler linhas use `get_result()` e depois `fetch_assoc()`.
- **Categorias**: não filtre produtos só por `destaque`; junte com `categorias`
  e filtre por `c.tipo`, senão peças marcadas aparecem como PC na vitrine.
- **Estoque**: quem limita a quantidade é `adicionarAoCarrinho()` (na inserção) e
  a releitura em `paginas/checkout.php` (na confirmação). Alterar quantidade
  direto na página do carrinho também respeita o estoque.
