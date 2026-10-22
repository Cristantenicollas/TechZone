--
-- PostgreSQL database dump
--

\restrict eEUNNejFD1Q2EBZVyJYjOzwl5uuhQyRSIzrzkPaI9J2Dm72c9qMwva9SCRIyBFm

-- Dumped from database version 18.6 (Ubuntu 18.6-0ubuntu0.26.04.1)
-- Dumped by pg_dump version 18.6 (Ubuntu 18.6-0ubuntu0.26.04.1)

-- Started on 2026-10-22 10:07:15 UTC

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 222 (class 1259 OID 32974)
-- Name: categorias; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.categorias (
    id integer NOT NULL,
    nome character varying(100) NOT NULL,
    descricao text,
    icone character varying(16),
    tipo character varying(10) DEFAULT 'peca'::character varying NOT NULL
);


ALTER TABLE public.categorias OWNER TO postgres;

--
-- TOC entry 221 (class 1259 OID 32973)
-- Name: categorias_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.categorias_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.categorias_id_seq OWNER TO postgres;

--
-- TOC entry 3539 (class 0 OID 0)
-- Dependencies: 221
-- Name: categorias_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.categorias_id_seq OWNED BY public.categorias.id;


--
-- TOC entry 228 (class 1259 OID 33035)
-- Name: itens_pedido; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.itens_pedido (
    id integer NOT NULL,
    pedido_id integer NOT NULL,
    produto_id integer NOT NULL,
    quantidade integer NOT NULL,
    preco_unitario numeric(10,2) NOT NULL
);


ALTER TABLE public.itens_pedido OWNER TO postgres;

--
-- TOC entry 227 (class 1259 OID 33034)
-- Name: itens_pedido_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.itens_pedido_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.itens_pedido_id_seq OWNER TO postgres;

--
-- TOC entry 3540 (class 0 OID 0)
-- Dependencies: 227
-- Name: itens_pedido_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.itens_pedido_id_seq OWNED BY public.itens_pedido.id;


--
-- TOC entry 226 (class 1259 OID 33016)
-- Name: pedidos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.pedidos (
    id integer NOT NULL,
    usuario_id integer NOT NULL,
    data_pedido timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    status character varying(50) DEFAULT 'Pendente'::character varying NOT NULL,
    total numeric(10,2) NOT NULL
);


ALTER TABLE public.pedidos OWNER TO postgres;

--
-- TOC entry 225 (class 1259 OID 33015)
-- Name: pedidos_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.pedidos_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.pedidos_id_seq OWNER TO postgres;

--
-- TOC entry 3541 (class 0 OID 0)
-- Dependencies: 225
-- Name: pedidos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.pedidos_id_seq OWNED BY public.pedidos.id;


