<?php

require_once __DIR__ . '/../../config/config.php';

class Usuario
{
    private $conn;

    public function __construct()
    {
        $this->conn = $GLOBALS['conn'];
        if ($this->conn->connect_error) {
            throw new Exception("Falha na conexão com o banco de dados: " . $this->conn->connect_error);
        }
    }

    public function inserir($usuario, $senhaHash)
    {
        $stmt = $this->conn->prepare("INSERT INTO usuarios (usuario, senha) VALUES (?, ?)");
        if (!$stmt) {
            throw new Exception("Erro ao preparar statement: " . $this->conn->error);
        }
        $stmt->bind_param("ss", $usuario, $senhaHash);
        $sucesso = $stmt->execute();
        $stmt->close();
        return $sucesso;
    }

    public function buscarPorUsuario($usuario)
    {
        $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE usuario = ?");
        if (!$stmt) {
            throw new Exception("Erro ao preparar statement: " . $this->conn->error);
        }
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $dados = $resultado->fetch_assoc();
        $stmt->close();
        return $dados;
    }
}
?>
