<?php
require_once __DIR__ . '/../vendor/autoload.php';

$controller = new UsuarioController();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao'])) {
    $acao = $_POST['acao'];
    if (method_exists($controller, $acao)) {
        $controller->$acao();
    } else {
        echo "Ação inválida.";
    }
} else {
    echo "Requisição inválida.";
}
