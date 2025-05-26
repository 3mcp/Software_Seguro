<?php
require_once 'config.php';

function limparEntrada($entrada) {
    return htmlspecialchars(trim($entrada));
}

if (isset($_POST['usuario'], $_POST['senha'], $_POST['confirmar_senha'])) {
    $usuario = limparEntrada($_POST['usuario']);
    $senha = limparEntrada($_POST['senha']);
    $confirmarSenha = limparEntrada($_POST['confirmar_senha']);

    if (empty($usuario) || empty($senha) || empty($confirmarSenha)) {
        echo "<script>alert('Todos os campos são obrigatórios.'); window.history.back();</script>";
        exit;
    }

    if ($senha !== $confirmarSenha) {
        echo "<script>alert('As senhas não coincidem.'); window.history.back();</script>";
        exit;
    }

    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>alert('Nome de usuário já está em uso. Escolha outro.'); window.history.back();</script>";
        $stmt->close();
        exit;
    }
    $stmt->close();

    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO usuarios (usuario, senha) VALUES (?, ?)");
    $stmt->bind_param("ss", $usuario, $senhaHash);

    if ($stmt->execute()) {
        echo "<script>alert('Usuário cadastrado com sucesso!'); window.location.href = '../login.html';</script>";
    } else {
        echo "<script>alert('Erro ao cadastrar. Tente novamente.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();

} else {
    echo "<script>alert('Dados não enviados corretamente.'); window.history.back();</script>";
}
?>
