<?php

$host = 'localhost';
$usuario = 'root';
$senha = 'PUC@1234';
$banco = 'clinica_medica';

$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die(json_encode(["erro" => "Falha na conexão: " . $conn->connect_error]));
}

if (!$conn) {
    die("Conexão falhou: " . mysqli_connect_error());
} 

$conn->set_charset("utf8");