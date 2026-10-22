<?php
/**
 * Configuração central do projeto.
 * Altere aqui os dados do banco. O caminho base (BASE_URL) é calculado sozinho.
 */

// ---------------------------------------------------------------- Caminho base
/**
 * BASE_URL = caminho da pasta "techzone" visto pelo navegador.
 *
 *   iniciar-servidor.cmd          -> /techzone
 *   XAMPP (htdocs/ProjetoFinal)   -> /ProjetoFinal/techzone
 *   hospedagem (techzone na raiz) -> ""  (vazio)
 *
 * Se algum dia o cálculo automático errar, force o valor sem editar o código,
 * definindo a variável de ambiente TECHZONE_BASE_URL (ex.: "/meu-site").
 */
function calcularBaseUrl(): string
{
    $forcado = getenv("TECHZONE_BASE_URL");
    if ($forcado !== false) {
        return rtrim($forcado, "/");
    }

    $raizServidor = realpath($_SERVER["DOCUMENT_ROOT"] ?? "") ?: "";
    $raizProjeto  = realpath(__DIR__ . "/..") ?: "";

    $raizServidor = rtrim(str_replace("\\", "/", $raizServidor), "/");
    $raizProjeto  = rtrim(str_replace("\\", "/", $raizProjeto), "/");

    // O projeto precisa estar dentro da raiz do servidor (compara sem diferenciar
    // maiúsculas só para o caso de "C:\" x "c:\" no Windows).
    if ($raizServidor !== "" && stripos($raizProjeto, $raizServidor . "/") === 0) {
        return substr($raizProjeto, strlen($raizServidor));
    }

    // Projeto na própria raiz do servidor.
    if ($raizServidor !== "" && strcasecmp($raizProjeto, $raizServidor) === 0) {
        return "";
    }

    // Não deu para calcular (ex.: link simbólico): valor padrão.
    return "/techzone";
}

define("BASE_URL", calcularBaseUrl());

// ------------------------------------------------------------------- Banco de dados
// PostgreSQL (servidor acessado via MobaXterm).
define("DB_HOST", "192.168.10.37");
define("DB_PORT", 5432);
define("DB_USER", "postgres");
define("DB_PASS", "nick2509");
define("DB_NAME", "techzone");

// ------------------------------------------------------------------- Layout
define("NOME_PROJETO", "TechZone");