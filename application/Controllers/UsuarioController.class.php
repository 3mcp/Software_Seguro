<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/config.php';



class UsuarioController {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario($GLOBALS['conn']);
    }

    private function limparEntrada($entrada) {
        return htmlspecialchars(trim($entrada));
    }

    public function cadastrar() {
        if (isset($_POST['usuario'], $_POST['senha'], $_POST['confirmar_senha'])) {
            $usuario = $this->limparEntrada($_POST['usuario']);
            $senha = $this->limparEntrada($_POST['senha']);
            $confirmarSenha = $this->limparEntrada($_POST['confirmar_senha']);

            if ($senha !== $confirmarSenha) {
                $this->voltarComErro('As senhas não coincidem.');
                return;
            }

            if ($this->usuarioModel->existeUsuario($usuario)) {
                $this->voltarComErro('Usuário já existe.');
                return;
            }

            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            if ($this->usuarioModel->criarUsuario($usuario, $senhaHash)) {
                echo "<script>alert('Cadastro realizado!'); window.location.href='?pagina=login';</script>";
            } else {
                $this->voltarComErro('Erro ao cadastrar.');
            }
        } else {
            $this->voltarComErro('Campos incompletos.');
        }
    }

    public function autenticar() {
        if (isset($_POST['usuario'], $_POST['senha'])) {
            $usuario = $this->limparEntrada($_POST['usuario']);
            $senha = $this->limparEntrada($_POST['senha']);

            $usuarioData = $this->usuarioModel->buscarUsuarioPorNome($usuario);
            if ($usuarioData && password_verify($senha, $usuarioData['senha'])) {
                $_SESSION['usuario_id'] = $usuarioData['id'];
                $_SESSION['usuario'] = $usuarioData['usuario'];
                header("Location: index.php?pagina=dashboard");
                exit;
            } else {
                echo "<script>alert('Login inválido!'); window.history.back();</script>";
            }
        }
    }

    private function voltarComErro($msg) {
        echo "<script>alert('{$msg}'); window.history.back();</script>";
    }
}
?>
