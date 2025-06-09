<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once "Controllers/LoginController.php";
require_once "Controllers/UsuarioController.php";
require_once "utils/sessao.php"; // Gerencia session_start e tempo de vida da sessão

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
        include "View/login.html";
        break;

    case 'cadastrar':
        include "View/cadastro.html";
        break;

    case 'dashboard':
    case 'agenda':
    case 'cadastro-paciente':
    case 'cadastro-medico':
    case 'cadastro-especialidade':
    case 'consulta-detalhe':
    case 'listagem-pacientes':
    case 'listagem-medicos':
    case 'listagem-especialidades':
        // Proteger todas essas páginas com sessão
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: ?pagina=login");
            exit;
        }
        include __DIR__ . "/View/{$pagina}.html";
        break;

    default:
        echo "Página não encontrada.";
        break;
}
