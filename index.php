<?php
/**
 * Só existe para quem abre a raiz do servidor (ex.: http://localhost:8080/
 * ou http://localhost/ProjetoFinal/). Manda o visitante para a pasta techzone.
 *
 * O caminho é relativo de propósito: funciona em qualquer subpasta, sem
 * depender de um "/techzone" escrito fixo.
 */

header("Location: techzone/");

exit;