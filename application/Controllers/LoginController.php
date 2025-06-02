<?php
$pagina = $_REQUEST["pagina"] ?? "login"; // se não vier nada, usa "login"

switch ($pagina) {
    case "login":
        require_once(__DIR__ . "/../View/login.html");
        break;
    case "cadastro":
        require_once __DIR__ . '/../View/cadastro.html';
        break;
    case "dashboard":
        break; // Aqui não precisa incluir nada, pois o dashboard é carregado após autenticação
    default:
        echo "Página não encontrada.";
}
?>