<?php
require_once 'auth.php';
$msg = '';

if (isset($_GET['hapus'])) {
    $conn->query("DELETE FROM pengumuman WHERE id=" . (int)$_GET['hapus']);
    $msg = 'Pengumuman dihapus.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul']);
    $isi = trim($_POST['isi']);
    $stmt = $conn->prepare("INSERT INTO pengumuman (judul, isi, penulis) VALUES (?, ?, ?)");
    $penulis = $_SESSION['admin_nama'];
    $stmt->bind_param('sss', $judul, $isi, $penulis);
    $stmt->execute();
    $msg = 'Pengumuman dipublikasikan.';
}

$list = $conn->query("SELECT * FROM pengumuman ORDER BY tanggal DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Pengumuman — Admin</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body { background: #f1f5f9; padding-bottom: 40px; }
        .admin-header { background: #172554; color: #fff; padding: 20px 0; margin-bottom: 30px; }
        .admin-header .container { display: flex; justify-content: space-between; align-items: center; }
        .admin-header a { color: #fff; text-decoration: none; background: rgba(255,255,255,0.15); padding: 8px 16px; border-radius: 8px; font-size: 14px; margin-left: 8px; }
        .card { background: #fff; padding: 28px; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.06); margin-bottom: 24px; }
        .card h2 { color: #172554; margin-bottom: 18px; font-size: 18px; }
        input, textarea { width: 100%; padding: 12px 14px; border: 1.5px solid #e2e8f0; border-radius: 8px; font-size: 14px; font-family: inherit; margin-bottom: 12px; }
        input:focus, textarea:focus { outline: none; border-color: #2563eb; }
        textarea { min-height: 120px; resize: vertical; }
        .btn { padding: 11px 22px; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; font-size: 14px; text-decoration: none; display: inline-block; }
        .btn-primary { background: #172554; color: #fff; }
        .btn-danger { background: #dc2626; color: #fff; }
        .btn-sm { padding: 6px 12px; font-size: 12px; }
        .msg { background: #d1fae5; color: #065f46; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; }
        .item { border-left: 4px solid #2563eb; padding: 14px 18px; background: #f8fafc; border-radius: 8px; margin-bottom: 12px; }
        .item h4 { color: #172554; margin-bottom: 6px; }
        .item small { color: #64748b; font-size: 12px; }
        .item p { margin-top: 8px; font-size: 14px; color: #334155; }
    </style>
</head>
<body>
    <div class="admin-header">
        <div class="container">
            <h1 style="font-size:18px;">📢 Kelola Pengumuman</h1>
            <div>
                <a href="dashboard.php">← Dashboard</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div>

    <div class="container">
        <?php if ($msg): ?><div class="msg"><?= e($msg) ?></div><?php endif; ?>

        <div class="card">
            <h2>➕ Buat Pengumuman Baru</h2>
            <form method="POST">
                <input type="text" name="judul" placeholder="Judul pengumuman" required>
                <textarea name="isi" placeholder="Isi pengumuman..." required></textarea>
                <button type="submit" class="btn btn-primary">Publikasikan</button>
            </form>
        </div>

        <div class="card">
            <h2>📋 Daftar Pengumuman (<?= $list->num_rows ?>)</h2>
            <?php while ($p = $list->fetch_assoc()): ?>
                <div class="item">
                    <h4><?= e($p['judul']) ?></h4>
                    <small><?= date('d M Y, H:i', strtotime($p['tanggal'])) ?> • <?= e($p['penulis']) ?></small>
                    <p><?= nl2br(e($p['isi'])) ?></p>
                    <a href="?hapus=<?= $p['id'] ?>" class="btn btn-sm btn-danger" style="margin-top:10px;" onclick="return confirm('Hapus pengumuman ini?')">Hapus</a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>