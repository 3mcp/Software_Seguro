<?php

namespace App\Controllers;

use App\Models\Consulta;
use App\Utils\CSRFValidador;


class ConsultaController
{
    public function cadastrarConsulta()
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

        $consultaModel = new Consulta();
        if ($consultaModel->inserir($dados)) {
            echo json_encode(['success' => true, 'message' => 'Consulta cadastrada com sucesso.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro ao cadastrar consulta.']);
        }
    }

    public function listarConsultas()
    {
        $consultaModel = new Consulta();
        $consultas = $consultaModel->listar();
        echo json_encode(['success' => true, 'data' => $consultas]);
    }

    public function buscarConsulta()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID não informado.']);
            return;
        }

        $consultaModel = new Consulta();
        $consulta = $consultaModel->buscarPorId($id);
        if ($consulta) {
            echo json_encode(['success' => true, 'data' => $consulta]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Consulta não encontrada.']);
        }
    }

    public function atualizarConsulta()
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

        $consultaModel = new Consulta();
        if ($consultaModel->atualizar($dados)) {
            echo json_encode(['success' => true, 'message' => 'Consulta atualizada com sucesso.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro ao atualizar consulta.']);
        }
    }

    public function removerConsulta()
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

        $consultaModel = new Consulta();
        if ($consultaModel->remover($dados['id'])) {
            echo json_encode(['success' => true, 'message' => 'Consulta removida com sucesso.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro ao remover consulta.']);
        }
    }
}
