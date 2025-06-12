<?php

namespace App\Controllers;

use App\Models\Especialidade;
use App\Utils\CSRFValidador;

class EspecialidadeController
{
    public function cadastrarEspecialidade()
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

        $especialidadeModel = new Especialidade();
        if ($especialidadeModel->inserir($dados)) {
            echo json_encode(['success' => true, 'message' => 'Especialidade cadastrada com sucesso.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro ao cadastrar especialidade.']);
        }
    }

    public function listarEspecialidades()
    {
        $especialidadeModel = new Especialidade();
        $especialidades = $especialidadeModel->listar();
        echo json_encode(['success' => true, 'data' => $especialidades]);
    }

    public function buscarEspecialidade()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID não informado.']);
            return;
        }

        $especialidadeModel = new Especialidade();
        $especialidade = $especialidadeModel->buscarPorId($id);
        if ($especialidade) {
            echo json_encode(['success' => true, 'data' => $especialidade]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Especialidade não encontrada.']);
        }
    }

    public function atualizarEspecialidade()
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

        $especialidadeModel = new Especialidade();
        if ($especialidadeModel->atualizar($dados)) {
            echo json_encode(['success' => true, 'message' => 'Especialidade atualizada com sucesso.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro ao atualizar especialidade.']);
        }
    }

    public function removerEspecialidade()
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

        $especialidadeModel = new Especialidade();
        if ($especialidadeModel->remover($dados['id'])) {
            echo json_encode(['success' => true, 'message' => 'Especialidade removida com sucesso.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro ao remover especialidade.']);
        }
    }
}
