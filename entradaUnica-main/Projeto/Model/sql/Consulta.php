<?php
class Consulta {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function listar() {
        $result = $this->conn->query("
            SELECT c.id, p.nome AS paciente, m.nome AS medico, c.data, c.hora
            FROM consultas c
            JOIN pacientes p ON c.paciente_id = p.id
            JOIN medicos m ON c.medico_id = m.id
            ORDER BY c.data, c.hora
        ");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function criar($dados) {
        $stmt = $this->conn->prepare("INSERT INTO consultas (paciente_id, medico_id, data, hora) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $dados['paciente_id'], $dados['medico_id'], $dados['data'], $dados['hora']);
        return $stmt->execute();
    }
}
?>
