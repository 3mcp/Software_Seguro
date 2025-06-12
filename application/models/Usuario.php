<?php

namespace App\Models;

class Usuario
{
    public function inserir($usuario, $senha)
    {
        $senhaHash = hash('sha256', $senha);
        $stmt = $GLOBALS['conn']->prepare("INSERT INTO usuarios (usuario, senha) VALUES (?, ?)");
        $stmt->bind_param("ss", $usuario, $senhaHash);
        return $stmt->execute();
    }

    public function buscarPorUsuario($usuario)
    {
        $stmt = $GLOBALS['conn']->prepare("SELECT * FROM usuarios WHERE usuario = ?");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function buscarPorId($id)
    {
        $stmt = $GLOBALS['conn']->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function listar()
    {
        $result = $GLOBALS['conn']->query("SELECT * FROM usuarios");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function atualizar($id, $usuario, $senha = null)
    {
        if ($senha) {
            $senhaHash = hash('sha256', $senha);
            $stmt = $GLOBALS['conn']->prepare("UPDATE usuarios SET usuario = ?, senha = ? WHERE id = ?");
            $stmt->bind_param("ssi", $usuario, $senhaHash, $id);
        } else {
            $stmt = $GLOBALS['conn']->prepare("UPDATE usuarios SET usuario = ? WHERE id = ?");
            $stmt->bind_param("si", $usuario, $id);
        }
        return $stmt->execute();
    }

    public function remover($id)
    {
        $stmt = $GLOBALS['conn']->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
