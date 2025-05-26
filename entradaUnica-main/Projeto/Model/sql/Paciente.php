<?php
class Paciente {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function listar() {
        $result = $this->conn->query("SELECT * FROM pacientes ORDER BY nome");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function criar($dados) {
        $stmt = $this->conn->prepare("INSERT INTO pacientes (nome, email, telefone, dataNascimento) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $dados['nome'], $dados['email'], $dados['telefone'], $dados['dataNascimento']);
        return $stmt->execute();
    }
}
?>