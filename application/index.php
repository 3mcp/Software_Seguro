<?php

declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', 'On');

// Carregar o autoloader do Composer
require_once __DIR__ . '/../vendor/autoload.php';

// Configuração do banco
require_once __DIR__ . '/../config/config.php';

use App\Utils\Sessao;
use App\Utils\VerificaSessao;

// Inicia a sessão (caso ainda não tenha sido iniciada)
Sessao::iniciar();

// Recuperar os parâmetros
$pagina = $_GET['pagina'] ?? '';
$action = $_GET['action'] ?? '';

// Rotas públicas (não exigem sessão ativa)
$rotasPublicas = ['login', 'cadastrarUsuario', 'autenticar'];

// Se não for rota pública, exige sessão
if (!in_array($pagina, $rotasPublicas) && !in_array($action, $rotasPublicas)) {
    VerificaSessao::verificar();
}

// Rotas de páginas (views)
if (!empty($pagina)) {
    $caminhoArquivo = __DIR__ . "/View/{$pagina}.html";
    if (file_exists($caminhoArquivo)) {
        require_once $caminhoArquivo;
        exit;
    } else {
        http_response_code(404);
        echo "Página não encontrada.";
        exit;
    }
}

// Rotas de ações (controllers)
try {
    switch ($action) {
        case 'autenticar':
            (new App\Controllers\LoginController())->autenticar();
            break;

        case 'cadastrarUsuario':
            (new App\Controllers\UsuarioController())->cadastrarUsuario();
            break;

        case 'listarPacientes':
            (new App\Controllers\PacienteController())->listarPacientes();
            break;

        case 'buscarPaciente':
            (new App\Controllers\PacienteController())->buscarPaciente();
            break;

        case 'cadastrarPaciente':
            (new App\Controllers\PacienteController())->cadastrarPaciente();
            break;

        case 'atualizarPaciente':
            (new App\Controllers\PacienteController())->atualizarPaciente();
            break;

        case 'removerPaciente':
            (new App\Controllers\PacienteController())->removerPaciente();
            break;

        case 'listarMedicos':
            (new App\Controllers\MedicoController())->listarMedicos();
            break;

        case 'buscarMedico':
            (new App\Controllers\MedicoController())->buscarMedico();
            break;

        case 'cadastrarMedico':
            (new App\Controllers\MedicoController())->cadastrarMedico();
            break;

        case 'atualizarMedico':
            (new App\Controllers\MedicoController())->atualizarMedico();
            break;

        case 'removerMedico':
            (new App\Controllers\MedicoController())->removerMedico();
            break;

        case 'listarEspecialidades':
            (new App\Controllers\EspecialidadeController())->listarEspecialidades();
            break;

        case 'buscarEspecialidade':
            (new App\Controllers\EspecialidadeController())->buscarEspecialidade();
            break;

        case 'cadastrarEspecialidade':
            (new App\Controllers\EspecialidadeController())->cadastrarEspecialidade();
            break;

        case 'atualizarEspecialidade':
            (new App\Controllers\EspecialidadeController())->atualizarEspecialidade();
            break;

        case 'removerEspecialidade':
            (new App\Controllers\EspecialidadeController())->removerEspecialidade();
            break;

        case 'listarConsultas':
            (new App\Controllers\ConsultaController())->listarConsultas();
            break;

        case 'buscarConsulta':
            (new App\Controllers\ConsultaController())->buscarConsulta();
            break;

        case 'cadastrarConsulta':
            (new App\Controllers\ConsultaController())->cadastrarConsulta();
            break;

        case 'atualizarConsulta':
            (new App\Controllers\ConsultaController())->atualizarConsulta();
            break;

        case 'removerConsulta':
            (new App\Controllers\ConsultaController())->removerConsulta();
            break;

        case 'logout':
            (new App\Controllers\LoginController())->logout();
            break;

        default:
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Ação inválida']);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro interno no servidor: ' . $e->getMessage()]);
}
