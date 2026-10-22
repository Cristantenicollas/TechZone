-- =============================================================================
-- TechZone — criação e carga inicial do banco
-- Execute este arquivo no MySQL para montar o banco do zero.
-- =============================================================================

CREATE DATABASE IF NOT EXISTS techzone
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE techzone;

-- ============================================================================
-- Limpeza (na ordem das dependências)
-- ============================================================================

DROP TABLE IF EXISTS itens_pedido;
DROP TABLE IF EXISTS pedidos;
DROP TABLE IF EXISTS produtos;
DROP TABLE IF EXISTS categorias;
DROP TABLE IF EXISTS usuarios;

-- ============================================================================
-- Tabelas
-- ============================================================================

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('cliente', 'admin') DEFAULT 'cliente',
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- tipo = 'peca' -> categoria de componentes | tipo = 'pc' -> PCs prontos
-- UNIQUE em nome evita categorias duplicadas (e permite INSERT IGNORE na migração)
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL UNIQUE,
    descricao TEXT,
    icone VARCHAR(16),
    tipo ENUM('peca', 'pc') DEFAULT 'peca'
);

-- icone    -> emoji exibido no card quando não há imagem real
-- destaque -> aparece na vitrine da página inicial
-- imagem   -> caminho da foto real (opcional, ainda não usada na versão atual)
-- UNIQUE em (nome, categoria_id) evita produtos repetidos na migração
CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categoria_id INT NOT NULL,
    nome VARCHAR(150) NOT NULL,
    descricao TEXT NOT NULL,
    preco DECIMAL(10,2) NOT NULL,
    estoque INT DEFAULT 0,
    icone VARCHAR(16),
    destaque TINYINT(1) DEFAULT 0,
    imagem VARCHAR(255),
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_produto (nome, categoria_id),
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE RESTRICT
);

CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    data_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(50) DEFAULT 'Pendente',
    total DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE itens_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    produto_id INT NOT NULL,
    quantidade INT NOT NULL,
    preco_unitario DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
    FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE RESTRICT
);

-- ============================================================================
-- Categorias
-- ============================================================================

INSERT INTO categorias (nome, descricao, icone, tipo) VALUES
    ('Processadores',   'CPUs para jogos, estudo e trabalho.',            '⚙️',  'peca'),
    ('Placas de vídeo', 'GPUs para desempenho gráfico em jogos e apps.',   '🎮',  'peca'),
    ('Placas-mãe',      'Placas base que conectam todos os componentes.', '🔗',  'peca'),
    ('Memórias',        'Memória RAM para multitarefa e jogos.',           '🧠',  'peca'),
    ('Armazenamento',   'SSDs e discos para seus arquivos.',               '💾',  'peca'),
    ('Fontes',          'Energia para alimentar seu setup com segurança.', '🔌',  'peca'),
    ('Coolers',         'Resfriamento para CPU e caixa.',                 '❄️',  'peca'),
    ('Gabinetes',       'Caixas para proteger e organizar o PC.',         '📦',  'peca'),
    ('Monitores',       'Telas para aproveitar seu setup.',               '🖥️', 'peca'),
    ('Periféricos',     'Mouse, teclado, fone e acessórios.',              '🎯',  'peca'),
    ('PCs Prontos',     'Configurações completas, prontas para usar.',     '🖥️', 'pc');

-- ============================================================================
-- Peças — 84 produtos
-- ============================================================================

