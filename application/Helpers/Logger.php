<?php
if (!function_exists('registrarLog')) {
    function registrarLog($mensagem)
    {
        $dataHora = date("Y-m-d_H-i-s");
        $dataArquivo = date("Y-m-d");
        $diretorio = __DIR__ . "/Logs";

        if (!is_dir($diretorio)) {
            mkdir($diretorio, 0777, true);
        }

        $arquivo = "$diretorio/log_$dataArquivo.txt";
        $mensagemFormatada = "[$dataHora] $mensagem" . PHP_EOL;
        file_put_contents($arquivo, $mensagemFormatada, FILE_APPEND);
    }
}
