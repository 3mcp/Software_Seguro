<?php
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$host    = $_ENV['DB_HOST'];
$usuario = $_ENV['DB_USER'];
$senha   = $_ENV['DB_PASS'];
$banco   = $_ENV['DB_NAME'];

$GLOBALS['conn'] = new mysqli($host, $usuario, $senha, $banco);

if ($GLOBALS['conn']->connect_error) {
    die(json_encode(["erro" => "Falha na conexão: " . $GLOBALS['conn']->connect_error]));
}

$GLOBALS['conn']->set_charset("utf8");
