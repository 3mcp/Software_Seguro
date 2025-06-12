<?php
namespace App\Controllers;

use App\Models\Medico;

require_once __DIR__ . '/../models/Medico.php';
require_once __DIR__ . '/../../utils/verificaSessao.php';
require_once __DIR__ . '/../../utils/csrf_validador.php';

class MedicoController
{
    private $model;

    public function __construct()
    {
        $this->model = new Medico();
    }

    public function listarMedicos()
    {
        header('Content-Type: application/json');
        try {
            $medicos = $this->model->listarMedicos();
            echo json_encode(['success' => true, 'data' => $medicos]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro ao listar médicos']);
        }
    }

    public function buscarMedico()
    {
        header('Content-Type: application/json');
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID inválido']);
            return;
        }

        $medico = $this->model->buscarMedicoPorId($id);
        if (!$medico) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Médico não encontrado']);
            return;
        }

        echo json_encode(['success' => true, 'data' => $medico]);
    }

    public function cadastrarMedico()
    {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input || !valida_csrf($input['csrf_token'] ?? '')) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Token CSRF inválido']);
            return;
        }

        $nome = trim($input['nome'] ?? '');
        $crm = trim($input['crm'] ?? '');
        $especialidadeId = filter_var($input['especialidadeId'] ?? null, FILTER_VALIDATE_INT);

        if (!$nome || !$crm || !$especialidadeId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Todos os campos são obrigatórios']);
            return;
        }

        try {
            $this->model->cadastrarMedico($nome, $crm, $especialidadeId);
            echo json_encode(['success' => true, 'message' => 'Médico cadastrado com sucesso']);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro ao cadastrar médico']);
        }
    }

    public function atualizarMedico()
    {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input || !valida_csrf($input['csrf_token'] ?? '')) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Token CSRF inválido']);
            return;
        }

        $id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);
        $nome = trim($input['nome'] ?? '');
        $crm = trim($input['crm'] ?? '');
        $especialidadeId = filter_var($input['especialidadeId'] ?? null, FILTER_VALIDATE_INT);

        if (!$id || !$nome || !$crm || !$especialidadeId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Todos os campos são obrigatórios']);
            return;
        }

        try {
            $this->model->atualizarMedico($id, $nome, $crm, $especialidadeId);
            echo json_encode(['success' => true, 'message' => 'Médico atualizado com sucesso']);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro ao atualizar médico']);
        }
    }

    public function removerMedico()
    {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input || !valida_csrf($input['csrf_token'] ?? '')) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Token CSRF inválido']);
            return;
        }

        $id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);
        if (!$id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID inválido']);
            return;
        }

        try {
            $this->model->removerMedico($id);
            echo json_encode(['success' => true, 'message' => 'Médico removido com sucesso']);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro ao remover médico']);
        }
    }
}
