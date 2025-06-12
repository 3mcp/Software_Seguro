<?php

require_once __DIR__ . '/../Helpers/Logger.php'; // para arquivos dentro de Controllers

class UsuarioController
{
    private $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = null;
    }

    private function carregarModelo()
    {
        if (!$this->usuarioModel) {
            require_once __DIR__ . '/../../config/config.php';       // conexão $conn
            require_once __DIR__ . '/../Models/Usuario.php';        // classe Usuario
            $this->usuarioModel = new Usuario($conn);
        }
    }

    private function limparEntrada($entrada)
    {
        return htmlspecialchars(trim($entrada));
    }

    public function cadastrar()
    {
        registrarLog("Iniciando processo de cadastro de usuário");
        $this->carregarModelo();

        if (isset($_POST['usuario'], $_POST['senha'], $_POST['confirmar_senha'])) {
            $usuario = $this->limparEntrada($_POST['usuario']);
            $senha = $this->limparEntrada($_POST['senha']);
            $confirmarSenha = $this->limparEntrada($_POST['confirmar_senha']);

            registrarLog("Tentativa de cadastro | Usuário: $usuario");

            if (empty($usuario) || empty($senha) || empty($confirmarSenha)) {
                registrarLog("Erro: Campos obrigatórios ausentes");
                $this->voltarComErro('Todos os campos são obrigatórios.');
                return;
            }

            if ($senha !== $confirmarSenha) {
                registrarLog("Erro: Senhas não coincidem para usuário: $usuario");
                $this->voltarComErro('As senhas não coincidem.');
                return;
            }

            if ($this->usuarioModel->existeUsuario($usuario)) {
                registrarLog("Erro: Nome de usuário já existe: $usuario");
                $this->voltarComErro('Nome de usuário já está em uso. Escolha outro.');
                return;
            }

            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

            if ($this->usuarioModel->criarUsuario($usuario, $senhaHash)) {
                registrarLog("Sucesso: Usuário cadastrado com sucesso: $usuario");
                echo "<script>alert('Usuário cadastrado com sucesso!'); window.location.href = '?pagina=login';</script>";
            } else {
                registrarLog("Erro ao tentar cadastrar usuário no banco: $usuario");
                $this->voltarComErro('Erro ao cadastrar. Tente novamente.');
            }
        } else {
            registrarLog("Erro: Dados do formulário não enviados corretamente");
            $this->voltarComErro('Dados não enviados corretamente.');
        }
    }

    public function autenticar()
    {
        $this->carregarModelo();

        if (isset($_POST['usuario'], $_POST['senha'])) {
            $usuario = $this->limparEntrada($_POST['usuario']);
            $senha = $this->limparEntrada($_POST['senha']);

            registrarLog("Tentativa de login | Usuário: $usuario");

            $usuarioData = $this->usuarioModel->buscarUsuarioPorNome($usuario);

            if ($usuarioData && password_verify($senha, $usuarioData['senha'])) {
                registrarLog("Login bem-sucedido | ID: {$usuarioData['id']} | Usuário: $usuario");
                $_SESSION['usuario_id'] = $usuarioData['id'];
                $_SESSION['usuario'] = $usuarioData['usuario'];
                $_SESSION['is_admin'] = (bool) $usuarioData['is_admin'];

                header("Location: index.php?pagina=dashboard");
                exit;
            } else {
                registrarLog("Erro de autenticação: Usuário ou senha inválidos | Usuário: $usuario");
                echo "<script>alert('Usuário ou senha inválidos'); window.history.back();</script>";
            }
        }
    }

    private function voltarComErro($msg)
    {
        echo "<script>alert('{$msg}'); window.history.back();</script>";
    }
}
