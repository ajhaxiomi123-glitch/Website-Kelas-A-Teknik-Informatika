<?php
require_once 'auth.php';

// Statistik
$total_anggota = $conn->query("SELECT COUNT(*) c FROM anggota")->fetch_assoc()['c'];
$total_jadwal  = $conn->query("SELECT COUNT(*) c FROM jadwal WHERE mata_kuliah != 'LIBUR'")->fetch_assoc()['c'];
$total_pengumuman = $conn->query("SELECT COUNT(*) c FROM pengumuman")->fetch_assoc()['c'];
$total_dokumentasi = $conn->query("SELECT COUNT(*) c FROM dokumentasi")->fetch_assoc()['c'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin — Kelas A</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body { background: #f1f5f9; }
        .admin-header { background: #172554; color: #fff; padding: 20px 0; }
        .admin-header .container { display: flex; justify-content: space-between; align-items: center; }
        .admin-header h1 { font-size: 20px; }
        .admin-header a { color: #fff; text-decoration: none; background: rgba(255,255,255,0.15); padding: 8px 16px; border-radius: 8px; font-size: 14px; }
        .admin-header a:hover { background: rgba(255,255,255,0.25); }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin: 30px 0; }
        .stat-card { background: #fff; padding: 24px; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.06); }
        .stat-card strong { display: block; font-size: 32px; color: #172554; margin-bottom: 4px; }
        .stat-card span { color: #64748b; font-size: 14px; }
        .menu-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; }
        .menu-card { background: #fff; padding: 28px 24px; border-radius: 12px; text-decoration: none; color: #172554; border: 1.5px solid #e2e8f0; transition: 0.3s; }
        .menu-card:hover { border-color: #2563eb; transform: translateY(-3px); box-shadow: 0 8px 24px rgba(37,99,235,0.15); }
        .menu-card .icon { font-size: 32px; margin-bottom: 12px; }
        .menu-card h3 { font-size: 17px; margin-bottom: 6px; }
        .menu-card p { color: #64748b; font-size: 13px; }
    </style>
</head>
<body>
    <div class="admin-header">
        <div class="container">
            <h1>⚙️ Dashboard Admin — Kelas A</h1>
            <div>
                <span style="margin-right:16px; font-size:14px;">Halo, <?= e($_SESSION['admin_nama']) ?></span>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="stats">
            <div class="stat-card"><strong><?= $total_anggota ?></strong><span>Total Anggota</span></div>
            <div class="stat-card"><strong><?= $total_jadwal ?></strong><span>Mata Kuliah</span></div>
            <div class="stat-card"><strong><?= $total_pengumuman ?></strong><span>Pengumuman</span></div>
            <div class="stat-card"><strong><?= $total_dokumentasi ?></strong><span>Foto Dokumentasi</span></div>
        </div>

        <h2 style="color:#172554; margin-bottom:16px;">Kelola Data</h2>
        <div class="menu-grid">
            <a href="edit_anggota.php" class="menu-card">
                <div class="icon">👥</div>
                <h3>Kelola Anggota</h3>
                <p>Tambah, edit, atau hapus data anggota kelas</p>
            </a>
            <a href="edit_jadwal.php" class="menu-card">
                <div class="icon">📅</div>
                <h3>Kelola Jadwal</h3>
                <p>Atur jadwal mata kuliah per hari</p>
            </a>
            <a href="edit_pengumuman.php" class="menu-card">
                <div class="icon">📢</div>
                <h3>Kelola Pengumuman</h3>
                <p>Buat dan hapus pengumuman kelas</p>
            </a>
            <a href="upload_foto.php" class="menu-card">
                <div class="icon">📸</div>
                <h3>Upload Dokumentasi</h3>
                <p>Tambah foto kegiatan kelas</p>
            </a>
            <a href="../index.php" class="menu-card">
                <div class="icon">🌐</div>
                <h3>Lihat Website</h3>
                <p>Buka halaman publik kelas A</p>
            </a>
        </div>
    </div>
</body>
</html>