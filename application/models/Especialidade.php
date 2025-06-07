<?php
class Especialidade {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function listarTodas() {
        $sql = "SELECT * FROM especialidades";
        $resultado = $this->conn->query($sql);

        $especialidades = [];
        while ($linha = $resultado->fetch_assoc()) {
            $especialidades[] = $linha;
        }

        return $especialidades;
    }

    public function cadastrar($nome) {
        $stmt = $this->conn->prepare("INSERT INTO especialidades (nome) VALUES (?)");
        $stmt->bind_param("s", $nome);
        $resultado = $stmt->execute();
        $stmt->close();
        return $resultado;
    }

    public function excluir($id) {
        // Verifica se existe médico associado
        $verifica = $this->conn->prepare("SELECT COUNT(*) AS total FROM medicos WHERE especialidadeId = ?");
        $verifica->bind_param("i", $id);
        $verifica->execute();
        $resultado = $verifica->get_result()->fetch_assoc();
        $verifica->close();

        if ($resultado && $resultado['total'] > 0) {
            return [
                'sucesso' => false,
                'erro' => 'Não é possível excluir. Existe um ou mais médicos associados a essa especialidade.'
            ];
        }

        $stmt = $this->conn->prepare("DELETE FROM especialidades WHERE id = ?");
        $stmt->bind_param("i", $id);
        $sucesso = $stmt->execute();
        $stmt->close();

        return ['sucesso' => $sucesso];
    }

}
