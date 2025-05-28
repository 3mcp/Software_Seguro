<?php
class Usuario {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function existeUsuario($usuario) {
        $stmt = $this->conn->prepare("SELECT id FROM usuarios WHERE usuario = ?");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $stmt->store_result();
        $existe = $stmt->num_rows > 0;
        $stmt->close();
        return $existe;
    }

    public function criarUsuario($usuario, $senhaHash) {
        $stmt = $this->conn->prepare("INSERT INTO usuarios (usuario, senha) VALUES (?, ?)");
        $stmt->bind_param("ss", $usuario, $senhaHash);
        $sucesso = $stmt->execute();
        $stmt->close();
        return $sucesso;
    }
}
