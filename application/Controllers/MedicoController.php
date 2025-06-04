<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../Models/Medico.php';

$medicoModel = new Medico($conn);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['acao']) && $_GET['acao'] === 'listar') {
    $medicos = $medicoModel->listarTodosComEspecialidade(); // <- já pega o nome da especialidade
    echo json_encode($medicos);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'cadastrar') {
    $nome = $_POST['nome'] ?? '';
    $crm = $_POST['crm'] ?? '';
    $especialidadeId = $_POST['especialidadeId'] ?? '';

    if ($medicoModel->cadastrar($nome, $crm, $especialidadeId)) {
        echo json_encode(["sucesso" => true]);
    } else {
        echo json_encode(["sucesso" => false, "erro" => "Erro ao cadastrar médico."]);
    }
    exit;
}