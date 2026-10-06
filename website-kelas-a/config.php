<?php
session_start();

$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'kelas_a';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die('Koneksi gagal: ' . $conn->connect_error);
}
$conn->set_charset('utf8mb4');

// Helper: escape HTML
function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

// Cek status admin
function is_admin() {
    return isset($_SESSION['admin_id']);
}

// Redirect helper
function redirect($url) {
    header("Location: $url");
    exit;
}
?>