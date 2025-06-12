<?php

namespace App\Controllers;

use App\Models\Usuario;
use App\Utils\CSRFValidador;
use Utils\VerificaSessao;

class UsuarioController
{
    public function cadastrarUsuario()
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
        $usuarioExistente = $usuarioModel->buscarPorUsuario($dados['usuario']);

        if ($usuarioExistente) {
            http_response_code(409);
            echo json_encode(['success' => false, 'message' => 'Usuário já existe.']);
            return;
        }

        if ($usuarioModel->inserir($dados['usuario'], $dados['senha'])) {
            echo json_encode(['success' => true, 'message' => 'Usuário cadastrado com sucesso.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro ao cadastrar usuário.']);
        }
    }
}
