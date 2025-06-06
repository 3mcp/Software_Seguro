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

    public function buscarConsultaPorId($id)
    {
        $sql = "SELECT 
                c.id, 
                c.dataHora, 
                c.pacienteId, 
                c.medicoId, 
                c.especialidadeId
            FROM consultas c
            WHERE c.id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $consulta = $resultado->fetch_assoc();
        $stmt->close();

        if ($consulta) {
            // Buscar nome do paciente
            $stmt = $this->conn->prepare("SELECT nome FROM pacientes WHERE id = ?");
            $stmt->bind_param("i", $consulta['pacienteId']);
            $stmt->execute();
            $stmt->bind_result($pacienteNome);
            $stmt->fetch();
            $consulta['paciente'] = $pacientenNome;
            $stmt->close();

            // Buscar nome do médico
            $stmt = $this->conn->prepare("SELECT nome FROM medicos WHERE id = ?");
            $stmt->bind_param("i", $consulta['medicoId']);
            $stmt->execute();
            $stmt->bind_result($medicoNome);
            $stmt->fetch();
            $consulta['medico'] = $medicoNome;
            $stmt->close();

            // Buscar nome da especialidade
            $stmt = $this->conn->prepare("SELECT nome FROM especialidades WHERE id = ?");
            $stmt->bind_param("i", $consulta['especialidadeId']);
            $stmt->execute();
            $stmt->bind_result($espNome);
            $stmt->fetch();
            $consulta['especialidade'] = $espNome;
            $stmt->close();
        }

        return $consulta;
    }
}

