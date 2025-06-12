<?php
namespace App\Controllers;

use App\Models\Consulta;

require_once __DIR__ . '/../models/Consulta.php';
require_once __DIR__ . '/../../utils/verificaSessao.php';
require_once __DIR__ . '/../../utils/csrf_validador.php';

class ConsultaController
{
    private $model;

    public function __construct()
    {
        $this->model = new Consulta();
    }

    public function listarConsultas()
    {
        header('Content-Type: application/json');
        try {
            $consultas = $this->model->listarConsultas();
            echo json_encode(['success' => true, 'data' => $consultas]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro ao listar consultas']);
        }
    }

    public function buscarConsulta()
    {
        header('Content-Type: application/json');
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID inválido']);
            return;
        }

        $consulta = $this->model->buscarConsultaPorId($id);
        if (!$consulta) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Consulta não encontrada']);
            return;
        }

        echo json_encode(['success' => true, 'data' => $consulta]);
    }

    public function cadastrarConsulta()
    {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input || !valida_csrf($input['csrf_token'] ?? '')) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Token CSRF inválido']);
            return;
        }

        $pacienteId = filter_var($input['pacienteId'] ?? null, FILTER_VALIDATE_INT);
        $medicoId = filter_var($input['medicoId'] ?? null, FILTER_VALIDATE_INT);
        $especialidadeId = filter_var($input['especialidadeId'] ?? null, FILTER_VALIDATE_INT);
        $dataHora = trim($input['dataHora'] ?? '');

        if (!$pacienteId || !$medicoId || !$especialidadeId || !$dataHora) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Todos os campos são obrigatórios']);
            return;
        }

        try {
            $this->model->cadastrarConsulta($pacienteId, $medicoId, $especialidadeId, $dataHora);
            echo json_encode(['success' => true, 'message' => 'Consulta cadastrada com sucesso']);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro ao cadastrar consulta']);
        }
    }

    public function atualizarConsulta()
    {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input || !valida_csrf($input['csrf_token'] ?? '')) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Token CSRF inválido']);
            return;
        }

        $id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);
        $pacienteId = filter_var($input['pacienteId'] ?? null, FILTER_VALIDATE_INT);
        $medicoId = filter_var($input['medicoId'] ?? null, FILTER_VALIDATE_INT);
        $especialidadeId = filter_var($input['especialidadeId'] ?? null, FILTER_VALIDATE_INT);
        $dataHora = trim($input['dataHora'] ?? '');

        if (!$id || !$pacienteId || !$medicoId || !$especialidadeId || !$dataHora) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Todos os campos são obrigatórios']);
            return;
        }

        try {
            $this->model->atualizarConsulta($id, $pacienteId, $medicoId, $especialidadeId, $dataHora);
            echo json_encode(['success' => true, 'message' => 'Consulta atualizada com sucesso']);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro ao atualizar consulta']);
        }
    }

    public function removerConsulta()
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
            $this->model->removerConsulta($id);
            echo json_encode(['success' => true, 'message' => 'Consulta removida com sucesso']);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro ao remover consulta']);
        }
    }
}
