<?php
session_start();
require_once 'config.php';

function limparEntrada($entrada) {
  return htmlspecialchars(trim($entrada));
}

if (isset($_POST['usuario'], $_POST['senha'])) {
    $usuario = limparEntrada($_POST['usuario']);
    $senha = limparEntrada($_POST['senha']);

    $stmt = $conn->prepare("SELECT id, senha FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $senhaHash);
        $stmt->fetch();

        if (password_verify($senha, $senhaHash)) {
            $_SESSION['usuario_id'] = $id;
            $_SESSION['usuario_nome'] = $usuario;

            header("Location: ../dashboard.html");
            exit;
        } else {
            echo "<script>alert('Senha incorreta.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Usuário não encontrado.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<script>alert('Dados incompletos.'); window.history.back();</script>";
}
?>
