<?php
class Paciente {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function listarTodos() {
        $sql = "SELECT * FROM pacientes";
        $resultado = $this->conn->query($sql);
        $pacientes = [];

        while ($linha = $resultado->fetch_assoc()) {
            $pacientes[] = $linha;
        }

        return $pacientes;
    }

    public function buscarPorId($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM pacientes WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $paciente = $resultado->fetch_assoc();
        $stmt->close();
        return $paciente;
    }

    public function cadastrar($nome, $email, $telefone, $dataNascimento) {
        $stmt = $this->conn->prepare("INSERT INTO pacientes (nome, email, telefone, dataNascimento) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nome, $email, $telefone, $dataNascimento);
        $resultado = $stmt->execute();
        $stmt->close();
        return $resultado;
    }
}
