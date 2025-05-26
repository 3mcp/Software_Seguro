<?php
require_once __DIR__ . '/../models/Consulta.php';

class ConsultaController {
    private $consulta;

    public function __construct($db) {
        $this->consulta = new Consulta($db);
    }

    public function processarRequisicao($method) {
        switch ($method) {
            case 'GET':
                echo json_encode($this->consulta->listar());
                break;
            case 'POST':
                $data = json_decode(file_get_contents("php://input"), true);
                if (!isset($data['paciente_id'], $data['medico_id'], $data['data'], $data['hora'])) {
                    http_response_code(400);
                    echo json_encode(["erro" => "Dados incompletos"]);
                } else if ($this->consulta->criar($data)) {
                    echo json_encode(["status" => "consulta agendada"]);
                } else {
                    http_response_code(500);
                    echo json_encode(["erro" => "Erro ao agendar consulta"]);
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
