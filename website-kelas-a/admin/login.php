<?php
require_once '../config.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_nama'] = $row['nama_lengkap'];
            redirect('dashboard.php');
        } else {
            $error = 'Password salah.';
        }
    } else {
        $error = 'Username tidak ditemukan.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin — Kelas A</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body { background: linear-gradient(135deg, #172554, #2563eb); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-box { background: #fff; padding: 40px; border-radius: 16px; width: 100%; max-width: 400px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
        .login-box h1 { color: #172554; margin-bottom: 6px; font-size: 24px; }
        .login-box p { color: #64748b; margin-bottom: 24px; font-size: 14px; }
        .login-box input { width: 100%; padding: 12px 16px; margin-bottom: 14px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 15px; font-family: inherit; }
        .login-box input:focus { outline: none; border-color: #2563eb; }
        .login-box button { width: 100%; padding: 13px; background: #172554; color: #fff; border: none; border-radius: 10px; font-weight: 700; font-size: 15px; cursor: pointer; transition: 0.3s; }
        .login-box button:hover { background: #2563eb; }
        .error { background: #fee2e2; color: #dc2626; padding: 12px; border-radius: 8px; margin-bottom: 16px; font-size: 14px; }
        .back { display: block; text-align: center; margin-top: 20px; color: #64748b; text-decoration: none; font-size: 13px; }
    </style>
</head>
<body>
    <div class="login-box">
        <h1>Login Admin</h1>
        <p>Panel admin Kelas A</p>
        <?php if ($error): ?><div class="error"><?= e($error) ?></div><?php endif; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required autofocus>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Masuk</button>
        </form>
        <a href="../index.php" class="back">← Kembali ke halaman utama</a>
    </div>
</body>
</html>