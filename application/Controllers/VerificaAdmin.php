<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
    echo json_encode(['admin' => true]);
} else {
    echo json_encode(['admin' => false]);
}
