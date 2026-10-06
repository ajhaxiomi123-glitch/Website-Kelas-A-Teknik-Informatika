<?php
require_once 'auth.php';

$msg = '';
$edit = null;

// Hapus
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    // Hapus foto kalau ada
    $row = $conn->query("SELECT foto FROM anggota WHERE id=$id")->fetch_assoc();
    if ($row && $row['foto'] && file_exists("../uploads/anggota/" . $row['foto'])) {
        unlink("../uploads/anggota/" . $row['foto']);
    }
    $conn->query("DELETE FROM anggota WHERE id = $id");
    $msg = 'Data anggota dihapus.';
}

// Naik / Turun urutan
if (isset($_GET['naik']) || isset($_GET['turun'])) {
    $id = (int)($_GET['naik'] ?? $_GET['turun']);
    $arah = isset($_GET['naik']) ? 'naik' : 'turun';
    
    $current = $conn->query("SELECT urutan FROM anggota WHERE id=$id")->fetch_assoc();
    if ($current) {
        $urutan_now = (int)$current['urutan'];
        $target_urutan = $arah === 'naik' ? $urutan_now - 1 : $urutan_now + 1;
        
        // Cari anggota yang punya urutan target
        $target = $conn->query("SELECT id FROM anggota WHERE urutan=$target_urutan LIMIT 1")->fetch_assoc();
        if ($target) {
            // Tukar urutan
            $conn->query("UPDATE anggota SET urutan=$target_urutan WHERE id=$id");
            $conn->query("UPDATE anggota SET urutan=$urutan_now WHERE id=" . $target['id']);
        }
    }
    redirect('edit_anggota.php');
}

// Simpan (tambah/edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id        = (int)($_POST['id'] ?? 0);
    $npm       = trim($_POST['npm']);
    $nama      = trim($_POST['nama']);
    $jabatan   = trim($_POST['jabatan'] ?? '');
    $telepon   = trim($_POST['telepon'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $instagram = trim($_POST['instagram'] ?? '');
    $alamat    = trim($_POST['alamat'] ?? '');
    $urutan    = (int)($_POST['urutan'] ?? 999);

    // Handle upload foto
    $nama_foto = $_POST['foto_lama'] ?? null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['foto'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($ext, $allowed) && $file['size'] <= 2 * 1024 * 1024) {
            $nama_baru = 'anggota_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
            $dir = '../uploads/anggota/';
            if (!is_dir($dir)) mkdir($dir, 0755, true);
            
            if (move_uploaded_file($file['tmp_name'], $dir . $nama_baru)) {
                // Hapus foto lama
                if ($nama_foto && file_exists($dir . $nama_foto)) {
                    unlink($dir . $nama_foto);
                }
                $nama_foto = $nama_baru;
            }
        }
    }

    if ($id > 0) {
        $stmt = $conn->prepare("UPDATE anggota SET npm=?, nama=?, foto=?, jabatan=?, telepon=?, email=?, instagram=?, alamat=?, urutan=? WHERE id=?");
        $stmt->bind_param('ssssssssii', $npm, $nama, $nama_foto, $jabatan, $telepon, $email, $instagram, $alamat, $urutan, $id);
        $msg = 'Data berhasil diperbarui.';
    } else {
        $stmt = $conn->prepare("INSERT INTO anggota (npm, nama, foto, jabatan, telepon, email, instagram, alamat, urutan) VALUES (?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param('ssssssssi', $npm, $nama, $nama_foto, $jabatan, $telepon, $email, $instagram, $alamat, $urutan);
        $msg = 'Anggota baru ditambahkan.';
    }
    $stmt->execute();
}

// Mode edit
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $edit = $conn->query("SELECT * FROM anggota WHERE id=$id")->fetch_assoc();
}

