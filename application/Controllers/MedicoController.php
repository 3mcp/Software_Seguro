<?php

namespace App\Controllers;

use App\Models\Medico;
use App\Utils\CSRFValidador;

class MedicoController
{
    public function cadastrarMedico()
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

        $medicoModel = new Medico();
        if ($medicoModel->inserir($dados)) {
            echo json_encode(['success' => true, 'message' => 'Médico cadastrado com sucesso.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro ao cadastrar médico.']);
        }
    }

    public function listarMedicos()
    {
        $medicoModel = new Medico();
        $medicos = $medicoModel->listar();
        echo json_encode(['success' => true, 'data' => $medicos]);
    }

    public function buscarMedico()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID não informado.']);
            return;
        }

        $medicoModel = new Medico();
        $medico = $medicoModel->buscarPorId($id);
        if ($medico) {
            echo json_encode(['success' => true, 'data' => $medico]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Médico não encontrado.']);
        }
    }

    public function atualizarMedico()
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

        $medicoModel = new Medico();
        if ($medicoModel->atualizar($dados)) {
            echo json_encode(['success' => true, 'message' => 'Médico atualizado com sucesso.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro ao atualizar médico.']);
        }
    }

    public function removerMedico()
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

        $medicoModel = new Medico();
        if ($medicoModel->remover($dados['id'])) {
            echo json_encode(['success' => true, 'message' => 'Médico removido com sucesso.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro ao remover médico.']);
        }
    }
}
