<?php
// admin/reset_admin.php — HAPUS file ini setelah dipakai!
require_once '../config.php';
$password_baru = 'admin123';
$hash = password_hash($password_baru, PASSWORD_DEFAULT);
$conn->query("UPDATE admin SET password='$hash' WHERE username='admin'");
echo "Password admin berhasil di-reset ke: <b>$password_baru</b><br>";
echo "Hash: <code>$hash</code><br><br>";
echo "⚠️ HAPUS file reset_admin.php sekarang!";