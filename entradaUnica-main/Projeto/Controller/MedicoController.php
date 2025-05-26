<?php
require_once __DIR__ . '/../models/Medico.php';

class MedicoController {
    private $medico;

    public function __construct($db) {
        $this->medico = new Medico($db);
    }

    public function processarRequisicao($method) {
        switch ($method) {
            case 'GET':
                echo json_encode($this->medico->listar());
                break;
            case 'POST':
                $data = json_decode(file_get_contents("php://input"), true);
                if (!isset($data['nome'], $data['especialidade'], $data['crm'])) {
                    http_response_code(400);
                    echo json_encode(["erro" => "Dados incompletos"]);
                } else if ($this->medico->criar($data)) {
                    echo json_encode(["status" => "médico cadastrado"]);
                } else {
                    http_response_code(500);
                    echo json_encode(["erro" => "Erro ao cadastrar médico"]);
                }
                break;
            default:
                http_response_code(405);
                echo json_encode(["erro" => "Método não permitido"]);
                break;
        }
    }
}