<?php

// utils/csrf_validador.php

// Sempre inicie a sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function valida_csrf($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
