<?php
require_once __DIR__ . '/../models/Usuario.php';

class UsuarioController {
    private $usuario;

    public function __construct($db) {
        $this->usuario = new Usuario($db);
    }

    public function processarRequisicao($method) {
        $data = json_decode(file_get_contents("php://input"), true);

        switch ($_GET['action'] ?? '') {
            case 'login':
                if (!isset($data['email']) || !isset($data['senha'])) {
                    http_response_code(400);
                    echo json_encode(["erro" => "Email e senha obrigatórios"]);
                } else {
                    $usuario = $this->usuario->autenticar($data['email'], $data['senha']);
                    if ($usuario) {
                        echo json_encode(["status" => "autenticado", "usuario" => $usuario]);
                    } else {
                        http_response_code(401);
                        echo json_encode(["erro" => "Credenciais inválidas"]);
                    }
                }
                break;
            case 'register':
                if (!isset($data['nome'], $data['email'], $data['senha'])) {
                    http_response_code(400);
                    echo json_encode(["erro" => "Dados incompletos"]);
                } else if ($this->usuario->criar($data)) {
                    echo json_encode(["status" => "usuario cadastrado"]);
                } else {
                    http_response_code(500);
                    echo json_encode(["erro" => "Erro ao cadastrar usuário"]);
                }
                break;
            default:
                http_response_code(400);
                echo json_encode(["erro" => "Ação não especificada"]);
        }
    }
}
?>