--
-- TOC entry 224 (class 1259 OID 32989)
-- Name: produtos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.produtos (
    id integer NOT NULL,
    categoria_id integer NOT NULL,
    nome character varying(150) NOT NULL,
    descricao text NOT NULL,
    preco numeric(10,2) NOT NULL,
    estoque integer DEFAULT 0 NOT NULL,
    icone character varying(16),
    destaque smallint DEFAULT 0 NOT NULL,
    imagem character varying(255),
    data_cadastro timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.produtos OWNER TO postgres;

--
-- TOC entry 223 (class 1259 OID 32988)
-- Name: produtos_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.produtos_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.produtos_id_seq OWNER TO postgres;

--
-- TOC entry 3542 (class 0 OID 0)
-- Dependencies: 223
-- Name: produtos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.produtos_id_seq OWNED BY public.produtos.id;


--
-- TOC entry 220 (class 1259 OID 32955)
-- Name: usuarios; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.usuarios (
    id integer NOT NULL,
    nome character varying(100) NOT NULL,
    email character varying(150) NOT NULL,
    senha character varying(255) NOT NULL,
    tipo character varying(10) DEFAULT 'cliente'::character varying NOT NULL,
    data_cadastro timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.usuarios OWNER TO postgres;

--
-- TOC entry 219 (class 1259 OID 32954)
-- Name: usuarios_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.usuarios_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.usuarios_id_seq OWNER TO postgres;

--
-- TOC entry 3543 (class 0 OID 0)
-- Dependencies: 219
-- Name: usuarios_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.usuarios_id_seq OWNED BY public.usuarios.id;


--
-- TOC entry 3347 (class 2604 OID 32977)
-- Name: categorias id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categorias ALTER COLUMN id SET DEFAULT nextval('public.categorias_id_seq'::regclass);


--
-- TOC entry 3356 (class 2604 OID 33038)
-- Name: itens_pedido id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.itens_pedido ALTER COLUMN id SET DEFAULT nextval('public.itens_pedido_id_seq'::regclass);


--
-- TOC entry 3353 (class 2604 OID 33019)
-- Name: pedidos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pedidos ALTER COLUMN id SET DEFAULT nextval('public.pedidos_id_seq'::regclass);


--
-- TOC entry 3349 (class 2604 OID 32992)
-- Name: produtos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.produtos ALTER COLUMN id SET DEFAULT nextval('public.produtos_id_seq'::regclass);


--
-- TOC entry 3344 (class 2604 OID 32958)
-- Name: usuarios id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarios ALTER COLUMN id SET DEFAULT nextval('public.usuarios_id_seq'::regclass);


--
-- TOC entry 3527 (class 0 OID 32974)
-- Dependencies: 222
-- Data for Name: categorias; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.categorias (id, nome, descricao, icone, tipo) FROM stdin;
7	Coolers	Resfriamento para CPU e caixa.		peca
9	Monitores	Telas para aproveitar seu setup.		peca
1	Processadores	CPUs para jogos, estudo e trabalho.		peca
8	Gabinetes	Caixas para proteger e organizar o PC.		peca
2	Placas de vídeo	GPUs para desempenho gráfico em jogos e apps.		peca
3	Placas-mãe	Placas base que conectam todos os componentes.		peca
4	Memórias	Memória RAM para multitarefa e jogos.		peca
5	Armazenamento	SSDs e discos para seus arquivos.		peca
6	Fontes	Energia para alimentar seu setup com segurança.		peca
10	Periféricos	Mouse, teclado, fone e acessórios.		peca
11	PCs Prontos	Configurações completas, prontas para usar.		pc
\.


--
-- TOC entry 3533 (class 0 OID 33035)
-- Dependencies: 228
-- Data for Name: itens_pedido; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.itens_pedido (id, pedido_id, produto_id, quantidade, preco_unitario) FROM stdin;
2	2	88	1	4499.90
3	3	42	1	599.90
4	3	55	1	99.90
5	3	50	1	549.90
6	3	64	1	549.90
7	3	38	1	1599.90
8	3	76	1	4299.90
9	3	80	1	699.90
10	3	20	1	8499.90
11	3	30	1	2199.90
12	3	10	1	4799.90
\.


--
-- TOC entry 3531 (class 0 OID 33016)
-- Dependencies: 226
-- Data for Name: pedidos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.pedidos (id, usuario_id, data_pedido, status, total) FROM stdin;
2	1	2026-10-08 15:19:26.213903	Pendente	4499.90
3	4	2026-10-20 14:59:46.697078	Pendente	23899.00
\.


--
-- TOC entry 3529 (class 0 OID 32989)
-- Dependencies: 224
-- Data for Name: produtos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.produtos (id, categoria_id, nome, descricao, preco, estoque, icone, destaque, imagem, data_cadastro) FROM stdin;
24	3	Gigabyte B450M DS3H V2	Micro-ATX AM4, até 64 GB DDR4 e 2 slots M.2.	549.90	16		0	assets/images/categorias/placas-mae.svg	2026-10-06 14:10:30
31	4	DDR4 4GB 2400MHz	Módulo único básico para uso administrativo.	89.90	40		0	assets/images/categorias/memorias.svg	2026-10-06 14:10:30
16	2	NVIDIA GeForce RTX 3060 Ti	8 GDDR6 com desempenho superior ao 3060.	3499.90	12		0	assets/images/categorias/placas-de-video.svg	2026-10-06 14:10:30
17	2	NVIDIA GeForce RTX 4060	8 GB, ray tracing e DLSS 3 para jogos em 1440p.	2899.90	17		0	assets/images/categorias/placas-de-video.svg	2026-10-06 14:10:30
25	3	ASUS Prime B550M-K	Micro-ATX AM4 com 2 slots M.2 e VRM robusto.	749.90	18		0	assets/images/categorias/placas-mae.svg	2026-10-06 14:10:30
36	4	DDR5 16GB (2x8GB) 5600MHz	Kit DDR5 de nova geração para placas LGA 1700.	429.90	20		0	assets/images/categorias/memorias.svg	2026-10-06 14:10:30
39	5	SSD SATA 240GB	Entrada para armazenamento rápido em 2.5".	189.90	32		0	assets/images/categorias/armazenamento.svg	2026-10-06 14:10:30
40	5	SSD SATA 480GB	Armazenamento intermediário para SO e aplicativos.	299.90	28		0	assets/images/categorias/armazenamento.svg	2026-10-06 14:10:30
41	5	SSD NVMe 500GB Gen3	NVMe PCIe 3.0, leitura de até 2000 MB/s.	379.90	26		0	assets/images/categorias/armazenamento.svg	2026-10-06 14:10:30
44	5	HD 1TB 7200 RPM	Disco mecânico de 1 TB para arquivar e backup.	349.90	20		0	assets/images/categorias/armazenamento.svg	2026-10-06 14:10:30
59	7	Water cooler 240mm AIO	Liquid cooling de 240 mm, reduz ruído e temperatura.	549.90	12		0	assets/images/categorias/coolers.svg	2026-10-06 14:10:30
61	8	Gabinete Micro ATX frontal	Compacto frontal, com uma ventoinhadora de 120 mm incluída.	219.90	22		0	assets/images/categorias/gabinetes.svg	2026-10-06 14:10:30
67	8	Gabinete ATX premium	Full tower com acabamento premium e gestão de cabos.	1299.90	6		0	assets/images/categorias/gabinetes.svg	2026-10-06 14:10:30
68	9	Monitor 19" LED 75Hz	Tela Full HD básica para trabalho e estudo.	549.90	20		0	assets/images/categorias/monitores.svg	2026-10-06 14:10:30
69	9	Monitor 21.5" LED VA 75Hz	Painel VA com contraste alto e bordas finas.	699.90	18		0	assets/images/categorias/monitores.svg	2026-10-06 14:10:30
70	9	Monitor 24" IPS 75Hz	Painel IPS Full HD, bom ângulo de visão.	899.90	24		0	assets/images/categorias/monitores.svg	2026-10-06 14:10:30
71	9	Monitor 24" IPS 144Hz	Full HD 144 Hz para jogos competitivos.	1299.90	17		0	assets/images/categorias/monitores.svg	2026-10-06 14:10:30
72	9	Monitor 27" IPS 75Hz	QHD em 75 Hz, ótimo para trabalho e estudo.	1199.90	15		0	assets/images/categorias/monitores.svg	2026-10-06 14:10:30
73	9	Monitor 27" VA 144Hz Curvo	QHD curvo 144 Hz para imersão em jogos.	1699.90	13		0	assets/images/categorias/monitores.svg	2026-10-06 14:10:30
74	9	Monitor 27" IPS 165Hz QHD	QHD 165 Hz com 1 ms de tempo de resposta.	2499.90	10		1	assets/images/categorias/monitores.svg	2026-10-06 14:10:30
75	9	Monitor 32" QHD 144Hz	QHD 144 Hz de 32", mais espaço para trabalho e jogo.	3299.90	8		0	assets/images/categorias/monitores.svg	2026-10-06 14:10:30
78	10	Mouse gamer RGB 16000 DPI	Sensor de 16000 DPI e switches ópticos.	249.90	24		0	assets/images/categorias/perifericos.svg	2026-10-06 14:10:30
79	10	Teclado mecânico compact	Mecânico 65% com switches lineares.	399.90	20		0	assets/images/categorias/perifericos.svg	2026-10-06 14:10:30
81	10	Headset gamer 7.1	Som surround virtual com microfone removível.	279.90	26		0	assets/images/categorias/perifericos.svg	2026-10-06 14:10:30
82	10	Webcam Full HD	Câmera 1080p para reuniões e streaming.	249.90	22		0	assets/images/categorias/perifericos.svg	2026-10-06 14:10:30
83	10	Caixa de som 2.1	Kit de duas caixas e subwoofer para música e jogos.	199.90	18		0	assets/images/categorias/perifericos.svg	2026-10-06 14:10:30
84	10	Controle sem fio Xbox	Controle compatível com PC e console.	349.90	21		0	assets/images/categorias/perifericos.svg	2026-10-06 14:10:30
85	11	PC Escritório Básico	Intel Core i3 + 8 GB DDR4 + SSD 240 GB. Para navegação, estudo e planilhas.	1899.90	10		0	assets/images/categorias/pcs.svg	2026-10-06 14:10:30
86	11	PC Familiar	Ryzen 5 5600 + GT 1030 + 16 GB DDR4 + SSD 480 GB. Uso geral em casa.	3499.90	9		0	assets/images/categorias/pcs.svg	2026-10-06 14:10:30
87	11	PC Home Theater	Ryzen 5 5600 + RTX 3050 + 16 GB DDR4 + SSD 480 GB. Filmes e jogos leves.	4299.90	8		0	assets/images/categorias/pcs.svg	2026-10-06 14:10:30
88	11	PC Gamer Start	Ryzen 5 5600 + GTX 1650 + 16 GB DDR4 + SSD NVMe 1 TB. Entrada no mundo gamer.	4499.90	11		1	assets/images/categorias/pcs.svg	2026-10-06 14:10:30
89	11	PC Estudo	Ryzen 5 5600 + RX 580 + 16 GB DDR4 + SSD 1 TB. Equilíbrio entre estudo e lazer.	4899.90	8		0	assets/images/categorias/pcs.svg	2026-10-06 14:10:30
90	11	PC Gamer RX 7600	Ryzen 7 5700X + RX 7600 + 16 GB DDR4 + SSD NVMe 1 TB. 1440p com taxa alta.	6299.90	10		1	assets/images/categorias/pcs.svg	2026-10-06 14:10:30
91	11	PC Compacto SFF	Intel Core i5-12400F + 16 GB DDR4 + SSD 512 GB em gabinete compacto.	3799.90	7		0	assets/images/categorias/pcs.svg	2026-10-06 14:10:30
42	5	SSD NVMe 1TB Gen4	NVMe PCIe 4.0, leitura de até 7000 MB/s.	599.90	23		1	assets/images/categorias/armazenamento.svg	2026-10-06 14:10:30
38	4	DDR5 64GB (2x32GB) 5600MHz	Kit DDR5 64 GB para edição 4K e máquinas virtuais.	1599.90	6		0	assets/images/categorias/memorias.svg	2026-10-06 14:10:30
76	9	Monitor 34" Ultrawide 144Hz	Ultrawide 3440x1440 144 Hz para setup imersivo.	4299.90	4		0	assets/images/categorias/monitores.svg	2026-10-06 14:10:30
80	10	Teclado mecânico full RGB	Mecânico completo com iluminação por tecla.	699.90	14		0	assets/images/categorias/perifericos.svg	2026-10-06 14:10:30
65	8	Gabinete ATX airflow R5	Mid tower com lateral em tela e 3 ventoinhadores frontais.	749.90	12		0	assets/images/categorias/gabinetes.svg	2026-10-06 14:10:30
66	8	Gabinete Full Tower 4000D	Full tower ATX com painel frontal em tela.	999.90	9		0	assets/images/categorias/gabinetes.svg	2026-10-06 14:10:30
4	1	AMD Ryzen 7 5700X	Octa-core 3.8 GHz, ideal para jogos e criação de conteúdo.	1299.90	20		1	assets/images/categorias/processadores.svg	2026-10-06 14:10:30
6	1	AMD Ryzen 9 5900X	Dodeca-core para edição de vídeo, streaming e uso pesado.	2699.90	8		0	assets/images/categorias/processadores.svg	2026-10-06 14:10:30
64	8	Gabinete ATX gamer H510	Mid tower ATX com frente em malha e RGB.	549.90	15		0	assets/images/categorias/gabinetes.svg	2026-10-06 14:10:30
26	3	MSI PRO B550M-P	Micro-ATX AM4 com header RGB e.Debug USB.	829.90	13		0	assets/images/categorias/placas-mae.svg	2026-10-06 14:10:30
27	3	ASUS TUF B550M-Plus	Micro-ATX AM4,Wifi integrado e dissipateis robusto.	1099.90	11		0	assets/images/categorias/placas-mae.svg	2026-10-06 14:10:30
29	3	ASUS TUF Z690-PLUS	ATX LGA 1700 com 4 slots M.2 e soporte a USB 3.2 Gen 2.	1699.90	8		0	assets/images/categorias/placas-mae.svg	2026-10-06 14:10:30
33	4	DDR4 16GB (2x8GB) 3200MHz	Kit dual channel para jogos.	289.90	30		0	assets/images/categorias/memorias.svg	2026-10-06 14:10:30
37	4	DDR5 32GB (2x16GB) 6000MHz	Kit DDR5 32 GB para workstations.	799.90	14		0	assets/images/categorias/memorias.svg	2026-10-06 14:10:30
30	3	MSI MAG Z790 Tomahawk	ATX LGA 1700 com VRM de 16 fases e rede 2.5G.	2199.90	5		0	assets/images/categorias/placas-mae.svg	2026-10-06 14:10:30
43	5	SSD NVMe 2TB Gen4	NVMe PCIe 4.0 de alta capacidade.	1099.90	15		0	assets/images/categorias/armazenamento.svg	2026-10-06 14:10:30
51	6	Fonte 750W 80 Plus Gold	750 W Gold para setups com GPU de alta performance.	699.90	15		1	assets/images/categorias/fontes.svg	2026-10-06 14:10:30
46	5	HD 4TB NAS	Disco de 4 TB para NAS e armazenamento em rede.	999.90	10		0	assets/images/categorias/armazenamento.svg	2026-10-06 14:10:30
12	2	NVIDIA GeForce GTX 1650	4 GB, boa escolha para esports e jogos leves em 1080p.	1249.90	18		0	assets/images/categorias/placas-de-video.svg	2026-10-06 14:10:30
3	1	AMD Ryzen 5 5600X	Hexa-core 4.6 GHz para quem quer mais quadros por segundo.	1049.90	16		0	assets/images/categorias/processadores.svg	2026-10-06 14:10:30
13	2	NVIDIA GeForce GTX 1660 Super	6 GB para jogos em 1440p com taxa de quadros alta.	2199.90	14		0	assets/images/categorias/placas-de-video.svg	2026-10-06 14:10:30
62	8	Gabinete Mid Tower Q300L	Mid tower com painel em tela e frontal em malha.	379.90	20		0	assets/images/categorias/gabinetes.svg	2026-10-06 14:10:30
5	1	AMD Ryzen 7 5800X	Octa-core 3.8 GHz com boost de 4.4 GHz.	1899.90	12		0	assets/images/categorias/processadores.svg	2026-10-06 14:10:30
63	8	Gabinete Mid Tower vidro 200D	Mid tower com lateral em vidro temperado.	449.90	18		0	assets/images/categorias/gabinetes.svg	2026-10-06 14:10:30
77	10	Mouse gamer 8000 DPI	Sensor de 8000 DPI com RGB.	149.90	30		0	assets/images/categorias/perifericos.svg	2026-10-06 14:10:30
22	2	AMD Radeon RX 6600	8 GB com bom desempenho em 1080p e 1440p.	1899.90	13		0	assets/images/categorias/placas-de-video.svg	2026-10-06 14:10:30
60	7	Water cooler 360mm AIO	Liquid cooling de 360 mm para setups de alta performance.	899.90	8		0	assets/images/categorias/coolers.svg	2026-10-06 14:10:30
23	2	AMD Radeon RX 7600	8 GB para 1440p com ray tracing.	2299.90	15		1	assets/images/categorias/placas-de-video.svg	2026-10-06 14:10:30
56	7	Cooler Air 120mm (AK400)	Torre compacta com um ventoinhador de 120 mm.	199.90	20		0	assets/images/categorias/coolers.svg	2026-10-06 14:10:30
57	7	Cooler Air 140mm (Arctic P14)	Torre com ventoinhador de 140 mm e cinco heatpipes.	289.90	16		0	assets/images/categorias/coolers.svg	2026-10-06 14:10:30
1	1	AMD Ryzen 3 3200G	Quad-core com Radeon Vega integrado, entrada acessível.	549.90	18		0	assets/images/categorias/processadores.svg	2026-10-06 14:10:30
2	1	AMD Ryzen 5 5600	Hexa-core 3.9 GHz, ótimo custo-benefício para jogos em 1080p.	899.90	24		0	assets/images/categorias/processadores.svg	2026-10-06 14:10:30
7	1	Intel Core i5-12400F	Hexa-core LGA 1700, exige placa-mãe com chipset série 600.	1099.90	22		0	assets/images/categorias/processadores.svg	2026-10-06 14:10:30
8	1	Intel Core i5-13400F	Performance intermediária excelente para gaming e produtividade.	1449.90	15		0	assets/images/categorias/processadores.svg	2026-10-06 14:10:30
9	1	Intel Core i7-13700F	Octa-core com 16 threads, ideal para jogos e edição.	2499.90	10		0	assets/images/categorias/processadores.svg	2026-10-06 14:10:30
11	2	NVIDIA GeForce GT 1030	2 GB GDDR5, compacta e econômica para uso geral.	599.90	20		0	assets/images/categorias/placas-de-video.svg	2026-10-06 14:10:30
14	2	NVIDIA GeForce RTX 3050	8 GB com Ray Tracing de 2ª geração.	2499.90	16		0	assets/images/categorias/placas-de-video.svg	2026-10-06 14:10:30
15	2	NVIDIA GeForce RTX 3060	12 GB, ótima para 1440p e ray tracing.	3199.90	15		1	assets/images/categorias/placas-de-video.svg	2026-10-06 14:10:30
20	2	NVIDIA GeForce RTX 4080	16 GB com DLSS 3, referência em 4K.	8499.90	5		1	assets/images/categorias/placas-de-video.svg	2026-10-06 14:10:30
10	1	Intel Core i9-14900K	24 núcleos para estações de trabalho e quem quer fazer overclock.	4799.90	4		0	assets/images/categorias/processadores.svg	2026-10-06 14:10:30
18	2	NVIDIA GeForce RTX 4060 Ti	8 GB com clock mais alto que a 4060 padrão.	3699.90	11		0	assets/images/categorias/placas-de-video.svg	2026-10-06 14:10:30
19	2	NVIDIA GeForce RTX 4070	12 GB com DLSS 3, Ideal para 1440p em altíssima qualidade.	5499.90	9		0	assets/images/categorias/placas-de-video.svg	2026-10-06 14:10:30
21	2	AMD Radeon RX 580 8GB	8 GB para jogos em 1080p com boa faixa de preço.	899.90	19		0	assets/images/categorias/placas-de-video.svg	2026-10-06 14:10:30
28	3	Gigabyte B760M Aorus Elite	Micro-ATX LGA 1700 para(processadores Intel de 12ª e 13ª geração).	1199.90	10		0	assets/images/categorias/placas-mae.svg	2026-10-06 14:10:30
32	4	DDR4 8GB 3200MHz	Módulo único DDR4, upgrade simples.	149.90	35		0	assets/images/categorias/memorias.svg	2026-10-06 14:10:30
34	4	DDR4 32GB (2x16GB) 3200MHz	Kit 32 GB dual channel para uso geral e edição.	549.90	22		0	assets/images/categorias/memorias.svg	2026-10-06 14:10:30
35	4	DDR4 16GB 3600MHz RGB	Kit com iluminação RGB e latência baixa.	379.90	16		0	assets/images/categorias/memorias.svg	2026-10-06 14:10:30
55	7	AMD Wraith Stealth	Cooler de fábrica AMD, baixo perfil.	99.90	21		0	assets/images/categorias/coolers.svg	2026-10-06 14:10:30
50	6	Fonte 650W 80 Plus Gold	650 W Gold, recomendada para placas de vídeo de até RTX 3060.	549.90	18		0	assets/images/categorias/fontes.svg	2026-10-06 14:10:30
45	5	HD 2TB 7200 RPM	Disco mecânico de 2 TB, bom para mídia e backups.	599.90	18		0	assets/images/categorias/armazenamento.svg	2026-10-06 14:10:30
47	6	Fonte 400W 80 Plus Bronze	400 W reais para setups básicos.	249.90	25		0	assets/images/categorias/fontes.svg	2026-10-06 14:10:30
48	6	Fonte 500W 80 Plus Bronze	500 W para entrada com placa de vídeo dedicada.	299.90	30		0	assets/images/categorias/fontes.svg	2026-10-06 14:10:30
49	6	Fonte 550W 80 Plus Bronze	550 W: par ideal para Ryzen 5 ou Core i5.	349.90	22		0	assets/images/categorias/fontes.svg	2026-10-06 14:10:30
52	6	Fonte 850W 80 Plus Gold	850 W Gold para RTX 4070 e processadores de 8 núcleos ou mais.	899.90	12		0	assets/images/categorias/fontes.svg	2026-10-06 14:10:30
53	6	Fonte 1000W 80 Plus Platinum	1000 W Platinum para estações de trabalho com RTX 4080.	1599.90	6		0	assets/images/categorias/fontes.svg	2026-10-06 14:10:30
54	7	Cooler box Intel LGA1700	Cooler de fábrica que acompanha CPUs Intel selecionadas.	89.90	25		0	assets/images/categorias/coolers.svg	2026-10-06 14:10:30
58	7	Noctua NH-D15	Torre de duplo fan, referência em cooler air silencioso.	749.90	8		0	assets/images/categorias/coolers.svg	2026-10-06 14:10:30
92	11	PC Creator de Vídeo	Intel Core i5-13400F + RTX 3060 + 32 GB DDR4 + SSD 1 TB. Edição e montagem de vídeo.	7999.90	6		0	assets/images/categorias/pcs.svg	2026-10-06 14:10:30
93	11	PC Gamer Pro	Ryzen 7 5800X + RTX 3060 + 32 GB DDR4 + SSD NVMe 1 TB. 1440p fluido.	8499.90	9		1	assets/images/categorias/pcs.svg	2026-10-06 14:10:30
94	11	PC Streaming	Intel Core i5-13400F + RTX 3060 + 32 GB DDR4 + SSD 1 TB. Transmissão de alta qualidade.	8999.90	5		0	assets/images/categorias/pcs.svg	2026-10-06 14:10:30
95	11	PC Gamer Extreme	Ryzen 9 5900X + RTX 4070 + 32 GB DDR4 + SSD NVMe 2 TB. 1440p no máximo.	13999.90	4		1	assets/images/categorias/pcs.svg	2026-10-06 14:10:30
96	11	PC Creator 3D	Ryzen 9 5900X + RTX 4070 + 64 GB DDR5 + SSD 2 TB. Modelagem 3D e renderização.	16999.90	3		0	assets/images/categorias/pcs.svg	2026-10-06 14:10:30
97	11	PC Workstation	Intel Core i9-14900K + RTX 4080 + 64 GB DDR5 + SSD 2 TB. Uso profissional pesado.	22999.90	2		0	assets/images/categorias/pcs.svg	2026-10-06 14:10:30
\.


--
-- TOC entry 3525 (class 0 OID 32955)
-- Dependencies: 220
-- Data for Name: usuarios; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.usuarios (id, nome, email, senha, tipo, data_cadastro) FROM stdin;
1	Administrador	admin@techzone.com	$2y$12$FmLBnm3gYU38rwL71jKx6OjeXBOUzjNdqDz8dHyHKDc7xv/dKfv6q	admin	2026-10-06 14:10:30
2	Cliente Teste	cliente@techzone.com	$2y$12$G9Tgg0X6YSZ4xa3pWLxsteBsQC5izIFMw1U/IBtbRS6XbJn/jDqU6	cliente	2026-10-06 14:10:30
4	Nicollas	nicollas@nicollas.com	$2y$12$QQxHux3QseTLDfnIVvzWbuyP6O6PdonEF44d1kk6djGd9PYcXmhk.	cliente	2026-10-20 14:59:07.479666
\.


--
-- TOC entry 3544 (class 0 OID 0)
-- Dependencies: 221
-- Name: categorias_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.categorias_id_seq', 12, false);


--
-- TOC entry 3545 (class 0 OID 0)
-- Dependencies: 227
-- Name: itens_pedido_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.itens_pedido_id_seq', 12, true);


--
-- TOC entry 3546 (class 0 OID 0)
-- Dependencies: 225
-- Name: pedidos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.pedidos_id_seq', 3, true);


--
-- TOC entry 3547 (class 0 OID 0)
-- Dependencies: 223
-- Name: produtos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.produtos_id_seq', 98, true);


--
-- TOC entry 3548 (class 0 OID 0)
-- Dependencies: 219
-- Name: usuarios_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.usuarios_id_seq', 4, true);


--
-- TOC entry 3362 (class 2606 OID 32987)
-- Name: categorias categorias_nome_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categorias
    ADD CONSTRAINT categorias_nome_key UNIQUE (nome);


--
-- TOC entry 3364 (class 2606 OID 32985)
-- Name: categorias categorias_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categorias
    ADD CONSTRAINT categorias_pkey PRIMARY KEY (id);


--
-- TOC entry 3372 (class 2606 OID 33045)
-- Name: itens_pedido itens_pedido_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.itens_pedido
    ADD CONSTRAINT itens_pedido_pkey PRIMARY KEY (id);


--
-- TOC entry 3370 (class 2606 OID 33028)
-- Name: pedidos pedidos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pedidos
    ADD CONSTRAINT pedidos_pkey PRIMARY KEY (id);


--
-- TOC entry 3366 (class 2606 OID 33007)
-- Name: produtos produtos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.produtos
    ADD CONSTRAINT produtos_pkey PRIMARY KEY (id);


--
-- TOC entry 3368 (class 2606 OID 33009)
-- Name: produtos uniq_produto; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.produtos
    ADD CONSTRAINT uniq_produto UNIQUE (nome, categoria_id);


--
-- TOC entry 3358 (class 2606 OID 32972)
-- Name: usuarios usuarios_email_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_email_key UNIQUE (email);


--
-- TOC entry 3360 (class 2606 OID 32970)
-- Name: usuarios usuarios_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_pkey PRIMARY KEY (id);


--
-- TOC entry 3375 (class 2606 OID 33046)
-- Name: itens_pedido itens_pedido_pedido_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.itens_pedido
    ADD CONSTRAINT itens_pedido_pedido_id_fkey FOREIGN KEY (pedido_id) REFERENCES public.pedidos(id) ON DELETE CASCADE;


--
-- TOC entry 3376 (class 2606 OID 33051)
-- Name: itens_pedido itens_pedido_produto_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.itens_pedido
    ADD CONSTRAINT itens_pedido_produto_id_fkey FOREIGN KEY (produto_id) REFERENCES public.produtos(id) ON DELETE RESTRICT;


--
-- TOC entry 3374 (class 2606 OID 33029)
-- Name: pedidos pedidos_usuario_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pedidos
    ADD CONSTRAINT pedidos_usuario_id_fkey FOREIGN KEY (usuario_id) REFERENCES public.usuarios(id) ON DELETE CASCADE;


--
-- TOC entry 3373 (class 2606 OID 33010)
-- Name: produtos produtos_categoria_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.produtos
    ADD CONSTRAINT produtos_categoria_id_fkey FOREIGN KEY (categoria_id) REFERENCES public.categorias(id) ON DELETE RESTRICT;


-- Completed on 2026-10-22 10:07:25 UTC

--
-- PostgreSQL database dump complete
--

\unrestrict eEUNNejFD1Q2EBZVyJYjOzwl5uuhQyRSIzrzkPaI9J2Dm72c9qMwva9SCRIyBFm

