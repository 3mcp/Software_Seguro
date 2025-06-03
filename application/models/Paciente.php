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
}