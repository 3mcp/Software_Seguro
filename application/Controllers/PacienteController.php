<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../Models/Paciente.php';

$pacienteModel = new Paciente($conn);

// Verifica se a chamada é via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['acao']) && $_GET['acao'] === 'listar') {
    $pacientes = $pacienteModel->listarTodos();
    echo json_encode($pacientes);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'cadastrar') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $dataNascimento = $_POST['dataNascimento'] ?? '';

    if ($pacienteModel->cadastrar($nome, $email, $telefone, $dataNascimento)) {
        echo json_encode(["sucesso" => true]);
    } else {
        echo json_encode(["sucesso" => false, "erro" => "Erro ao cadastrar paciente."]);
    }
    exit;
}