<?php

namespace App\Models;

class Consulta
{
    public function inserir($dados)
    {
        $stmt = $GLOBALS['conn']->prepare("INSERT INTO consultas (pacienteId, medicoId, especialidadeId, dataHora) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $dados['pacienteId'], $dados['medicoId'], $dados['especialidadeId'], $dados['dataHora']);
        return $stmt->execute();
    }

    public function listar()
{
    $sql = "SELECT 
                consultas.id,
                consultas.dataHora,
                pacientes.id AS pacienteId,
                pacientes.nome AS nomePaciente,
                medicos.id AS medicoId,
                medicos.nome AS nomeMedico,
                especialidades.id AS especialidadeId,
                especialidades.nome AS nomeEspecialidade
            FROM consultas
            JOIN pacientes ON pacientes.id = consultas.pacienteId
            JOIN medicos ON medicos.id = consultas.medicoId
            JOIN especialidades ON especialidades.id = consultas.especialidadeId";

    $result = $GLOBALS['conn']->query($sql);

    if (!$result) {
        return [];
    }

    return $result->fetch_all(MYSQLI_ASSOC);
}


    public function buscarPorId($id)
    {
        $stmt = $GLOBALS['conn']->prepare("SELECT * FROM consultas WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function atualizar($dados)
    {
        $stmt = $GLOBALS['conn']->prepare("UPDATE consultas SET pacienteId = ?, medicoId = ?, especialidadeId = ?, dataHora = ? WHERE id = ?");
        $stmt->bind_param("iiisi", $dados['pacienteId'], $dados['medicoId'], $dados['especialidadeId'], $dados['dataHora'], $dados['id']);
        return $stmt->execute();
    }

    public function remover($id)
    {
        $stmt = $GLOBALS['conn']->prepare("DELETE FROM consultas WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
