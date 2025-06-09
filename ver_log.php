<?php
require_once 'utils/sessao.php';

// Apenas usuário admin pode acessar
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario'] !== 'admin') {
    http_response_code(403);
    echo "Acesso restrito.";
    exit;
}

echo "<h2>Logs do sistema</h2>";
echo "<pre>";
readfile(__DIR__ . "/logs/sistema.log");
echo "</pre>";