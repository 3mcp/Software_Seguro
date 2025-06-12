<?php
require_once __DIR__ . '/../Helpers/Logger.php'; // para arquivos dentro de Controllers


require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../Models/Especialidade.php';

header('Content-Type: application/json');

$especialidadeModel = new Especialidade($conn);

// Log da requisição
registrarLog("Requisição recebida: Método " . $_SERVER['REQUEST_METHOD'] . " | Ação: " . ($_GET['acao'] ?? $_POST['acao'] ?? 'não informada'));

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['acao']) && $_GET['acao'] === 'listar') {
    registrarLog("Ação: listar especialidades");
    $dados = $especialidadeModel->listarTodas();
    registrarLog("Total de especialidades retornadas: " . count($dados));
    echo json_encode($dados);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['acao'] === 'cadastrar') {
    $nome = $_POST['nome'] ?? '';
    registrarLog("Ação: cadastrar especialidade | Nome: $nome");

    if ($especialidadeModel->cadastrar($nome)) {
        registrarLog("Especialidade cadastrada com sucesso: $nome");
        echo json_encode(["sucesso" => true]);
    } else {
        registrarLog("Erro ao cadastrar especialidade: $nome");
        echo json_encode(["sucesso" => false]);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['acao'] === 'excluir') {
    $id = $_POST['id'] ?? null;
    registrarLog("Ação: excluir especialidade | ID: $id");

    $resultado = $especialidadeModel->excluir($id);
    registrarLog("Resultado da exclusão ID $id: " . json_encode($resultado));
    echo json_encode($resultado);
    exit;
}
