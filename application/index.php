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

switch ($pagina) {
    case 'login':
        include "/View/login.html";
        break;
    case 'cadastro':
        include "/View/cadastro.html";
        break;
    case 'dashboard':
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: ?pagina=login");
            exit;
        }
        include __DIR__ . "/View/dashboard.html";
        break;

    case 'agenda':
        include __DIR__ . "/View/agenda.html";
        break;

    case 'consulta-detalhe':
        include __DIR__ . "/View/consulta-detalhe.html";
        break;

    case 'cadastro-paciente':
        include __DIR__ . "/View/cadastro-paciente.html";
        break;
    case 'cadastro-medico':
        include __DIR__ . "/View/cadastro-medico.html";
        break;

    case 'cadastro-especialidade':
        include __DIR__ . "/View/cadastro-especialidade.html";
        break;
    
    case 'listagem-pacientes':
        include __DIR__ . "/View/listagem-pacientes.html";
        break;

    case 'listagem-medicos':
        include __DIR__ . "/View/listagem-medicos.html";
        break;

    case 'listagem-especialidades':
        include __DIR__ . "/View/listagem-especialidades.html";
        break;
    
    default:
        echo "Página não encontrada.";
        break;
}
?>
