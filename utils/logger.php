<?php
// utils/logger.php

namespace App\Utils;

use Exception;

class Logger
{
    private static $logFile;

    // Inicializa o caminho do log
    private static function init()
    {
        if (!self::$logFile) {
            self::$logFile = __DIR__ . '/../../logs/sistema.log';
        }
    }

    public static function log(string $message): void
    {
        self::init();

        $dataHora = date('Y-m-d H:i:s');
        $mensagemFormatada = "[$dataHora] $message\n";

        // Tenta gravar com lock de arquivo (segurança contra concorrência)
        file_put_contents(self::$logFile, $mensagemFormatada, FILE_APPEND | LOCK_EX);
    }

    public static function logErro(Exception $e): void
    {
        self::init();

        $dataHora = date('Y-m-d H:i:s');
        $mensagemErro = "[$dataHora] ERRO: {$e->getMessage()} em {$e->getFile()}:{$e->getLine()}\n";

        file_put_contents(self::$logFile, $mensagemErro, FILE_APPEND | LOCK_EX);
    }
}
