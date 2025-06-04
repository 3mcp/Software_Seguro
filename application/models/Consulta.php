<?php
class Consulta {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function criarConsulta($pacienteId, $medicoId, $especialidadeId, $dataHora) {
        $stmt = $this->conn->prepare("
            INSERT INTO consultas (pacienteId, medicoId, especialidadeId, dataHora)
            VALUES (?, ?, ?, ?)
        ");

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("iiis", $pacienteId, $medicoId, $especialidadeId, $dataHora);
        $resultado = $stmt->execute();
        $stmt->close();
        return $resultado;
    }

    public function listarConsultas() {
        $sql = "
            SELECT 
                c.id,
                p.nome AS paciente,
                m.nome AS medico,
                e.nome AS especialidade,
                c.dataHora
            FROM consultas c
            JOIN pacientes p ON c.pacienteId = p.id
            JOIN medicos m ON c.medicoId = m.id
            JOIN especialidades e ON c.especialidadeId = e.id
            ORDER BY c.dataHora DESC
        ";

        $result = $this->conn->query($sql);

        $consultas = [];
        while ($row = $result->fetch_assoc()) {
            $consultas[] = $row;
        }

        return $consultas;
    }
}

