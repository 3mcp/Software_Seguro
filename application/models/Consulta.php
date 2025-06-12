<?php
namespace App\Models;

require_once __DIR__ . '/../../config/config.php';

class Consulta
{
    private $conn;

    public function __construct()
    {
        $this->conn = $GLOBALS['conn'];
    }

    public function listarConsultas()
    {
        $sql = "SELECT 
                    c.id, 
                    c.pacienteId, p.nome AS nomePaciente,
                    c.medicoId, m.nome AS nomeMedico,
                    c.especialidadeId, e.nome AS nomeEspecialidade,
                    c.dataHora 
                FROM consultas c
                JOIN pacientes p ON c.pacienteId = p.id
                JOIN medicos m ON c.medicoId = m.id
                JOIN especialidades e ON c.especialidadeId = e.id";

        $result = $this->conn->query($sql);
        $consultas = [];

        while ($row = $result->fetch_assoc()) {
            $consultas[] = $row;
        }

        return $consultas;
    }

    public function buscarConsultaPorId($id)
    {
        $stmt = $this->conn->prepare(
            "SELECT 
                c.id, c.pacienteId, p.nome AS nomePaciente,
                c.medicoId, m.nome AS nomeMedico,
                c.especialidadeId, e.nome AS nomeEspecialidade,
                c.dataHora 
            FROM consultas c
            JOIN pacientes p ON c.pacienteId = p.id
            JOIN medicos m ON c.medicoId = m.id
            JOIN especialidades e ON c.especialidadeId = e.id
            WHERE c.id = ?"
        );

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $resultado;
    }

    public function cadastrarConsulta($pacienteId, $medicoId, $especialidadeId, $dataHora)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO consultas (pacienteId, medicoId, especialidadeId, dataHora) 
             VALUES (?, ?, ?, ?)"
        );

        $stmt->bind_param("iiis", $pacienteId, $medicoId, $especialidadeId, $dataHora);
        $resultado = $stmt->execute();
        $stmt->close();
        return $resultado;
    }

    public function atualizarConsulta($id, $pacienteId, $medicoId, $especialidadeId, $dataHora)
    {
        $stmt = $this->conn->prepare(
            "UPDATE consultas 
             SET pacienteId = ?, medicoId = ?, especialidadeId = ?, dataHora = ?
             WHERE id = ?"
        );

        $stmt->bind_param("iiisi", $pacienteId, $medicoId, $especialidadeId, $dataHora, $id);
        $resultado = $stmt->execute();
        $stmt->close();
        return $resultado;
    }

    public function removerConsulta($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM consultas WHERE id = ?");
        $stmt->bind_param("i", $id);
        $resultado = $stmt->execute();
        $stmt->close();
        return $resultado;
    }
}