$list = $conn->query("SELECT * FROM anggota ORDER BY urutan ASC, nama ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Anggota — Admin</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body { background: #f1f5f9; padding-bottom: 40px; }
        .admin-header { background: #172554; color: #fff; padding: 20px 0; margin-bottom: 30px; }
        .admin-header .container { display: flex; justify-content: space-between; align-items: center; }
        .admin-header a { color: #fff; text-decoration: none; background: rgba(255,255,255,0.15); padding: 8px 16px; border-radius: 8px; font-size: 14px; margin-left: 8px; }
        .card { background: #fff; padding: 28px; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.06); margin-bottom: 24px; }
        .card h2 { color: #172554; margin-bottom: 18px; font-size: 18px; }
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px; }
        .form-grid input { padding: 11px 14px; border: 1.5px solid #e2e8f0; border-radius: 8px; font-size: 14px; font-family: inherit; }
        .form-grid input:focus { outline: none; border-color: #2563eb; }
        .btn { padding: 11px 22px; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; font-size: 14px; text-decoration: none; display: inline-block; }
        .btn-primary { background: #172554; color: #fff; }
        .btn-primary:hover { background: #2563eb; }
        .btn-warning { background: #f59e0b; color: #fff; }
        .btn-danger { background: #dc2626; color: #fff; }
        .btn-sm { padding: 6px 10px; font-size: 12px; }
        .msg { background: #d1fae5; color: #065f46; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; }
        .foto-preview { width: 60px; height: 60px; border-radius: 50%; object-fit: cover; background: #e2e8f0; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        th, td { text-align: left; padding: 10px 8px; border-bottom: 1px solid #e2e8f0; vertical-align: middle; }
        th { background: #f8fafc; color: #172554; font-weight: 700; }
        tr:hover { background: #f8fafc; }
        .actions { white-space: nowrap; }
        .urutan-btn { display: inline-block; padding: 3px 8px; font-size: 12px; background: #e2e8f0; color: #172554; border-radius: 4px; text-decoration: none; font-weight: 700; }
        .urutan-btn:hover { background: #cbd5e1; }
        .ig-badge { color: #dc2626; font-weight: 600; font-size: 12px; }
    </style>
</head>
<body>
    <div class="admin-header">
        <div class="container">
            <h1 style="font-size:18px;">👥 Kelola Anggota Kelas</h1>
            <div>
                <a href="dashboard.php">← Dashboard</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div>

    <div class="container">
        <?php if ($msg): ?><div class="msg"><?= e($msg) ?></div><?php endif; ?>

        <div class="card">
            <h2><?= $edit ? '✏️ Edit Anggota' : '➕ Tambah Anggota Baru' ?></h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $edit['id'] ?? '' ?>">
                <input type="hidden" name="foto_lama" value="<?= e($edit['foto'] ?? '') ?>">
                
                <div class="form-grid">
                    <input type="text" name="npm" placeholder="NPM" value="<?= e($edit['npm'] ?? '') ?>" required>
                    <input type="text" name="nama" placeholder="Nama Lengkap" value="<?= e($edit['nama'] ?? '') ?>" required>
                    <input type="text" name="jabatan" placeholder="Jabatan (Kosongkan kalau anggota biasa)" value="<?= e($edit['jabatan'] ?? '') ?>">
                    <input type="text" name="telepon" placeholder="No. Telepon (cth: 087825037972)" value="<?= e($edit['telepon'] ?? '') ?>">
                    <input type="email" name="email" placeholder="Email" value="<?= e($edit['email'] ?? '') ?>">
                    <input type="text" name="instagram" placeholder="Instagram (tanpa @)" value="<?= e($edit['instagram'] ?? '') ?>">
                    <input type="text" name="alamat" placeholder="Alamat" value="<?= e($edit['alamat'] ?? '') ?>">
                    <input type="number" name="urutan" placeholder="Urutan (kecil = atas)" value="<?= e($edit['urutan'] ?? '999') ?>">
                </div>

                <div style="margin-top:14px;">
                    <label style="display:block; font-size:13px; color:#64748b; margin-bottom:8px;">
                        Foto Profil (JPG/PNG/WEBP, maks 2MB)
                    </label>
                    <?php if (!empty($edit['foto'])): ?>
                        <img src="../uploads/anggota/<?= e($edit['foto']) ?>" class="foto-preview" style="margin-bottom:8px; display:block;">
                    <?php endif; ?>
                    <input type="file" name="foto" accept="image/*">
                </div>

                <div style="margin-top:16px;">
                    <button type="submit" class="btn btn-primary"><?= $edit ? 'Simpan Perubahan' : 'Tambah' ?></button>
                    <?php if ($edit): ?>
                        <a href="edit_anggota.php" class="btn" style="background:#e2e8f0;">Batal</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="card">
            <h2>📋 Daftar Anggota (<?= $list->num_rows ?>)</h2>
            <p style="font-size:13px; color:#64748b; margin-bottom:14px;">
                💡 <strong>Tips:</strong> Gunakan tombol <strong>▲ ▼</strong> untuk memindahkan posisi anggota. Anggota dengan urutan terkecil (perangkat kelas) akan tampil di atas.
            </p>
            <table>
                <thead>
                    <tr>
                        <th>Urut</th>
                        <th>Foto</th>
                        <th>NPM</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Kontak</th>
                        <th>Instagram</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($a = $list->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <strong><?= $a['urutan'] ?></strong><br>
                                <a href="?naik=<?= $a['id'] ?>" class="urutan-btn" title="Naikkan">▲</a>
                                <a href="?turun=<?= $a['id'] ?>" class="urutan-btn" title="Turunkan">▼</a>
                            </td>
                            <td>
                                <?php if ($a['foto'] && file_exists("../uploads/anggota/" . $a['foto'])): ?>
                                    <img src="../uploads/anggota/<?= e($a['foto']) ?>" class="foto-preview">
                                <?php else: ?>
                                    <div class="foto-preview" style="display:flex; align-items:center; justify-content:center; color:#64748b; font-weight:700;">
                                        <?= strtoupper(substr($a['nama'], 0, 1)) ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><code><?= e($a['npm']) ?></code></td>
                            <td><?= e($a['nama']) ?></td>
                            <td><?= e($a['jabatan']) ?: '-' ?></td>
                            <td>
                                <?= $a['telepon'] ? '📱 ' . e($a['telepon']) . '<br>' : '' ?>
                                <?= $a['email'] ? '✉️ ' . e($a['email']) : '' ?>
                            </td>
                            <td>
                                <?= $a['instagram'] ? '<span class="ig-badge">@' . e($a['instagram']) . '</span>' : '-' ?>
                            </td>
                            <td class="actions">
                                <a href="?edit=<?= $a['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="?hapus=<?= $a['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus <?= e($a['nama']) ?>?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>