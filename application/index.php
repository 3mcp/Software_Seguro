<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';


use App\Controllers\LoginController;
use App\Controllers\UsuarioController;
use App\Controllers\PacienteController;
use App\Controllers\MedicoController;
use App\Controllers\EspecialidadeController;
use App\Controllers\ConsultaController;
use App\Utils\VerificaSessao;

// Inicia a sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Recebe os parâmetros
$pagina = $_GET['pagina'] ?? null;
$action = $_GET['action'] ?? null;

// Bloco do logout (coloque logo aqui, após capturar $pagina e $action)
if ($pagina === 'logout') {
    session_destroy();
    header("Location: index.php?pagina=login");
    exit;
}

// Rotas públicas
$rotasPublicas = ['login', 'cadastrarUsuario', 'autenticar', 'cadastro'];

// Protege as páginas privadas
if (!in_array($pagina, $rotasPublicas) && !in_array($action, $rotasPublicas)) {
    if (!VerificaSessao::verificaSessaoAtiva()) {
        header("Location: index.php?pagina=login");
        exit;
    }
}

// Se for chamada de página (view HTML)
if ($pagina) {
    include __DIR__ . "/View/{$pagina}.html";
    exit;
}

// Agora o roteamento dos controllers para API

if ($action) {
    header("Content-Type: application/json");

    try {
        switch ($action) {
            // Login
            case 'autenticar':
                (new LoginController())->autenticar();
                break;

            // Cadastro de usuário
            case 'cadastrarUsuario':
                (new UsuarioController())->cadastrarUsuario();
                break;

            // Pacientes
            case 'cadastrarPaciente':
                (new PacienteController())->cadastrarPaciente();
                break;
            case 'listarPacientes':
                (new PacienteController())->listarPacientes();
                break;
            case 'buscarPaciente':
                (new PacienteController())->buscarPaciente();
                break;
            case 'atualizarPaciente':
                (new PacienteController())->atualizarPaciente();
                break;
            case 'removerPaciente':
                (new PacienteController())->removerPaciente();
                break;

            // Médicos
            case 'cadastrarMedico':
                (new MedicoController())->cadastrarMedico();
                break;
            case 'listarMedicos':
                (new MedicoController())->listarMedicos();
                break;
            case 'buscarMedico':
                (new MedicoController())->buscarMedico();
                break;
            case 'atualizarMedico':
                (new MedicoController())->atualizarMedico();
                break;
            case 'removerMedico':
                (new MedicoController())->removerMedico();
                break;

            // Especialidades
            case 'cadastrarEspecialidade':
                (new EspecialidadeController())->cadastrarEspecialidade();
                break;
            case 'listarEspecialidades':
                (new EspecialidadeController())->listarEspecialidades();
                break;
            case 'buscarEspecialidade':
                (new EspecialidadeController())->buscarEspecialidade();
                break;
            case 'atualizarEspecialidade':
                (new EspecialidadeController())->atualizarEspecialidade();
                break;
            case 'removerEspecialidade':
                (new EspecialidadeController())->removerEspecialidade();
                break;

            // Consultas
            case 'cadastrarConsulta':
                (new ConsultaController())->cadastrarConsulta();
                break;
            case 'listarConsultas':
                (new ConsultaController())->listarConsultas();
                break;
            case 'buscarConsulta':
                (new ConsultaController())->buscarConsulta();
                break;
            case 'atualizarConsulta':
                (new ConsultaController())->atualizarConsulta();
                break;
            case 'removerConsulta':
                (new ConsultaController())->removerConsulta();
                break;

            default:
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Ação inválida.']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Erro interno.', 'erro' => $e->getMessage()]);
    }

    exit;
}

// Se não houver página nem action:
header("Location: index.php?pagina=login");
exit;
