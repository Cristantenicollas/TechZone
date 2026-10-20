<?php
/**
 * Só existe para o servidor de desenvolvimento.
 *
 * O TechZone mora em /techzone (BASE_URL), então a raiz do site não tem
 * nada. Sem este arquivo, http://localhost:8080/ responde 404 sem explicação.
 * Aqui a gente manda o visitante para a página inicial.
 */

header("Location: /techzone/");

exit;