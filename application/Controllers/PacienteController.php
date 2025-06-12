<?php

namespace App\Controllers;

use App\Models\Paciente;
use App\Utils\CSRFValidador;
use Utils\VerificaSessao;

class PacienteController
{
    public function cadastrarPaciente()
    {
        $dados = json_decode(file_get_contents("php://input"), true);
        
        if (!isset($dados['csrf_token'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Token CSRF ausente.']);
            return;
        }

        if (!CSRFValidador::validar($dados['csrf_token'])) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Token CSRF inválido.']);
            return;
        }

        $pacienteModel = new Paciente();
        if ($pacienteModel->inserir($dados)) {
            echo json_encode(['success' => true, 'message' => 'Paciente cadastrado com sucesso.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro ao cadastrar paciente.']);
        }
    }

    public function listarPacientes()
    {
        $pacienteModel = new Paciente();
        $pacientes = $pacienteModel->listar();
        echo json_encode(['success' => true, 'data' => $pacientes]);
    }

    public function buscarPaciente()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID não informado.']);
            return;
        }

        $pacienteModel = new Paciente();
        $paciente = $pacienteModel->buscarPorId($id);
        if ($paciente) {
            echo json_encode(['success' => true, 'data' => $paciente]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Paciente não encontrado.']);
        }
    }

    public function atualizarPaciente()
    {
        $dados = json_decode(file_get_contents("php://input"), true);
        
        if (!isset($dados['csrf_token'], $dados['id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Dados incompletos.']);
            return;
        }

        if (!CSRFValidador::validar($dados['csrf_token'])) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Token CSRF inválido.']);
            return;
        }

        $pacienteModel = new Paciente();
        if ($pacienteModel->atualizar($dados)) {
            echo json_encode(['success' => true, 'message' => 'Paciente atualizado com sucesso.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro ao atualizar paciente.']);
        }
    }

    public function removerPaciente()
    {
        $dados = json_decode(file_get_contents("php://input"), true);
        
        if (!isset($dados['csrf_token'], $dados['id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Dados incompletos.']);
            return;
        }

        if (!CSRFValidador::validar($dados['csrf_token'])) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Token CSRF inválido.']);
            return;
        }

        $pacienteModel = new Paciente();
        if ($pacienteModel->remover($dados['id'])) {
            echo json_encode(['success' => true, 'message' => 'Paciente removido com sucesso.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro ao remover paciente.']);
        }
    }
}
