<?php
function registrarLog($mensagem) {
    $data = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'CLI';
    $linha = "[$data][$ip] $mensagem" . PHP_EOL;

    file_put_contents(__DIR__ . '/../logs/sistema.log', $linha, FILE_APPEND);
}
