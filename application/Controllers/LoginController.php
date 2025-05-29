<?php
$pagina = $_REQUEST["pagina"] ?? "login"; // se não vier nada, usa "login"

switch ($pagina) {
    case "login":
        require_once(__DIR__ . "/../View/login.php");
        break;
    case "cadastro":
        require_once __DIR__ . '/../View/cadastro.php';
        break;

    case "dashboard":
        session_start();
        if (isset($_SESSION['usuario'])) {
            header("Location: ?pagina=login");
            exit;
        }
        require_once __DIR__ . '/../View/dashboard.php';
        break;
    default:
        echo "Página não encontrada.";
}
?>