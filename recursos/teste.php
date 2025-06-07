<?php
$senha = "Admin123456";
$hash = '$2y$10$D5TY0An6a.qthQTfDSrMXOyACuPtC5bNaonwhB4R5QcBTl6mT0rhm';

if (password_verify($senha, $hash)) {
    echo "Senha correta!";
} else {
    echo "Senha incorreta!";
}

echo password_hash("Admin123456", PASSWORD_DEFAULT);