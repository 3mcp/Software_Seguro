<?php
require_once 'UsuarioController.class.php';

$controller = new UsuarioController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['acao'])) {
        $acao = $_POST['acao'];

        if (method_exists($controller, $acao)) {
            $controller->$acao();
        } else {
            echo "Ação inválida.";
        }
    } else {
        echo "Nenhuma ação especificada.";
    }
    exit;
}
