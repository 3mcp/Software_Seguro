<?php
class Especialidade {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function listarTodas() {
        $sql = "SELECT * FROM especialidades";
        $resultado = $this->conn->query($sql);

        $especialidades = [];
        while ($linha = $resultado->fetch_assoc()) {
            $especialidades[] = $linha;
        }

        return $especialidades;
    }

    public function cadastrar($nome) {
        $stmt = $this->conn->prepare("INSERT INTO especialidades (nome) VALUES (?)");
        $stmt->bind_param("s", $nome);
        $resultado = $stmt->execute();
        $stmt->close();
        return $resultado;
    }

    public function excluir($id) {
    $stmt = $this->conn->prepare("DELETE FROM especialidades WHERE id = ?");
    $stmt->bind_param("i", $id);
    $resultado = $stmt->execute();
    $stmt->close();
    return $resultado;
}
}
