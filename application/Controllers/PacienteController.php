<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../Models/Paciente.php';

$pacienteModel = new Paciente($conn);

echo "testando";
// Verifica se a chamada é via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['acao']) && $_GET['acao'] === 'listar') {
    $pacientes = $pacienteModel->listarTodos();
    echo json_encode($pacientes);
    exit;
}