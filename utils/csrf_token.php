<?php
require_once __DIR__ . '/../utils/sessao.php';

// Inicia buffer de saída para evitar poluição de JSON
ob_start();

header('Content-Type: application/json');

// Gera token apenas se não existir
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Sempre retorna apenas o JSON puro
echo json_encode(['token' => $_SESSION['csrf_token']]);

ob_end_flush();
?>
