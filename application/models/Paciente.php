<?php

namespace App\Models;

class Paciente
{
    public function inserir($dados)
    {
        $stmt = $GLOBALS['conn']->prepare("INSERT INTO pacientes (nome, cpf, email, telefone, dataNascimento) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $dados['nome'], $dados['cpf'], $dados['email'], $dados['telefone'], $dados['dataNascimento']);
        return $stmt->execute();
    }

    public function listar()
    {
        $result = $GLOBALS['conn']->query("SELECT * FROM pacientes");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function buscarPorId($id)
    {
        $stmt = $GLOBALS['conn']->prepare("SELECT * FROM pacientes WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function atualizar($dados)
    {
        $stmt = $GLOBALS['conn']->prepare("UPDATE pacientes SET nome = ?, cpf = ?, email = ?, telefone = ?, dataNascimento = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $dados['nome'], $dados['cpf'], $dados['email'], $dados['telefone'], $dados['dataNascimento'], $dados['id']);
        return $stmt->execute();
    }

    public function remover($id)
    {
        $stmt = $GLOBALS['conn']->prepare("DELETE FROM pacientes WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
