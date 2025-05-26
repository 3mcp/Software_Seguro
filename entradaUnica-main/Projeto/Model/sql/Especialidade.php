<?php
class Especialidade {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function listar() {
        $result = $this->conn->query("SELECT * FROM especialidades ORDER BY nome");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function criar($dados) {
        $stmt = $this->conn->prepare("INSERT INTO especialidades (nome) VALUES (?)");
        $stmt->bind_param("s", $dados['nome']);
        return $stmt->execute();
    }
}
