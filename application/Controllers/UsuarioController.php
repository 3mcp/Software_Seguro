<?php

require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../../utils/csrf_validador.php';
require_once __DIR__ . '/../../utils/sessao.php';

class UsuarioController
{
    private $model;

    public function __construct()
    {
        $this->model = new Usuario();
    }

    public function cadastrarUsuario()
    {
        header('Content-Type: application/json');

        $csrf_token = $_POST['csrf_token'] ?? '';
        if (!valida_csrf($csrf_token)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Token CSRF inválido']);
            exit;
        }

        $usuario = trim($_POST['usuario'] ?? '');
        $senhaHash = trim($_POST['senha'] ?? '');
        $confirmarSenhaHash = trim($_POST['confirmar_senha'] ?? '');

        if (!$usuario || !$senhaHash || !$confirmarSenhaHash) {
            echo json_encode(['success' => false, 'message' => 'Preencha todos os campos.']);
            return;
        }

        if ($senhaHash !== $confirmarSenhaHash) {
            echo json_encode(['success' => false, 'message' => 'As senhas não coincidem.']);
            return;
        }

        try {
            $usuarioExistente = $this->model->buscarPorUsuario($usuario);
            if ($usuarioExistente) {
                echo json_encode(['success' => false, 'message' => 'Usuário já existe.']);
                return;
            }

            $sucesso = $this->model->inserir($usuario, $senhaHash);

            if ($sucesso) {
                echo json_encode(['success' => true, 'message' => 'Usuário cadastrado com sucesso.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Falha ao cadastrar usuário.']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro interno: ' . $e->getMessage()]);
        }
    }
}
?>
