<?php
header("Content-Type: text/plain; charset=utf-8");
foreach (["SCRIPT_NAME", "SCRIPT_FILENAME", "DOCUMENT_ROOT", "REQUEST_URI"] as $k) {
    echo $k . " = " . ($_SERVER[$k] ?? "(vazio)") . "\n";
}
require __DIR__ . "/../includes/config.php";
echo "BASE_URL = [" . BASE_URL . "]\n";