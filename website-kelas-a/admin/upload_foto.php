<?php
require_once 'auth.php';
$msg = ''; $err = '';

// Hapus
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    $row = $conn->query("SELECT file_foto FROM dokumentasi WHERE id=$id")->fetch_assoc();
    if ($row && file_exists("../uploads/" . $row['file_foto'])) {
        unlink("../uploads/" . $row['file_foto']);
    }
    $conn->query("DELETE FROM dokumentasi WHERE id=$id");
    $msg = 'Dokumentasi dihapus.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul']);
    $keterangan = trim($_POST['keterangan']);

    if (!isset($_FILES['foto']) || $_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
        $err = 'Gagal upload. Coba lagi.';
    } else {
        $file = $_FILES['foto'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($ext, $allowed)) {
            $err = 'Format tidak didukung. Gunakan JPG/PNG/GIF/WEBP.';
        } elseif ($file['size'] > 5 * 1024 * 1024) {
            $err = 'Ukuran maksimal 5MB.';
        } else {
            $nama_baru = 'foto_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
            $tujuan = '../uploads/' . $nama_baru;

            if (!is_dir('../uploads')) mkdir('../uploads', 0755, true);

            if (move_uploaded_file($file['tmp_name'], $tujuan)) {
                $stmt = $conn->prepare("INSERT INTO dokumentasi (judul, file_foto, keterangan) VALUES (?,?,?)");
                $stmt->bind_param('sss', $judul, $nama_baru, $keterangan);
                $stmt->execute();
                $msg = 'Foto berhasil diupload.';
            } else {
                $err = 'Gagal menyimpan file.';
            }
        }
    }
}

$list = $conn->query("SELECT * FROM dokumentasi ORDER BY tanggal DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Upload Dokumentasi — Admin</title>
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
        .btn { padding: 11px 22px; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; font-size: 14px; text-decoration: none; display: inline-block; }
        .btn-primary { background: #172554; color: #fff; }
        .btn-danger { background: #dc2626; color: #fff; }
        .btn-sm { padding: 6px 12px; font-size: 12px; }
        .msg { background: #d1fae5; color: #065f46; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; }
        .err { background: #fee2e2; color: #991b1b; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; }
        .grid-foto { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 14px; }
        .foto-item { position: relative; border-radius: 10px; overflow: hidden; background: #f1f5f9; }
        .foto-item img { width: 100%; aspect-ratio: 4/3; object-fit: cover; display: block; }
        .foto-item h4 { padding: 8px 10px 2px; font-size: 13px; color: #172554; }
        .foto-item small { padding: 0 10px 10px; display: block; color: #64748b; font-size: 11px; }
        .foto-item .btn-danger { position: absolute; top: 8px; right: 8px; }
    </style>
</head>
<body>
    <div class="admin-header">
        <div class="container">
            <h1 style="font-size:18px;">📸 Upload Dokumentasi</h1>
            <div>
                <a href="dashboard.php">← Dashboard</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div>

    <div class="container">
        <?php if ($msg): ?><div class="msg"><?= e($msg) ?></div><?php endif; ?>
        <?php if ($err): ?><div class="err"><?= e($err) ?></div><?php endif; ?>

        <div class="card">
            <h2>➕ Upload Foto Baru</h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="text" name="judul" placeholder="Judul foto (cth: Rapat Kelas)" required>
                <input type="file" name="foto" accept="image/*" required>
                <textarea name="keterangan" placeholder="Keterangan (opsional)" style="min-height:80px;"></textarea>
                <button type="submit" class="btn btn-primary">Upload</button>
            </form>
        </div>

        <div class="card">
            <h2>📋 Galeri (<?= $list->num_rows ?> foto)</h2>
            <?php if ($list->num_rows === 0): ?>
                <p style="color:#64748b;">Belum ada foto.</p>
            <?php else: ?>
                <div class="grid-foto">
                    <?php while ($d = $list->fetch_assoc()): ?>
                        <div class="foto-item">
                            <img src="../uploads/<?= e($d['file_foto']) ?>" alt="<?= e($d['judul']) ?>">
                            <h4><?= e($d['judul']) ?></h4>
                            <small><?= date('d M Y', strtotime($d['tanggal'])) ?></small>
                            <a href="?hapus=<?= $d['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus foto ini?')">×</a>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>