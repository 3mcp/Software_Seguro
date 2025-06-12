<?php
function registrarLog($mensagem)
{
    $dataHora = date("Y-m-d_H-i-s");
    $dataArquivo = date("Y-m-d");
    $diretorio = __DIR__ . "/Helpers/Logs";
    if (!is_dir($diretorio)) {
        mkdir($diretorio, 0777, true);
    }

    $arquivo = "$diretorio/log_$dataArquivo.txt";
    $mensagemFormatada = "[$dataHora] $mensagem" . PHP_EOL;
    file_put_contents($arquivo, $mensagemFormatada, FILE_APPEND);
}

session_start();
$pagina = $_GET['pagina'] ?? 'login';
$action = $_REQUEST['action'] ?? null;

// Log inicial da sessão
registrarLog("Sessão iniciada. Página solicitada: $pagina. Ação: " . ($action ?? 'nenhuma'));

if ($action) {
    require_once "Controllers/UsuarioController.php";
    $controller = new UsuarioController();

    switch ($action) {
        case 'cadastrar':
            registrarLog("Ação: cadastrar usuário.");
            $controller->cadastrar();
            break;
        case 'autenticar':
            registrarLog("Ação: autenticar usuário.");
            $controller->autenticar();
            break;
        case 'logout':
            registrarLog("Ação: logout. Encerrando sessão.");
            session_destroy();
            header("Location: ?pagina=login");
            exit;
        default:
            registrarLog("Ação inválida recebida: $action");
            echo "Ação inválida.";
            exit;
    }
}

switch ($pagina) {
    case 'login':
        registrarLog("Página: login");
        include __DIR__ . "/View/login.html";
        break;

    case 'cadastrar':
        registrarLog("Página: cadastrar");
        if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
            registrarLog("Acesso negado à página de cadastro - usuário não é admin.");
            echo "Acesso negado. Apenas administradores podem acessar essa página.";
            exit;
        }
        include __DIR__ . "/View/cadastro.html";
        break;

    case 'dashboard':
        registrarLog("Página: dashboard");
        if (!isset($_SESSION['usuario_id'])) {
            registrarLog("Acesso negado ao dashboard - sessão não identificada.");
            header("Location: ?pagina=login");
            exit;
        }
        include __DIR__ . "/View/dashboard.html";
        break;

    case 'agenda':
        registrarLog("Página: agenda");
        include __DIR__ . "/View/agenda.html";
        break;

    case 'consulta-detalhe':
        $idConsulta = $_GET['id'] ?? null;
        registrarLog("Página: consulta-detalhe. ID: " . ($idConsulta ?? 'não informado'));
        if (!$idConsulta) {
            registrarLog("Consulta não encontrada - ID ausente.");
            echo "Consulta não encontrada.";
            exit;
        }
        include __DIR__ . "/View/consulta-detalhe.html";
        break;

    case 'cadastroConsulta':
        registrarLog("Página: cadastroConsulta");
        include __DIR__ . "/View/cadastroConsulta.html";
        break;

    case 'cadastro-paciente':
        registrarLog("Página: cadastro-paciente");
        include __DIR__ . "/View/cadastro-paciente.html";
        break;

    case 'cadastro-medico':
        registrarLog("Página: cadastro-medico");
        include __DIR__ . "/View/cadastro-medico.html";
        break;

    case 'cadastro-especialidade':
        registrarLog("Página: cadastro-especialidade");
        include __DIR__ . "/View/cadastro-especialidade.html";
        break;

    case 'listagem-pacientes':
        registrarLog("Página: listagem-pacientes");
        include __DIR__ . "/View/listagem-pacientes.html";
        break;

    case 'listagem-medicos':
        registrarLog("Página: listagem-medicos");
        include __DIR__ . "/View/listagem-medicos.html";
        break;

    case 'listagem-especialidades':
        registrarLog("Página: listagem-especialidades");
        include __DIR__ . "/View/listagem-especialidades.html";
        break;

    default:
        registrarLog("Página inválida solicitada: $pagina");
        echo "Página não encontrada.";
        break;
}
