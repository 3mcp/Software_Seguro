<?php

namespace App\Controllers;

require_once __DIR__ . '/../models/Paciente.php';
require_once __DIR__ . '/../../utils/sessao.php';
require_once __DIR__ . '/../../utils/csrf_validador.php';
require_once __DIR__ . '/../../utils/logger.php';

use App\Models\Paciente;
use App\Utils\Logger;

class PacienteController
{
    private $model;

    public function __construct()
    {
        $this->model = new Paciente();
    }

    public function listarPacientes()
    {
        try {
            header('Content-Type: application/json');
            $pacientes = $this->model->listarPacientes();
            echo json_encode(['success' => true, 'data' => $pacientes]);
        } catch (\Exception $e) {
            Logger::logErro($e);
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro ao listar pacientes.']);
        }
    }

    public function buscarPaciente()
    {
        try {
            if (!isset($_GET['id'])) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'ID não informado']);
                return;
            }

            $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
            $paciente = $this->model->buscarPacientePorId($id);

            if ($paciente) {
                echo json_encode(['success' => true, 'data' => $paciente]);
            } else {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Paciente não encontrado']);
            }
        } catch (\Exception $e) {
            Logger::logErro($e);
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro ao buscar paciente.']);
        }
    }

    public function cadastrarPaciente()
    {
        try {
            $csrf_token = $_POST['csrf_token'] ?? '';
            if (!valida_csrf($csrf_token)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Token CSRF inválido']);
                return;
            }

            $nome = trim($_POST['nome'] ?? '');
            $cpf = trim($_POST['cpf'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $telefone = trim($_POST['telefone'] ?? '');
            $dataNascimento = trim($_POST['dataNascimento'] ?? '');

            if (!$nome || !$cpf || !$email || !$telefone || !$dataNascimento) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Todos os campos são obrigatórios']);
                return;
            }

            $sucesso = $this->model->cadastrarPaciente($nome, $cpf, $email, $telefone, $dataNascimento);
            if ($sucesso) {
                echo json_encode(['success' => true, 'message' => 'Paciente cadastrado com sucesso']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Falha ao cadastrar paciente']);
            }
        } catch (\Exception $e) {
            Logger::logErro($e);
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro interno no cadastro']);
        }
    }

    public function atualizarPaciente()
    {
        try {
            $csrf_token = $_POST['csrf_token'] ?? '';
            if (!valida_csrf($csrf_token)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Token CSRF inválido']);
                return;
            }

            $id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
            $nome = trim($_POST['nome'] ?? '');
            $cpf = trim($_POST['cpf'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $telefone = trim($_POST['telefone'] ?? '');
            $dataNascimento = trim($_POST['dataNascimento'] ?? '');

            if (!$id || !$nome || !$cpf || !$email || !$telefone || !$dataNascimento) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Todos os campos são obrigatórios']);
                return;
            }

            $sucesso = $this->model->atualizarPaciente($id, $nome, $cpf, $email, $telefone, $dataNascimento);
            if ($sucesso) {
                echo json_encode(['success' => true, 'message' => 'Paciente atualizado com sucesso']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Falha ao atualizar paciente']);
            }
        } catch (\Exception $e) {
            Logger::logErro($e);
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro interno na atualização']);
        }
    }

    public function removerPaciente()
    {
        try {
            if (!isset($_GET['id'])) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'ID não informado']);
                return;
            }

            $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
            $sucesso = $this->model->removerPaciente($id);

            if ($sucesso) {
                echo json_encode(['success' => true, 'message' => 'Paciente removido com sucesso']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Falha ao remover paciente']);
            }
        } catch (\Exception $e) {
            Logger::logErro($e);
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro interno na remoção']);
        }
    }
}
