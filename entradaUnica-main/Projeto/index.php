<?php
switch ($_GET['resource'] ?? '') {
    case 'pacientes':
        require_once __DIR__ . '/../app/controllers/PacienteController.php';
        $controller = new PacienteController($conn);
        break;
    case 'usuarios':
        require_once __DIR__ . '/../app/controllers/UsuarioController.php';
        $controller = new UsuarioController($conn);
        break;
    case 'medicos':
        require_once __DIR__ . '/../app/controllers/MedicoController.php';
        $controller = new MedicoController($conn);
        break;
    case 'consultas':
        require_once __DIR__ . '/../app/controllers/ConsultaController.php';
        $controller = new ConsultaController($conn);
        break;
    case 'especialidade':
        require_once __DIR__ . '/../models/Especialidade.php';
        $controller = new EspecialidadeController($conn);
        break;
    default:
        http_response_code(404);
        echo json_encode(["erro" => "Recurso não encontrado"]);
        exit;
}

$controller->processarRequisicao($_SERVER['REQUEST_METHOD']);
?>