<?php

require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../../utils/csrf_validador.php';
require_once __DIR__ . '/../../utils/sessao.php';

class LoginController
{
    private $model;

    public function __construct()
    {
        $this->model = new Usuario();
    }

    public function autenticar()
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

        if (!$usuario || !$senhaHash) {
            echo json_encode(['success' => false, 'message' => 'Preencha usuário e senha.']);
            return;
        }

        try {
            $usuarioData = $this->model->buscarPorUsuario($usuario);

            if (!$usuarioData) {
                echo json_encode(['success' => false, 'message' => 'Credenciais inválidas.']);
                return;
            }

            if ($senhaHash === $usuarioData['senha']) {
                // Login válido -> cria sessão
                session_regenerate_id(true);
                $_SESSION['usuario_id'] = $usuarioData['id'];
                $_SESSION['usuario'] = $usuarioData['usuario'];
                $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
                $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                $_SESSION['ultimo_ativo'] = time();

                echo json_encode(['success' => true, 'message' => 'Login realizado com sucesso']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Credenciais inválidas.']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro interno: ' . $e->getMessage()]);
        }
    }
}
?>
