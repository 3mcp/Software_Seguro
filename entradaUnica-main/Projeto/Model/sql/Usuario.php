<?php
class Usuario {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function criar($dados) {
        $stmt = $this->conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
        $senhaHash = password_hash($dados['senha'], PASSWORD_BCRYPT);
        $stmt->bind_param("sss", $dados['nome'], $dados['email'], $senhaHash);
        return $stmt->execute();
    }

    public function autenticar($email, $senha) {
        $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        if ($resultado && password_verify($senha, $resultado['senha'])) {
            return $resultado;
        }
        return false;
    }
}
?>