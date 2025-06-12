<?php

namespace App\Controllers;

use App\Models\Usuario;
use App\Utils\CSRFValidador;
use App\Utils\VerificaSessao;

class LoginController
{
    public function autenticar()
    {
        $dados = json_decode(file_get_contents("php://input"), true);

        if (!isset($dados['usuario'], $dados['senha'], $dados['csrf_token'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Dados incompletos.']);
            return;
        }

        if (!CSRFValidador::validar($dados['csrf_token'])) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Token CSRF inválido.']);
            return;
        }

        $usuarioModel = new Usuario();
        $usuario = $usuarioModel->buscarPorUsuario($dados['usuario']);

        if ($usuario && hash('sha256', $dados['senha']) === $usuario['senha']) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['usuario_id'] = $usuario['id'];
            echo json_encode(['success' => true, 'message' => 'Login realizado com sucesso.']);
        } else {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Usuário ou senha inválidos.']);
        }
    }
}
