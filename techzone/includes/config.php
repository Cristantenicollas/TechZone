<?php
/**
 * Configuração central do projeto.
 * Altere aqui os dados do banco. O caminho base (BASE_URL) é calculado sozinho.
 */

// ---------------------------------------------------------------- Caminho base
/**
 * BASE_URL = caminho da pasta "techzone" visto pelo NAVEGADOR.
 *
 * É calculado a partir do endereço da página aberta (SCRIPT_NAME), e não do
 * caminho no disco do servidor. Assim funciona em qualquer lugar:
 *
 *   http://192.168.10.37/ProjetoFinal/techzone/  -> /ProjetoFinal/techzone
 *   http://localhost:8080/techzone/              -> /techzone
 *   site com techzone na raiz                    -> ""  (vazio)
 *
 * Para forçar um valor sem editar o código, defina a variável de ambiente
 * TECHZONE_BASE_URL (ex.: "/ProjetoFinal/techzone").
 */
function calcularBaseUrl(): string
{
    $forcado = getenv("TECHZONE_BASE_URL");
    if ($forcado !== false) {
        return rtrim($forcado, "/");
    }

    // Ex.: /ProjetoFinal/techzone/paginas/produtos.php
    $script = str_replace("\\", "/", $_SERVER["SCRIPT_NAME"] ?? "");
    $pasta  = dirname($script);

    // Páginas dentro de subpastas: sobe até a pasta techzone.
    $pasta = preg_replace('#/(admin|login|paginas|database)$#i', "", $pasta);

    return rtrim($pasta, "/\\.");
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