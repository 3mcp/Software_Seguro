<?php
require_once __DIR__ . '/../models/Especialidade.php';

class EspecialidadeController {
    private $especialidade;

    public function __construct($db) {
        $this->especialidade = new Especialidade($db);
    }

    public function processarRequisicao($method) {
        switch ($method) {
            case 'GET':
                echo json_encode($this->especialidade->listar());
                break;
            case 'POST':
                $data = json_decode(file_get_contents("php://input"), true);
                if (!$data || !isset($data['nome'])) {
                    http_response_code(400);
                    echo json_encode(["erro" => "Nome da especialidade é obrigatório"]);
                } else if ($this->especialidade->criar($data)) {
                    echo json_encode(["status" => "cadastrado"]);
                } else {
                    http_response_code(500);
                    echo json_encode(["erro" => "Erro ao cadastrar especialidade"]);
                }
                break;
            default:
                http_response_code(405);
                echo json_encode(["erro" => "Método não permitido"]);
        }
    }
}
