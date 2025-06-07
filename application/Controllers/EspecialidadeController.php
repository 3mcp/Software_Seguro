<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../Models/Especialidade.php';

$especialidadeModel = new Especialidade($conn);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['acao']) && $_GET['acao'] === 'listar') {
    $dados = $especialidadeModel->listarTodas();
    echo json_encode($dados);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['acao'] === 'cadastrar') {
    $nome = $_POST['nome'] ?? '';

    if ($especialidadeModel->cadastrar($nome)) {
        echo json_encode(["sucesso" => true]);
    } else {
        echo json_encode(["sucesso" => false]);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['acao'] === 'excluir') {
    $id = $_POST['id'] ?? null;
    echo json_encode(['sucesso' => $especialidadeModel->excluir($id)]);
    exit;
}
