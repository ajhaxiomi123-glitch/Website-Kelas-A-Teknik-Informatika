<?php
require_once 'auth.php';

$msg = '';
$edit = null;

if (isset($_GET['hapus'])) {
    $conn->query("DELETE FROM jadwal WHERE id = " . (int)$_GET['hapus']);
    $msg = 'Jadwal dihapus.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $hari = $_POST['hari'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];
    $mata_kuliah = trim($_POST['mata_kuliah']);
    $sks = (int)$_POST['sks'];
    $dosen = trim($_POST['dosen']);
    $ruang = trim($_POST['ruang']);
    $catatan = trim($_POST['catatan']);

    if ($id > 0) {
        $stmt = $conn->prepare("UPDATE jadwal SET hari=?, jam_mulai=?, jam_selesai=?, mata_kuliah=?, sks=?, dosen=?, ruang=?, catatan=? WHERE id=?");
        $stmt->bind_param('ssssisssi', $hari, $jam_mulai, $jam_selesai, $mata_kuliah, $sks, $dosen, $ruang, $catatan, $id);
        $msg = 'Jadwal diperbarui.';
    } else {
        $stmt = $conn->prepare("INSERT INTO jadwal (hari, jam_mulai, jam_selesai, mata_kuliah, sks, dosen, ruang, catatan) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->bind_param('ssssisss', $hari, $jam_mulai, $jam_selesai, $mata_kuliah, $sks, $dosen, $ruang, $catatan);
        $msg = 'Jadwal ditambahkan.';
    }
    $stmt->execute();
}

if (isset($_GET['edit'])) {
    $edit = $conn->query("SELECT * FROM jadwal WHERE id=" . (int)$_GET['edit'])->fetch_assoc();
}

$list = $conn->query("SELECT * FROM jadwal ORDER BY FIELD(hari,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'), urutan ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Jadwal — Admin</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body { background: #f1f5f9; padding-bottom: 40px; }
        .admin-header { background: #172554; color: #fff; padding: 20px 0; margin-bottom: 30px; }
        .admin-header .container { display: flex; justify-content: space-between; align-items: center; }
        .admin-header a { color: #fff; text-decoration: none; background: rgba(255,255,255,0.15); padding: 8px 16px; border-radius: 8px; font-size: 14px; margin-left: 8px; }
        .card { background: #fff; padding: 28px; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.06); margin-bottom: 24px; }
        .card h2 { color: #172554; margin-bottom: 18px; font-size: 18px; }
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 12px; }
        .form-grid input, .form-grid select { padding: 11px 14px; border: 1.5px solid #e2e8f0; border-radius: 8px; font-size: 14px; font-family: inherit; width: 100%; }
        .form-grid input:focus, .form-grid select:focus { outline: none; border-color: #2563eb; }
        .btn { padding: 11px 22px; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; font-size: 14px; text-decoration: none; display: inline-block; }
        .btn-primary { background: #172554; color: #fff; }
        .btn-primary:hover { background: #2563eb; }
        .btn-warning { background: #f59e0b; color: #fff; }
        .btn-danger { background: #dc2626; color: #fff; }
        .btn-sm { padding: 6px 12px; font-size: 12px; }
        .msg { background: #d1fae5; color: #065f46; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        th, td { text-align: left; padding: 10px 8px; border-bottom: 1px solid #e2e8f0; }
        th { background: #f8fafc; color: #172554; font-weight: 700; }
        tr:hover { background: #f8fafc; }
    </style>
</head>
<body>
    <div class="admin-header">
        <div class="container">
            <h1 style="font-size:18px;">📅 Kelola Jadwal Kuliah</h1>
            <div>
                <a href="dashboard.php">← Dashboard</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div>

    <div class="container">
        <?php if ($msg): ?><div class="msg"><?= e($msg) ?></div><?php endif; ?>

        <div class="card">
            <h2><?= $edit ? '✏️ Edit Jadwal' : '➕ Tambah Jadwal' ?></h2>
            <form method="POST">
                <input type="hidden" name="id" value="<?= $edit['id'] ?? '' ?>">
                <div class="form-grid">
                    <select name="hari" required>
                        <?php foreach (['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $h): ?>
                            <option value="<?= $h ?>" <?= ($edit['hari'] ?? '') === $h ? 'selected' : '' ?>><?= $h ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="text" name="jam_mulai" placeholder="Jam Mulai (cth: 08.00)" value="<?= e($edit['jam_mulai'] ?? '') ?>" required>
                    <input type="text" name="jam_selesai" placeholder="Jam Selesai" value="<?= e($edit['jam_selesai'] ?? '') ?>" required>
                    <input type="text" name="mata_kuliah" placeholder="Mata Kuliah" value="<?= e($edit['mata_kuliah'] ?? '') ?>" required>
                    <input type="number" name="sks" placeholder="SKS" value="<?= e($edit['sks'] ?? '') ?>" min="0">
                    <input type="text" name="dosen" placeholder="Dosen" value="<?= e($edit['dosen'] ?? '') ?>">
                    <input type="text" name="ruang" placeholder="Ruang" value="<?= e($edit['ruang'] ?? '') ?>">
                    <input type="text" name="catatan" placeholder="Catatan" value="<?= e($edit['catatan'] ?? '') ?>">
                </div>
                <div style="margin-top:16px;">
                    <button type="submit" class="btn btn-primary"><?= $edit ? 'Simpan' : 'Tambah' ?></button>
                    <?php if ($edit): ?><a href="edit_jadwal.php" class="btn" style="background:#e2e8f0;">Batal</a><?php endif; ?>
                </div>
            </form>
        </div>

        <div class="card">
            <h2>📋 Daftar Jadwal</h2>
            <table>
                <thead>
                    <tr><th>Hari</th><th>Jam</th><th>Mata Kuliah</th><th>SKS</th><th>Dosen</th><th>Ruang</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    <?php while ($j = $list->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?= e($j['hari']) ?></strong></td>
                            <td><?= e($j['jam_mulai']) ?>-<?= e($j['jam_selesai']) ?></td>
                            <td><?= e($j['mata_kuliah']) ?></td>
                            <td><?= $j['sks'] ?></td>
                            <td><?= e($j['dosen']) ?></td>
                            <td><?= e($j['ruang']) ?></td>
                            <td class="actions">
                                <a href="?edit=<?= $j['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="?hapus=<?= $j['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus jadwal ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>