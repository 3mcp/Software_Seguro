<?php
require_once __DIR__ . '/../Helpers/Logger.php'; // para arquivos dentro de Controllers


require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../Models/Medico.php';

$medicoModel = new Medico($conn);

// Log da requisição
$acaoRecebida = $_GET['acao'] ?? $_POST['acao'] ?? 'não informada';
registrarLog("Requisição recebida: Método {$_SERVER['REQUEST_METHOD']} | Ação: $acaoRecebida");

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['acao']) && $_GET['acao'] === 'listar') {
    registrarLog("Ação: listar médicos");
    $medicos = $medicoModel->listarTodosComEspecialidade();
    registrarLog("Total de médicos retornados: " . count($medicos));
    echo json_encode($medicos);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'cadastrar') {
    $nome = $_POST['nome'] ?? '';
    $crm = $_POST['crm'] ?? '';
    $especialidadeId = $_POST['especialidadeId'] ?? '';

    registrarLog("Ação: cadastrar médico | Nome: $nome | CRM: $crm | Especialidade ID: $especialidadeId");

    if ($medicoModel->cadastrar($nome, $crm, $especialidadeId)) {
        registrarLog("Médico cadastrado com sucesso: $nome");
        echo json_encode(["sucesso" => true]);
    } else {
        registrarLog("Erro ao cadastrar médico: $nome");
        echo json_encode(["sucesso" => false, "erro" => "Erro ao cadastrar médico."]);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = json_decode(file_get_contents("php://input"), true);
    $acao = $dados['acao'] ?? '';

    if ($acao === 'excluir') {
        $id = $dados['id'] ?? null;
        registrarLog("Ação: excluir médico | ID: $id");

        if ($id && $medicoModel->excluir($id)) {
            registrarLog("Médico excluído com sucesso: ID $id");
            echo json_encode(["sucesso" => true]);
        } else {
            registrarLog("Erro ao excluir médico: ID $id");
            echo json_encode(["sucesso" => false, "erro" => "Erro ao excluir médico."]);
        }
        exit;
    }
}

if (isset($_GET['acao']) && $_GET['acao'] === 'buscarEspecialidade' && isset($_GET['medicoId'])) {
    $id = $_GET['medicoId'];
    registrarLog("Ação: buscar especialidade por médico | Médico ID: $id");
    $especialidade = $medicoModel->buscarEspecialidadePorMedico($id);
    registrarLog("Resultado da especialidade: " . json_encode($especialidade));
    echo json_encode($especialidade);
    exit;
}
