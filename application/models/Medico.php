<?php
class Medico {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function listarTodosComEspecialidade() {
        $sql = "SELECT m.id, m.nome, m.crm, e.nome AS especialidade
                FROM medicos m
                LEFT JOIN especialidades e ON m.especialidadeId = e.id";
        $resultado = $this->conn->query($sql);

        $medicos = [];
        while ($linha = $resultado->fetch_assoc()) {
            $medicos[] = $linha;
        }

        return $medicos;
    }

     public function cadastrar($nome, $crm, $especialidadeId) {
        $stmt = $this->conn->prepare("INSERT INTO medicos (nome, crm, especialidadeId) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $nome, $crm, $especialidadeId);
        $resultado = $stmt->execute();
        $stmt->close();
        return $resultado;
    }
}
