<?php
// utils/sessao.php
namespace App\Utils;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


?>



