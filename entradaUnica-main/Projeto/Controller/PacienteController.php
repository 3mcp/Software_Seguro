<?php
require_once __DIR__ . '/../models/Paciente.php';

class PacienteController {
    private $paciente;

    public function __construct($db) {
        $this->paciente = new Paciente($db);
    }

    public function processarRequisicao($method) {
        switch ($method) {
            case 'GET':
                echo json_encode($this->paciente->listar());
                break;
            case 'POST':
                $data = json_decode(file_get_contents("php://input"), true);
                if (!$data || !isset($data['nome'])) {
                    http_response_code(400);
                    echo json_encode(["erro" => "Dados incompletos"]);
                } else if ($this->paciente->criar($data)) {
                    echo json_encode(["status" => "cadastrado"]);
                } else {
                    http_response_code(500);
                    echo json_encode(["erro" => "Erro ao cadastrar paciente"]);
                }
                break;
            default:
                http_response_code(405);
                echo json_encode(["erro" => "Método não permitido"]);
                break;
        }
    }
}
?>