<?php
ini_set('session.gc_maxlifetime', 900); //15 minutos
session_set_cookie_params([
    'lifetime' => 900,
    'path' => '/',
    'secure' => false,  // true se estiver usando HTTPS
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();