INSERT INTO produtos (categoria_id, nome, descricao, preco, estoque, icone, destaque) VALUES
    -- Processadores ----------------------------------------------------------
    (1, 'AMD Ryzen 3 3200G',            'Quad-core com Radeon Vega integrado, entrada acessível.',             549.90, 18, '🧩', 0),
    (1, 'AMD Ryzen 5 5600',             'Hexa-core 3.9 GHz, ótimo custo-benefício para jogos em 1080p.',       899.90, 24, '🧩', 0),
    (1, 'AMD Ryzen 5 5600X',            'Hexa-core 4.6 GHz para quem quer mais quadros por segundo.',         1049.90, 16, '🧩', 0),
    (1, 'AMD Ryzen 7 5700X',            'Octa-core 3.8 GHz, ideal para jogos e criação de conteúdo.',         1299.90, 20, '🧩', 1),
    (1, 'AMD Ryzen 7 5800X',            'Octa-core 3.8 GHz com boost de 4.4 GHz.',                             1899.90, 12, '🧩', 0),
    (1, 'AMD Ryzen 9 5900X',            'Dodeca-core para edição de vídeo, streaming e uso pesado.',            2699.90, 8,  '🧩', 0),
    (1, 'Intel Core i5-12400F',          'Hexa-core LGA 1700, exige placa-mãe com chipset série 600.',               1099.90, 22, '🔵', 0),
    (1, 'Intel Core i5-13400F',          'Performance intermediária excelente para gaming e produtividade.',     1449.90, 15, '🔵', 0),
    (1, 'Intel Core i7-13700F',          'Octa-core com 16 threads, ideal para jogos e edição.',                   2499.90, 10, '🔵', 0),
    (1, 'Intel Core i9-14900K',          '24 núcleos para estações de trabalho e quem quer fazer overclock.',        4799.90, 5,  '🔵', 0),

    -- Placas de vídeo --------------------------------------------------------
    (2, 'NVIDIA GeForce GT 1030',        '2 GB GDDR5, compacta e econômica para uso geral.',                    599.90, 20, '🎮', 0),
    (2, 'NVIDIA GeForce GTX 1650',       '4 GB, boa escolha para esports e jogos leves em 1080p.',                1249.90, 18, '🎮', 0),
    (2, 'NVIDIA GeForce GTX 1660 Super', '6 GB para jogos em 1440p com taxa de quadros alta.',                     2199.90, 14, '🎮', 0),
    (2, 'NVIDIA GeForce RTX 3050',       '8 GB com Ray Tracing de 2ª geração.',                                    2499.90, 16, '🎮', 0),
    (2, 'NVIDIA GeForce RTX 3060',       '12 GB, ótima para 1440p e ray tracing.',                                 3199.90, 15, '🎮', 1),
    (2, 'NVIDIA GeForce RTX 3060 Ti',    '8 GDDR6 com desempenho superior ao 3060.',                               3499.90, 12, '🎮', 0),
    (2, 'NVIDIA GeForce RTX 4060',       '8 GB, ray tracing e DLSS 3 para jogos em 1440p.',                           2899.90, 17, '🎮', 0),
    (2, 'NVIDIA GeForce RTX 4060 Ti',    '8 GB com clock mais alto que a 4060 padrão.',                            3699.90, 11, '🎮', 0),
    (2, 'NVIDIA GeForce RTX 4070',       '12 GB com DLSS 3, Ideal para 1440p em altíssima qualidade.',             5499.90, 9,  '🎮', 0),
    (2, 'NVIDIA GeForce RTX 4080',       '16 GB com DLSS 3, referência em 4K.',                                    8499.90, 6,  '🎮', 1),
    (2, 'AMD Radeon RX 580 8GB',         '8 GB para jogos em 1080p com boa faixa de preço.',                        899.90, 19, '🎮', 0),
    (2, 'AMD Radeon RX 6600',            '8 GB com bom desempenho em 1080p e 1440p.',                               1899.90, 13, '🎮', 0),
    (2, 'AMD Radeon RX 7600',            '8 GB para 1440p com ray tracing.',                                        2299.90, 15, '🎮', 1),

    -- Placas-mãe -------------------------------------------------------------
    (3, 'Gigabyte B450M DS3H V2',        'Micro-ATX AM4, até 64 GB DDR4 e 2 slots M.2.',                           549.90, 16, '🔗', 0),
    (3, 'ASUS Prime B550M-K',           'Micro-ATX AM4 com 2 slots M.2 e VRM robusto.',                            749.90, 18, '🔗', 0),
    (3, 'MSI PRO B550M-P',              'Micro-ATX AM4 com header RGB e.Debug USB.',                              829.90, 13, '🔗', 0),
    (3, 'ASUS TUF B550M-Plus',          'Micro-ATX AM4,Wifi integrado e dissipateis robusto.',                      1099.90, 11, '🔗', 0),
    (3, 'Gigabyte B760M Aorus Elite',    'Micro-ATX LGA 1700 para(processadores Intel de 12ª e 13ª geração).',    1199.90, 10, '🔗', 0),
    (3, 'ASUS TUF Z690-PLUS',           'ATX LGA 1700 com 4 slots M.2 e soporte a USB 3.2 Gen 2.',                 1699.90, 8,  '🔗', 0),
    (3, 'MSI MAG Z790 Tomahawk',         'ATX LGA 1700 com VRM de 16 fases e rede 2.5G.',                            2199.90, 6,  '🔗', 0),

    -- Memórias ---------------------------------------------------------------
    (4, 'DDR4 4GB 2400MHz',              'Módulo único básico para uso administrativo.',                             89.90,  40, '🧠', 0),
    (4, 'DDR4 8GB 3200MHz',              'Módulo único DDR4, upgrade simples.',                                       149.90, 35, '🧠', 0),
    (4, 'DDR4 16GB (2x8GB) 3200MHz',     'Kit dual channel para jogos.',                                              289.90, 30, '🧠', 0),
    (4, 'DDR4 32GB (2x16GB) 3200MHz',    'Kit 32 GB dual channel para uso geral e edição.',                         549.90, 22, '🧠', 0),
    (4, 'DDR4 16GB 3600MHz RGB',         'Kit com iluminação RGB e latência baixa.',                                 379.90, 16, '🧠', 0),
    (4, 'DDR5 16GB (2x8GB) 5600MHz',     'Kit DDR5 de nova geração para placas LGA 1700.',                           429.90, 20, '🧠', 0),
    (4, 'DDR5 32GB (2x16GB) 6000MHz',    'Kit DDR5 32 GB para workstations.',                                        799.90, 14, '🧠', 0),
    (4, 'DDR5 64GB (2x32GB) 5600MHz',    'Kit DDR5 64 GB para edição 4K e máquinas virtuais.',                      1599.90, 7,  '🧠', 0),

    -- Armazenamento ----------------------------------------------------------
    (5, 'SSD SATA 240GB',                'Entrada para armazenamento rápido em 2.5".',                               189.90, 32, '💾', 0),
    (5, 'SSD SATA 480GB',                'Armazenamento intermediário para SO e aplicativos.',                     299.90, 28, '💾', 0),
    (5, 'SSD NVMe 500GB Gen3',           'NVMe PCIe 3.0, leitura de até 2000 MB/s.',                                 379.90, 26, '⚡', 0),
    (5, 'SSD NVMe 1TB Gen4',             'NVMe PCIe 4.0, leitura de até 7000 MB/s.',                                 599.90, 24, '⚡', 1),
    (5, 'SSD NVMe 2TB Gen4',             'NVMe PCIe 4.0 de alta capacidade.',                                       1099.90, 15, '⚡', 0),
    (5, 'HD 1TB 7200 RPM',               'Disco mecânico de 1 TB para arquivar e backup.',                           349.90, 20, '💾', 0),
    (5, 'HD 2TB 7200 RPM',               'Disco mecânico de 2 TB, bom para mídia e backups.',                         599.90, 18, '💾', 0),
    (5, 'HD 4TB NAS',                    'Disco de 4 TB para NAS e armazenamento em rede.',                         999.90, 10, '💾', 0),

    -- Fontes -----------------------------------------------------------------
    (6, 'Fonte 400W 80 Plus Bronze',     '400 W reais para setups básicos.',                                        249.90, 25, '🔌', 0),
    (6, 'Fonte 500W 80 Plus Bronze',     '500 W para entrada com placa de vídeo dedicada.',                    299.90, 30, '🔌', 0),
    (6, 'Fonte 550W 80 Plus Bronze',     '550 W: par ideal para Ryzen 5 ou Core i5.',                              349.90, 22, '🔌', 0),
    (6, 'Fonte 650W 80 Plus Gold',       '650 W Gold, recomendada para placas de vídeo de até RTX 3060.',            549.90, 19, '⚡', 0),
    (6, 'Fonte 750W 80 Plus Gold',       '750 W Gold para setups com GPU de alta performance.',                       699.90, 15, '⚡', 1),
    (6, 'Fonte 850W 80 Plus Gold',       '850 W Gold para RTX 4070 e processadores de 8 núcleos ou mais.',            899.90, 12, '⚡', 0),
    (6, 'Fonte 1000W 80 Plus Platinum',  '1000 W Platinum para estações de trabalho com RTX 4080.',                   1599.90, 6,  '⚡', 0),

    -- Coolers ----------------------------------------------------------------
    (7, 'Cooler box Intel LGA1700',      'Cooler de fábrica que acompanha CPUs Intel selecionadas.',                  89.90,  25, '❄️', 0),
    (7, 'AMD Wraith Stealth',            'Cooler de fábrica AMD, baixo perfil.',                                     99.90,  22, '❄️', 0),
    (7, 'Cooler Air 120mm (AK400)',      'Torre compacta com um ventoinhador de 120 mm.',                             199.90, 20, '❄️', 0),
    (7, 'Cooler Air 140mm (Arctic P14)', 'Torre com ventoinhador de 140 mm e cinco heatpipes.',                      289.90, 16, '❄️', 0),
    (7, 'Noctua NH-D15',                 'Torre de duplo fan, referência em cooler air silencioso.',                     749.90, 8,  '❄️', 0),
    (7, 'Water cooler 240mm AIO',        'Liquid cooling de 240 mm, reduz ruído e temperatura.',                       549.90, 12, '❄️', 0),
    (7, 'Water cooler 360mm AIO',        'Liquid cooling de 360 mm para setups de alta performance.',                    899.90, 8,  '❄️', 0),

    -- Gabinetes --------------------------------------------------------------
    (8, 'Gabinete Micro ATX frontal',    'Compacto frontal, com uma ventoinhadora de 120 mm incluída.',               219.90, 22, '📦', 0),
    (8, 'Gabinete Mid Tower Q300L',      'Mid tower com painel em tela e frontal em malha.',                          379.90, 20, '📦', 0),
    (8, 'Gabinete Mid Tower vidro 200D', 'Mid tower com lateral em vidro temperado.',                                 449.90, 18, '📦', 0),
    (8, 'Gabinete ATX gamer H510',       'Mid tower ATX com frente em malha e RGB.',                                  549.90, 16, '📦', 0),
    (8, 'Gabinete ATX airflow R5',       'Mid tower com lateral em tela e 3 ventoinhadores frontais.',                 749.90, 12, '📦', 0),
    (8, 'Gabinete Full Tower 4000D',     'Full tower ATX com painel frontal em tela.',                                 999.90, 9,  '📦', 0),
    (8, 'Gabinete ATX premium',          'Full tower com acabamento premium e gestão de cabos.',                     1299.90, 6,  '📦', 0),

    -- Monitores --------------------------------------------------------------
    (9, 'Monitor 19" LED 75Hz',          'Tela Full HD básica para trabalho e estudo.',                              549.90, 20, '🖥️', 0),
    (9, 'Monitor 21.5" LED VA 75Hz',     'Painel VA com contraste alto e bordas finas.',                              699.90, 18, '🖥️', 0),
    (9, 'Monitor 24" IPS 75Hz',          'Painel IPS Full HD, bom ângulo de visão.',                                 899.90, 24, '🖥️', 0),
    (9, 'Monitor 24" IPS 144Hz',         'Full HD 144 Hz para jogos competitivos.',                                  1299.90, 17, '🖥️', 0),
    (9, 'Monitor 27" IPS 75Hz',          'QHD em 75 Hz, ótimo para trabalho e estudo.',                             1199.90, 15, '🖥️', 0),
    (9, 'Monitor 27" VA 144Hz Curvo',    'QHD curvo 144 Hz para imersão em jogos.',                                   1699.90, 13, '🖥️', 0),
    (9, 'Monitor 27" IPS 165Hz QHD',     'QHD 165 Hz com 1 ms de tempo de resposta.',                               2499.90, 10, '🖥️', 1),
    (9, 'Monitor 32" QHD 144Hz',         'QHD 144 Hz de 32", mais espaço para trabalho e jogo.',                   3299.90, 8,  '🖥️', 0),
    (9, 'Monitor 34" Ultrawide 144Hz',    'Ultrawide 3440x1440 144 Hz para setup imersivo.',                          4299.90, 5,  '🖥️', 0),

    -- Periféricos ------------------------------------------------------------
    (10, 'Mouse gamer 8000 DPI',          'Sensor de 8000 DPI com RGB.',                                             149.90, 30, '🎯', 0),
    (10, 'Mouse gamer RGB 16000 DPI',     'Sensor de 16000 DPI e switches ópticos.',                                  249.90, 24, '🎯', 0),
    (10, 'Teclado mecânico compact',      'Mecânico 65% com switches lineares.',                                       399.90, 20, '⌨️', 0),
    (10, 'Teclado mecânico full RGB',     'Mecânico completo com iluminação por tecla.',                               699.90, 15, '⌨️', 0),
    (10, 'Headset gamer 7.1',             'Som surround virtual com microfone removível.',                             279.90, 26, '🎧', 0),
    (10, 'Webcam Full HD',                'Câmera 1080p para reuniões e streaming.',                                  249.90, 22, '📷', 0),
    (10, 'Caixa de som 2.1',              'Kit de duas caixas e subwoofer para música e jogos.',                        199.90, 18, '🔊', 0),
    (10, 'Controle sem fio Xbox',         'Controle compatível com PC e console.',                                    349.90, 21, '🕹️', 0);

