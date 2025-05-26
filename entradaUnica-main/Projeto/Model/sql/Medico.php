<?php
class Medico {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function listar() {
        $result = $this->conn->query("SELECT * FROM medicos ORDER BY nome");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function criar($dados) {
        $stmt = $this->conn->prepare("INSERT INTO medicos (nome, especialidade, crm) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $dados['nome'], $dados['especialidade'], $dados['crm']);
        return $stmt->execute();
    }
}
?>