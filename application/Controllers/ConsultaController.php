<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

require_once __DIR__ . '/../Helpers/Logger.php'; // para arquivos dentro de Controllers

// Tratamento de exceções
set_exception_handler(function ($e) {
    registrarLog("Exceção capturada: " . $e->getMessage());
    echo json_encode(['erro' => 'Exceção capturada: ' . $e->getMessage()]);
    exit;
});

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../Models/Consulta.php';

// Lê os dados enviados em JSON
$raw = file_get_contents("php://input");
$input = json_decode($raw, true);

registrarLog("Requisição recebida: " . $raw);

if (!$input || !isset($input['acao'])) {
    registrarLog("Erro: Dados inválidos ou ação não especificada.");
    echo json_encode(['erro' => 'Dados inválidos ou ação não especificada.']);
    exit;
}

$acao = $input['acao'];
$consultaModel = new Consulta($conn);

switch ($acao) {
    case 'atualizar':
        registrarLog("Ação: atualizar consulta");

        $pacienteId = $input['pacienteId'] ?? null;
        $medicoId = $input['medicoId'] ?? null;
        $especialidadeId = $input['especialidadeId'] ?? null;
        $dataHora = $input['dataHora'] ?? null;

        if (!$pacienteId || !$medicoId || !$especialidadeId || !$dataHora) {
            registrarLog("Erro: Campos obrigatórios ausentes.");
            echo json_encode(['erro' => 'Todos os campos são obrigatórios.']);
            exit;
        }

        require_once __DIR__ . '/../Models/Paciente.php';
        $pacienteModel = new Paciente($conn);
        $paciente = $pacienteModel->buscarPorId($pacienteId);
        registrarLog("Paciente carregado: ID $pacienteId - Nome: {$paciente['nome']}");

        $novoId = $consultaModel->criarConsulta($pacienteId, $medicoId, $especialidadeId, $dataHora);

        if ($novoId) {
            registrarLog("Consulta criada com ID: $novoId");
            $consulta = $consultaModel->buscarConsultaPorId($novoId);

            require_once __DIR__ . '/../Helpers/EmailHelper.php';
            EmailHelper::enviarEmailConsulta(
                $paciente['email'],
                $paciente['nome'],
                $consulta['medico'] ?? 'Desconhecido',
                $consulta['especialidade'] ?? 'Desconhecida',
                date('d/m/Y H:i', strtotime($consulta['dataHora'] ?? ''))
            );
            registrarLog("E-mail enviado para: {$paciente['email']}");

            echo json_encode(['sucesso' => true]);
        } else {
            registrarLog("Erro ao inserir a consulta.");
            echo json_encode(['erro' => 'Erro ao inserir a consulta.']);
        }
        exit;

    case 'listar':
        registrarLog("Ação: listar consultas");
        $consultas = $consultaModel->listarConsultas();
        echo json_encode($consultas);
        break;

    case 'buscar':
        $id = $input['id'] ?? null;
        registrarLog("Ação: buscar consulta ID $id");

        if (!$id) {
            registrarLog("Erro: ID da consulta não informado.");
            echo json_encode(['erro' => 'ID da consulta não informado.']);
            exit;
        }

        $consulta = $consultaModel->buscarConsultaPorId($id);

        if ($consulta) {
            registrarLog("Consulta encontrada: ID $id");
            echo json_encode($consulta);
        } else {
            registrarLog("Consulta não encontrada: ID $id");
            echo json_encode(['erro' => 'Consulta não encontrada.']);
        }
        break;

    case 'editar':
        $id = $input['id'] ?? null;
        $dataHora = $input['dataHora'] ?? null;
        registrarLog("Ação: editar consulta ID $id para nova data $dataHora");

        if (!$id || !$dataHora) {
            registrarLog("Erro: ID e nova data/hora obrigatórios.");
            echo json_encode(['erro' => 'ID e nova data/hora obrigatórios.']);
            exit;
        }

        $sucesso = $consultaModel->atualizarDataHora($id, $dataHora);
        registrarLog("Resultado da edição: " . ($sucesso ? 'sucesso' : 'falha'));
        echo json_encode(['sucesso' => $sucesso]);
        break;

    case 'excluir':
        $id = $input['id'] ?? null;
        registrarLog("Ação: excluir consulta ID $id");

        if (!$id) {
            registrarLog("Erro: ID da consulta não informado.");
            echo json_encode(['erro' => 'ID da consulta não informado.']);
            exit;
        }

        $sucesso = $consultaModel->excluirConsulta($id);
        registrarLog("Resultado da exclusão: " . ($sucesso ? 'sucesso' : 'falha'));
        echo json_encode(['sucesso' => $sucesso]);
        break;

    default:
        registrarLog("Erro: Ação '$acao' não reconhecida.");
        echo json_encode(['erro' => 'Ação não reconhecida.']);
        break;
}
