<?php

namespace App\Controllers;

require_once __DIR__ . '/../models/Especialidade.php';
require_once __DIR__ . '/../../utils/sessao.php';
require_once __DIR__ . '/../../utils/logger.php';

use App\Models\Especialidade;
use App\Utils\Logger;

class EspecialidadeController
{
    private $model;

    public function __construct()
    {
        $this->model = new Especialidade();
    }

    public function listarEspecialidades()
    {
        try {
            header('Content-Type: application/json');
            $dados = $this->model->listarEspecialidades();
            echo json_encode(['success' => true, 'data' => $dados]);
        } catch (\Exception $e) {
            Logger::logErro($e);
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro ao listar especialidades.']);
        }
    }
}
