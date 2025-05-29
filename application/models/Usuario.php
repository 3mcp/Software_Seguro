<?php
class Usuario {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function existeUsuario($usuario) {
        $stmt = $this->conn->prepare("SELECT id FROM usuarios WHERE usuario = ?");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $stmt->store_result();
        $existe = $stmt->num_rows > 0;
        $stmt->close();
        return $existe;
    }

    public function buscarUsuarioPorNome($usuario) {
    $stmt = $this->conn->prepare("SELECT id, usuario, senha FROM usuarios WHERE usuario = ?");
    
    if (!$stmt) {
        die("Erro na preparação: " . $this->conn->error);
    }

    $stmt->bind_param("s", $usuario);

    if (!$stmt->execute()) {
        die("Erro na execução: " . $stmt->error);
    }

    $resultado = $stmt->get_result();
    $usuarioData = $resultado->fetch_assoc();
    $stmt->close();

    var_dump($usuarioData); // Veja o que veio do banco

    return $usuarioData;
}


    public function criarUsuario($usuario, $senhaHash) {
        $stmt = $this->conn->prepare("INSERT INTO usuarios (usuario, senha) VALUES (?, ?)");
        $stmt->bind_param("ss", $usuario, $senhaHash);
        $sucesso = $stmt->execute();
        $stmt->close();
        return $sucesso;
    }
}
