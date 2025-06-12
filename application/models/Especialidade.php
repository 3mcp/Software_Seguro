<?php

namespace App\Models;

class Especialidade
{
    public function inserir($dados)
    {
        $stmt = $GLOBALS['conn']->prepare("INSERT INTO especialidades (nome) VALUES (?)");
        $stmt->bind_param("s", $dados['nome']);
        return $stmt->execute();
    }

    public function listar()
    {
        $result = $GLOBALS['conn']->query("SELECT * FROM especialidades");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function buscarPorId($id)
    {
        $stmt = $GLOBALS['conn']->prepare("SELECT * FROM especialidades WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function atualizar($dados)
    {
        $stmt = $GLOBALS['conn']->prepare("UPDATE especialidades SET nome = ? WHERE id = ?");
        $stmt->bind_param("si", $dados['nome'], $dados['id']);
        return $stmt->execute();
    }

    public function remover($id)
    {
        $stmt = $GLOBALS['conn']->prepare("DELETE FROM especialidades WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
