<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../Models/Consulta.php';

header('Content-Type: application/json');

// Lê os dados enviados em JSON
$raw = file_get_contents("php://input");
$input = json_decode($raw, true);

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
            require_once __DIR__ . '/../Models/Paciente.php';
            $pacienteModel = new Paciente($conn);
            $paciente = $pacienteModel->buscarPorId($pacienteId);

            echo json_encode(['sucesso' => true]);
        } else {
            echo json_encode(['erro' => 'Erro ao agendar a consulta.']);
        }
        break;

    case 'listar':
        $consultas = $consultaModel->listarConsultas();
        echo json_encode($consultas);
        break;
    case 'buscar':
        $id = $input['id'] ?? null;
        if (!$id) {
            echo json_encode(['erro' => 'ID da consulta não informado.']);
            exit;
        }

        $consulta = $consultaModel->buscarConsultaPorId($id);

        if ($consulta) {
            echo json_encode($consulta);
        } else {
            echo json_encode(['erro' => 'Consulta não encontrada.']);
        }
        break;

    case 'editar':
        $id = $input['id'] ?? null;
        $dataHora = $input['dataHora'] ?? null;

        if (!$id || !$dataHora) {
            echo json_encode(['erro' => 'ID e nova data/hora obrigatórios.']);
            exit;
        }

        $sucesso = $consultaModel->atualizarDataHora($id, $dataHora);
        echo json_encode(['sucesso' => $sucesso]);
        break;

    case 'excluir':
        $id = $input['id'] ?? null;

        if (!$id) {
            echo json_encode(['erro' => 'ID da consulta não informado.']);
            exit;
        }

        $sucesso = $consultaModel->excluirConsulta($id);
        echo json_encode(['sucesso' => $sucesso]);
        break;

    default:
        echo json_encode(['erro' => 'Ação não reconhecida.']);
        break;
}
