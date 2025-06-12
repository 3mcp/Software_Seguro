<?php

namespace App\Models;

class Medico
{
    public function inserir($dados)
    {
        $stmt = $GLOBALS['conn']->prepare("INSERT INTO medicos (nome, crm, especialidadeId) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $dados['nome'], $dados['crm'], $dados['especialidadeId']);
        return $stmt->execute();
    }

    public function listar()
    {
        $result = $GLOBALS['conn']->query("SELECT * FROM medicos");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function buscarPorId($id)
    {
        $stmt = $GLOBALS['conn']->prepare("SELECT * FROM medicos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function atualizar($dados)
    {
        $stmt = $GLOBALS['conn']->prepare("UPDATE medicos SET nome = ?, crm = ?, especialidadeId = ? WHERE id = ?");
        $stmt->bind_param("ssii", $dados['nome'], $dados['crm'], $dados['especialidadeId'], $dados['id']);
        return $stmt->execute();
    }

    public function remover($id)
    {
        $stmt = $GLOBALS['conn']->prepare("DELETE FROM medicos WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
