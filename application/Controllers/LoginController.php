<?php
$pagina = $_REQUEST["pagina"] ?? "login"; // se não vier nada, usa "login"

switch ($pagina) {
    case "login":
        require_once(__DIR__ . "/../View/login.php");
        break;
    case "cadastro":
        require_once __DIR__ . '/../View/cadastro.php';
        break;
    default:
        echo "Página não encontrada.";
}
?>