-- ============================================================================
-- PCs prontos — 13 configurações completas e compráveis
-- (os 4 com destaque = 1 são os que aparecem na vitrine da página inicial)
-- ============================================================================

INSERT INTO produtos (categoria_id, nome, descricao, preco, estoque, icone, destaque) VALUES
    (11, 'PC Escritório Básico',      'Intel Core i3 + 8 GB DDR4 + SSD 240 GB. Para navegação, estudo e planilhas.',                      1899.90, 10, '🖥️', 0),
    (11, 'PC Familiar',               'Ryzen 5 5600 + GT 1030 + 16 GB DDR4 + SSD 480 GB. Uso geral em casa.',                          3499.90, 9,  '🏠', 0),
    (11, 'PC Home Theater',           'Ryzen 5 5600 + RTX 3050 + 16 GB DDR4 + SSD 480 GB. Filmes e jogos leves.',                      4299.90, 8,  '📺', 0),
    (11, 'PC Gamer Start',            'Ryzen 5 5600 + GTX 1650 + 16 GB DDR4 + SSD NVMe 1 TB. Entrada no mundo gamer.',               4499.90, 12, '🎮', 1),
    (11, 'PC Estudo',                 'Ryzen 5 5600 + RX 580 + 16 GB DDR4 + SSD 1 TB. Equilíbrio entre estudo e lazer.',        4899.90, 8,  '📚', 0),
    (11, 'PC Gamer RX 7600',          'Ryzen 7 5700X + RX 7600 + 16 GB DDR4 + SSD NVMe 1 TB. 1440p com taxa alta.',            6299.90, 10, '🎮', 1),
    (11, 'PC Compacto SFF',           'Intel Core i5-12400F + 16 GB DDR4 + SSD 512 GB em gabinete compacto.',                      3799.90, 7,  '📦', 0),
    (11, 'PC Creator de Vídeo',       'Intel Core i5-13400F + RTX 3060 + 32 GB DDR4 + SSD 1 TB. Edição e montagem de vídeo.',         7999.90, 6,  '🎬', 0),
    (11, 'PC Gamer Pro',              'Ryzen 7 5800X + RTX 3060 + 32 GB DDR4 + SSD NVMe 1 TB. 1440p fluido.',                 8499.90, 9,  '⚡', 1),
    (11, 'PC Streaming',              'Intel Core i5-13400F + RTX 3060 + 32 GB DDR4 + SSD 1 TB. Transmissão de alta qualidade.', 8999.90, 5, '📡', 0),
    (11, 'PC Gamer Extreme',          'Ryzen 9 5900X + RTX 4070 + 32 GB DDR4 + SSD NVMe 2 TB. 1440p no máximo.',               13999.90, 4,  '🔥', 1),
    (11, 'PC Creator 3D',             'Ryzen 9 5900X + RTX 4070 + 64 GB DDR5 + SSD 2 TB. Modelagem 3D e renderização.',        16999.90, 3,  '🎨', 0),
    (11, 'PC Workstation',            'Intel Core i9-14900K + RTX 4080 + 64 GB DDR5 + SSD 2 TB. Uso profissional pesado.',       22999.90, 2,  '🏗️', 0);

-- ============================================================================
-- Usuários
-- ============================================================================

-- Administrador
-- E-mail: admin@techzone.com
-- Senha:  admin123
-- Troque a senha antes de usar em produção.
INSERT INTO usuarios (nome, email, senha, tipo) VALUES
    ('Administrador', 'admin@techzone.com',
     '$2y$12$FmLBnm3gYU38rwL71jKx6OjeXBOUzjNdqDz8dHyHKDc7xv/dKfv6q',
     'admin');

-- Cliente de teste
-- E-mail: cliente@techzone.com
-- Senha:  cliente123
INSERT INTO usuarios (nome, email, senha, tipo) VALUES
    ('Cliente Teste', 'cliente@techzone.com',
     '$2y$12$G9Tgg0X6YSZ4xa3pWLxsteBsQC5izIFMw1U/IBtbRS6XbJn/jDqU6',
     'cliente');
