<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../Models/Consulta.php';

header('Content-Type: application/json');

// Lê os dados enviados em JSON
$input = json_decode(file_get_contents("php://input"), true);

if (!$input || !isset($input['acao'])) {
    echo json_encode(['erro' => 'Dados inválidos ou ação não especificada.']);
    exit;
}

$consultaModel = new Consulta($conn);

switch ($input['acao']) {
    case 'atualizar':
        $pacienteId = $input['pacienteId'] ?? null;
        $medicoId = $input['medicoId'] ?? null;
        $especialidadeId = $input['especialidadeId'] ?? null;
        $dataHora = $input['dataHora'] ?? null;

        if (!$pacienteId || !$medicoId || !$especialidadeId || !$dataHora) {
            echo json_encode(['erro' => 'Todos os campos são obrigatórios.']);
            exit;
        }

        $sucesso = $consultaModel->criarConsulta($pacienteId, $medicoId, $especialidadeId, $dataHora);

        if ($sucesso) {
            echo json_encode(['sucesso' => true]);
        } else {
            echo json_encode(['erro' => 'Erro ao agendar a consulta.']);
        }
        break;
    
    case 'listar':
        $consultas = $consultaModel->listarConsultas();
        echo json_encode($consultas);
        break;

    default:
        echo json_encode(['erro' => 'Ação não reconhecida.']);
        break;
}
