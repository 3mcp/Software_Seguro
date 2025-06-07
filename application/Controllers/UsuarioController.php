<?php

    class UsuarioController {
        private $usuarioModel;

        public function __construct() {
            $this->usuarioModel = null;
        }

        private function carregarModelo() {
            if (!$this->usuarioModel) {
                require_once __DIR__ . '/../../config/config.php';       // conexão $conn
                require_once __DIR__ . '/../Models/Usuario.php';        // classe Usuario
                $this->usuarioModel = new Usuario($conn);
            }
        }

        private function limparEntrada($entrada) {
            return htmlspecialchars(trim($entrada));
        }

        public function cadastrar() {
            echo "entrou na função cadastrar";
            $this->carregarModelo();

            if (isset($_POST['usuario'], $_POST['senha'], $_POST['confirmar_senha'])) {
                $usuario = $this->limparEntrada($_POST['usuario']);
                $senha = $this->limparEntrada($_POST['senha']);
                $confirmarSenha = $this->limparEntrada($_POST['confirmar_senha']);

                if (empty($usuario) || empty($senha) || empty($confirmarSenha)) {
                    $this->voltarComErro('Todos os campos são obrigatórios.');
                    return;
                }

                if ($senha !== $confirmarSenha) {
                    $this->voltarComErro('As senhas não coincidem.');
                    return;
                }

                if ($this->usuarioModel->existeUsuario($usuario)) {
                    $this->voltarComErro('Nome de usuário já está em uso. Escolha outro.');
                    return;
                }

                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

                if ($this->usuarioModel->criarUsuario($usuario, $senhaHash)) {
                    echo "<script>alert('Usuário cadastrado com sucesso!'); window.location.href = '?pagina=login';</script>";
                } else {
                    $this->voltarComErro('Erro ao cadastrar. Tente novamente.');
                }

            } else {
                $this->voltarComErro('Dados não enviados corretamente.');
            }
        }

        public function autenticar() {
            $this->carregarModelo();

            if (isset($_POST['usuario'], $_POST['senha'])) {
                $usuario = $this->limparEntrada($_POST['usuario']);
                $senha = $this->limparEntrada($_POST['senha']);

                $usuarioData = $this->usuarioModel->buscarUsuarioPorNome($usuario);

                if ($usuarioData && password_verify($senha, $usuarioData['senha'])) {
                    $_SESSION['usuario_id'] = $usuarioData['id'];
                    $_SESSION['usuario'] = $usuarioData['usuario'];
                    $_SESSION['is_admin'] = (bool) $usuarioData['is_admin']; // <- ESSENCIAL

                    header("Location: index.php?pagina=dashboard");
                    exit;
                } else {
                    echo "<script>alert('Usuário ou senha inválidos'); window.history.back();</script>";
                }
            }
        }


        private function voltarComErro($msg) {
            echo "<script>alert('{$msg}'); window.history.back();</script>";
        }
    }
?>
