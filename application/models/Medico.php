<?php
namespace App\Models;

require_once __DIR__ . '/../../config/config.php';

use mysqli;
use Exception;

class Medico
{
    private $conn;

    public function __construct()
    {
        $this->conn = $GLOBALS['conn'];
    }

    public function listarMedicos()
    {
        $sql = "SELECT m.id, m.nome, m.crm, e.nome AS nomeEspecialidade
                FROM medicos m
                INNER JOIN especialidades e ON m.especialidadeId = e.id";
        return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function buscarMedicoPorId($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM medicos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function cadastrarMedico($nome, $crm, $especialidadeId)
    {
        $stmt = $this->conn->prepare("INSERT INTO medicos (nome, crm, especialidadeId) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $nome, $crm, $especialidadeId);
        return $stmt->execute();
    }

    public function atualizarMedico($id, $nome, $crm, $especialidadeId)
    {
        $stmt = $this->conn->prepare("UPDATE medicos SET nome=?, crm=?, especialidadeId=? WHERE id=?");
        $stmt->bind_param("ssii", $nome, $crm, $especialidadeId, $id);
        return $stmt->execute();
    }

    public function removerMedico($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM medicos WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
