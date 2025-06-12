<?php
namespace App\Models;

require_once __DIR__ . '/../../config/config.php';

use mysqli;
use Exception;

class Especialidade
{
    private $conn;

    public function __construct()
    {
        $this->conn = $GLOBALS['conn'];
    }

    public function listarEspecialidades()
    {
        $sql = "SELECT * FROM especialidades";
        return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function buscarPorId($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM especialidades WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function cadastrarEspecialidade($nome)
    {
        $stmt = $this->conn->prepare("INSERT INTO especialidades (nome) VALUES (?)");
        $stmt->bind_param("s", $nome);
        return $stmt->execute();
    }

    public function atualizarEspecialidade($id, $nome)
    {
        $stmt = $this->conn->prepare("UPDATE especialidades SET nome = ? WHERE id = ?");
        $stmt->bind_param("si", $nome, $id);
        return $stmt->execute();
    }

    public function removerEspecialidade($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM especialidades WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
