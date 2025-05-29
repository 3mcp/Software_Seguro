<?php
session_start();

require_once "Controllers/LoginController.php";
require_once "Controllers/UsuarioController.php";

// Roteamento por ação (formulário POST, por exemplo)
$action = $_REQUEST['action'] ?? null;

if ($action) {
    $controller = new UsuarioController();
    
    switch ($action) {
        case 'cadastrar':
            $controller->cadastrar();
            break;
        case 'autenticar':
            $controller->autenticar();
            break;
        case 'logout':
            session_destroy();
            header("Location: ?pagina=login");
            exit;
        default:
            echo "Ação inválida.";
            exit;
    }
}

// Roteamento por página (via GET ?pagina=...)
$pagina = $_GET['pagina'] ?? 'login';

switch ($pagina) {
    case 'login':
        include "/View/login.php";
        break;
    case 'cadastro':
        include "/View/cadastro.php";
        break;
    case 'dashboard':
        // Protege para que só usuários logados vejam o dashboard
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: ?pagina=login");
            exit;
        }
        include "View/dashboard.php";
        break;
    default:
        echo "Página não encontrada.";
        break;
}
?>
