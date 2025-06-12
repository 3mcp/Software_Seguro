<?php
require_once __DIR__ . '/../Helpers/Logger.php'; // para arquivos dentro de Controllers


require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../Models/Paciente.php';

$pacienteModel = new Paciente($conn);

// Log da requisição
$acaoRecebida = $_GET['acao'] ?? $_POST['acao'] ?? 'não informada';
registrarLog("Requisição recebida: Método {$_SERVER['REQUEST_METHOD']} | Ação: $acaoRecebida");

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['acao']) && $_GET['acao'] === 'listar') {
    registrarLog("Ação: listar pacientes");
    $pacientes = $pacienteModel->listarTodos();
    registrarLog("Total de pacientes retornados: " . count($pacientes));
    echo json_encode($pacientes);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'cadastrar') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $dataNascimento = $_POST['dataNascimento'] ?? '';

    registrarLog("Ação: cadastrar paciente | Nome: $nome | Email: $email | Telefone: $telefone | Data Nasc: $dataNascimento");

    if ($pacienteModel->cadastrar($nome, $email, $telefone, $dataNascimento)) {
        registrarLog("Paciente cadastrado com sucesso: $nome");
        echo json_encode(["sucesso" => true]);
    } else {
        registrarLog("Erro ao cadastrar paciente: $nome");
        echo json_encode(["sucesso" => false, "erro" => "Erro ao cadastrar paciente."]);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['acao'] === 'excluir') {
    $id = $_POST['id'] ?? null;
    registrarLog("Ação: excluir paciente | ID: $id");

    $sucesso = $pacienteModel->excluir($id);
    registrarLog("Resultado da exclusão do paciente ID $id: " . ($sucesso ? 'sucesso' : 'falha'));
    echo json_encode(['sucesso' => $sucesso]);
    exit;
}
