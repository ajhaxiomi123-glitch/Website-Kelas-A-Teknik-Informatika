<?php
require_once 'config.php';

// Ambil data dari database
$anggota    = $conn->query("SELECT * FROM anggota ORDER BY urutan ASC, nama ASC");
$jadwal     = $conn->query("SELECT * FROM jadwal ORDER BY 
    FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'), urutan ASC");
$pengumuman = $conn->query("SELECT * FROM pengumuman ORDER BY tanggal DESC LIMIT 5");
$dokumentasi = $conn->query("SELECT * FROM dokumentasi ORDER BY tanggal DESC");

// Kelompokkan jadwal per hari
$jadwal_per_hari = [];
while ($j = $jadwal->fetch_assoc()) {
    $jadwal_per_hari[$j['hari']][] = $j;
}
$hari_urutan = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelas A — Teknik Informatika 2026</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="container nav-wrapper">
        <a href="#" class="logo">
    <img src="assets/logo.png" alt="Logo Kelas A" class="logo-img">
    <div>
        <strong>Kelas A</strong>
        <small>Teknik Informatika 2026</small>
    </div>
</a>
        <ul class="nav-menu">
            <li><a href="#anggota">Anggota</a></li>
            <li><a href="#jadwal">Jadwal</a></li>
            <li><a href="#dokumentasi">Dokumentasi</a></li>
            <li><a href="#pengumuman">Pengumuman</a></li>
        </ul>
        <div class="nav-right">
            <span class="clock" id="clock">--:--:--</span>
            <?php if (is_admin()): ?>
                <a href="admin/dashboard.php" class="btn-admin">⚙️ Admin</a>
            <?php else: ?>
                <a href="admin/login.php" class="btn-login">Login</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<!-- HERO -->
<header class="hero">
    <div class="container hero-content">
        <h1>Selamat Datang di <span>Kelas A</span></h1>
        <p>Pusat informasi kelas — anggota, jadwal kuliah, dokumentasi, dan pengumuman terbaru.</p>
        <div class="hero-stats">
            <div><strong><?= $conn->query("SELECT COUNT(*) c FROM anggota")->fetch_assoc()['c'] ?></strong><span>Anggota</span></div>
            <div><strong>7</strong><span>Hari Kuliah</span></div>
            <div><strong>10</strong><span>Mata Kuliah</span></div>
            <div><strong>18</strong><span>SKS Total</span></div>
        </div>
    </div>
</header>

<!-- ANGGOTA -->
<section id="anggota" class="section">
    <div class="container">
        <div class="section-head">
            <span class="section-tag">Struktur Kelas</span>
            <h2>Anggota Kelas A</h2>
            <p class="section-sub">Total <?= $conn->query("SELECT COUNT(*) c FROM anggota")->fetch_assoc()['c'] ?> mahasiswa terdaftar</p>
        </div>

        <input type="text" id="searchAnggota" class="search-box" placeholder="🔍 Cari nama atau NPM...">

        <div class="anggota-grid" id="anggotaGrid">
    <?php 
    // Kelompokkan: yang punya jabatan dulu (di atas), lalu anggota biasa
    $anggota->data_seek(0);
    $perangkat = [];
    $biasa = [];
    while ($a = $anggota->fetch_assoc()) {
        if (!empty($a['jabatan'])) $perangkat[] = $a;
        else $biasa[] = $a;
    }
    $semua = array_merge($perangkat, $biasa);
    foreach ($semua as $a): 
    ?>
        <div class="anggota-card" data-search="<?= strtolower(e($a['nama']) . ' ' . e($a['npm'])) ?>">
            <?php if ($a['jabatan']): ?>
                <span class="jabatan-badge"><?= e($a['jabatan']) ?></span>
            <?php endif; ?>
            
            <?php if ($a['foto'] && file_exists("uploads/anggota/" . $a['foto'])): ?>
                <img src="uploads/anggota/<?= e($a['foto']) ?>" alt="<?= e($a['nama']) ?>" class="avatar-img">
            <?php else: ?>
                <div class="avatar"><?= strtoupper(substr($a['nama'], 0, 1)) ?></div>
            <?php endif; ?>
            
            <h4><?= e($a['nama']) ?></h4>
            <p class="npm">NPM: <?= e($a['npm']) ?></p>
            
            <?php if ($a['telepon']): ?>
                <p class="kontak">📱 <a href="https://wa.me/62<?= ltrim(e($a['telepon']), '0') ?>" target="_blank"><?= e($a['telepon']) ?></a></p>
            <?php endif; ?>
            <?php if ($a['email']): ?>
                <p class="kontak">✉️ <?= e($a['email']) ?></p>
            <?php endif; ?>
            <?php if ($a['instagram']): ?>
                <p class="kontak">📷 <a href="https://instagram.com/<?= e($a['instagram']) ?>" target="_blank">@<?= e($a['instagram']) ?></a></p>
            <?php endif; ?>
            <?php if ($a['alamat']): ?>
                <p class="kontak alamat">📍 <?= e($a['alamat']) ?></p>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>
    </div>
</section>

<!-- JADWAL -->
<section id="jadwal" class="section section-alt">
    <div class="container">
        <div class="section-head">
            <span class="section-tag">Jadwal Kuliah</span>
            <h2>Jadwal Semester 1</h2>
            <p class="section-sub">Kelas A — Teknik Informatika 2026</p>
        </div>

        <div class="jadwal-wrapper">
            <?php foreach ($hari_urutan as $hari): 
                if (!isset($jadwal_per_hari[$hari])) continue;
                $list = $jadwal_per_hari[$hari];
                $is_libur = ($list[0]['mata_kuliah'] === 'LIBUR');
            ?>
                <div class="hari-card <?= $is_libur ? 'libur' : '' ?>">
                    <div class="hari-head">
                        <h3><?= e($hari) ?></h3>
                    </div>
                    <div class="hari-body">
                        <?php if ($is_libur): ?>
                            <p class="libur-text"><?= e($list[0]['catatan']) ?></p>
                        <?php else: ?>
                            <?php foreach ($list as $j): ?>
                                <div class="matkul">
                                    <div class="matkul-time">
                                        <span><?= e($j['jam_mulai']) ?></span>
                                        <small>s.d.</small>
                                        <span><?= e($j['jam_selesai']) ?></span>
                                    </div>
                                    <div class="matkul-info">
                                        <h4><?= e($j['mata_kuliah']) ?> <span class="sks"><?= $j['sks'] ?> SKS</span></h4>
                                        <p>👤 <?= e($j['dosen']) ?></p>
                                        <p>📍 <?= e($j['ruang']) ?></p>
                                        <?php if ($j['catatan']): ?>
                                            <p class="catatan">ℹ️ <?= e($j['catatan']) ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- DOKUMENTASI -->
<section id="dokumentasi" class="section">
    <div class="container">
        <div class="section-head">
            <span class="section-tag">Dokumentasi</span>
            <h2>Galeri Kelas</h2>
            <p class="section-sub">Momen dan kegiatan kelas A</p>
        </div>

        <?php if ($dokumentasi->num_rows === 0): ?>
            <div class="empty">Belum ada dokumentasi. Admin dapat menambahkan foto melalui panel admin.</div>
        <?php else: ?>
            <div class="galeri-grid">
                <?php while ($d = $dokumentasi->fetch_assoc()): ?>
                    <div class="galeri-item" onclick="bukaFoto('uploads/<?= e($d['file_foto']) ?>', '<?= e($d['judul']) ?>')">
                        <img src="uploads/<?= e($d['file_foto']) ?>" alt="<?= e($d['judul']) ?>">
                        <div class="galeri-overlay">
                            <h4><?= e($d['judul']) ?></h4>
                            <small><?= date('d M Y', strtotime($d['tanggal'])) ?></small>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- PENGUMUMAN -->
<section id="pengumuman" class="section section-alt">
    <div class="container">
        <div class="section-head">
            <span class="section-tag">Info Terbaru</span>
            <h2>Pengumuman Kelas</h2>
        </div>

        <div class="pengumuman-list">
            <?php if ($pengumuman->num_rows === 0): ?>
                <div class="empty">Belum ada pengumuman.</div>
            <?php else: ?>
                <?php while ($p = $pengumuman->fetch_assoc()): ?>
                    <div class="pengumuman-item">
                        <div class="pengumuman-head">
                            <h4><?= e($p['judul']) ?></h4>
                            <small><?= date('d M Y, H:i', strtotime($p['tanggal'])) ?> • <?= e($p['penulis']) ?></small>
                        </div>
                        <p><?= nl2br(e($p['isi'])) ?></p>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="footer">
    <div class="container">
        <p>&copy; <?= date('Y') ?> Kelas A — Teknik Informatika Universitas Aisyah Pringsewu</p>
        <p class="footer-sub">Dibuat oleh Baasithu Gilang Firmansyah</p>
    </div>
</footer>

<!-- MODAL FOTO -->
<div id="modalFoto" class="modal" onclick="this.style.display='none'">
    <span class="modal-close">&times;</span>
    <img id="modalImg" src="" alt="">
    <p id="modalCaption"></p>
</div>

<script src="script.js"></script>
</body>
</html>