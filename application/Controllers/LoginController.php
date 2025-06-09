<?php


require_once __DIR__ . '/../vendor/autoload.php';
require_once(__DIR__ . '/../config/config.php');



$pagina = $_REQUEST["pagina"] ?? "login";

switch ($pagina) {
    case "login":
        require_once(__DIR__ . "/../View/login.html");
        break;

    case "cadastro":
        require_once __DIR__ . '/../View/cadastro.html';
        break;

    case "autenticar":
        $usuarioModel = new Usuario($conn);

        $login = $_POST['usuario'] ?? '';
        $senha = $_POST['senha'] ?? '';

        $usuarioData = $usuarioModel->buscarUsuarioPorNome($login);

        if ($usuarioData && password_verify($senha, $usuarioData['senha'])) {
            $_SESSION['usuario_id'] = $usuarioData['id'];
            $_SESSION['usuario'] = $usuarioData['usuario'];
            $_SESSION['token'] = bin2hex(random_bytes(16));

            header("Location: index.php?controller=LoginController&pagina=dashboard");
            exit;
        } else {
            echo "<script>alert('Login inválido!'); window.location.href='index.php?controller=LoginController&pagina=login';</script>";
        }
        break;

    case "logout":
        session_unset();
        session_destroy();
        header("Location: index.php?controller=LoginController&pagina=login");
        exit;

    case "dashboard":
        // Carrega dashboard se autenticado
        break;

    default:
        echo "Página não encontrada.";
}
?>
