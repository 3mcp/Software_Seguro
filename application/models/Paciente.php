<?php
namespace App\Models;

require_once __DIR__ . '/../../config/config.php';

use mysqli;
use Exception;

class Paciente
{
    private $conn;

    public function __construct()
    {
        $this->conn = $GLOBALS['conn'];
    }

    public function listarPacientes()
    {
        $sql = "SELECT * FROM pacientes";
        return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function buscarPacientePorId($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM pacientes WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function cadastrarPaciente($nome, $cpf, $email, $telefone, $dataNascimento)
    {
        $stmt = $this->conn->prepare("INSERT INTO pacientes (nome, cpf, email, telefone, dataNascimento) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nome, $cpf, $email, $telefone, $dataNascimento);
        return $stmt->execute();
    }

    public function atualizarPaciente($id, $nome, $cpf, $email, $telefone, $dataNascimento)
    {
        $stmt = $this->conn->prepare("UPDATE pacientes SET nome=?, cpf=?, email=?, telefone=?, dataNascimento=? WHERE id=?");
        $stmt->bind_param("sssssi", $nome, $cpf, $email, $telefone, $dataNascimento, $id);
        return $stmt->execute();
    }

    public function removerPaciente($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM pacientes WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
