<?php
require_once 'config.php';

$anggota    = $conn->query("SELECT * FROM anggota ORDER BY urutan ASC, nama ASC");
$jadwal     = $conn->query("SELECT * FROM jadwal ORDER BY 
    FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'), urutan ASC");
$pengumuman = $conn->query("SELECT * FROM pengumuman ORDER BY tanggal DESC LIMIT 5");
$dokumentasi = $conn->query("SELECT * FROM dokumentasi ORDER BY tanggal DESC LIMIT 8");

$jadwal_per_hari = [];
while ($j = $jadwal->fetch_assoc()) {
    $jadwal_per_hari[$j['hari']][] = $j;
}
$hari_urutan = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];

// Ambil data perangkat kelas untuk section "Struktur"
$perangkat = $conn->query("SELECT * FROM anggota WHERE jabatan IS NOT NULL AND jabatan != '' ORDER BY urutan ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelas A Teknik Informatika 2026 | UAP</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- ===== TOP BAR ===== -->
<div class="topbar">
    <div class="container topbar-inner">
        <div class="topbar-left">
            <span>📞 +62 877-6732-2080</span>
            <span>✉️ gilangfirmansyah830@gmail.com</span>
        </div>
        <div class="topbar-right">
            <span id="clock">--:--:--</span>
            <span class="topbar-divider">|</span>
            <a href="#kontak">Kontak</a>
        </div>
    </div>
</div>

<!-- ===== NAVBAR ===== -->
<nav class="navbar">
    <div class="container nav-wrapper">
        <a href="#" class="logo">
            <img src="assets/logo.png" alt="Logo Kelas A" class="logo-img" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <div class="logo-fallback">
                <span class="logo-mark">A</span>
            </div>
            <div class="logo-text">
                <strong>KELAS A</strong>
                <small>Teknik Informatika 2026</small>
            </div>
        </a>

        <ul class="nav-menu">
            <li><a href="#beranda" class="active">BERANDA</a></li>
            <li class="has-dropdown">
                <a href="#profil">PROFIL ▾</a>
                <ul class="dropdown">
                    <li><a href="#tentang">Tentang Kelas</a></li>
                    <li><a href="#struktur">Struktur Kelas</a></li>
                </ul>
            </li>
            <li class="has-dropdown">
                <a href="#jadwal">AKADEMIK ▾</a>
                <ul class="dropdown">
                    <li><a href="#jadwal">Jadwal Kuliah</a></li>
                    <li><a href="#matkul">Mata Kuliah</a></li>
                </ul>
            </li>
            <li><a href="#dokumentasi">GALERI</a></li>
            <li class="has-dropdown">
                <a href="#pengumuman">INFORMASI ▾</a>
                <ul class="dropdown">
                    <li><a href="#pengumuman">Pengumuman</a></li>
                    <li><a href="#faq">FAQ</a></li>
                </ul>
            </li>
            <li><a href="#anggota">ANGGOTA</a></li>
            <?php if (is_admin()): ?>
                <li><a href="admin/dashboard.php" class="nav-admin">⚙️ ADMIN</a></li>
            <?php else: ?>
                <li><a href="admin/login.php" class="nav-login">LOGIN</a></li>
            <?php endif; ?>
        </ul>

        <button class="menu-toggle" onclick="toggleMenu()">☰</button>
    </div>
</nav>

<!-- ===== HERO SLIDER ===== -->
<header id="beranda" class="hero-slider">
    <div class="slide active" style="background-image: url('assets/slider1.jpeg');">
        <div class="slide-overlay"></div>
    </div>
    <div class="slide" style="background-image: url('assets/slider2.jpeg');">
        <div class="slide-overlay"></div>
    </div>
    <div class="slide" style="background-image: url('assets/slider3.jpeg');">
        <div class="slide-overlay"></div>
    </div>

    <div class="hero-content">
        <h1 class="hero-title">KELAS A</h1>
        <p class="hero-subtitle">
    <div class="hero-content">
        <h2 class="hero-title">TEKNIK INFORMATIKA 2026</h2>
        <p class="hero-subtitle">
            Satu kelas, satu tujuan, tumbuh bersama dalam teknologi, karakter, dan prestasi.
        </p>
        <p class="hero-subtitle">
          Universitas Aisyah Pringsewu.
        </p>
    </div>

    <button class="slider-arrow prev" onclick="moveSlide(-1)">‹</button>
    <button class="slider-arrow next" onclick="moveSlide(1)">›</button>

    <div class="slider-dots">
        <span class="dot active" onclick="goSlide(0)"></span>
        <span class="dot" onclick="goSlide(1)"></span>
        <span class="dot" onclick="goSlide(2)"></span>
    </div>
</header>

<!-- ===== QUICK STATS ===== -->
<section class="quick-stats">
    <div class="container">
        <div class="stats-row">
            <div class="stat-item">
                <strong><?= $conn->query("SELECT COUNT(*) c FROM anggota")->fetch_assoc()['c'] ?></strong>
                <span>Anggota Kelas</span>
            </div>
            <div class="stat-item">
                <strong>7</strong>
                <span>Mata Kuliah</span>
            </div>
            <div class="stat-item">
                <strong>19</strong>
                <span>Total SKS</span>
            </div>
            <div class="stat-item">
                <strong>5</strong>
                <span>Hari Kuliah</span>
            </div>
        </div>
    </div>
</section>

<!-- ===== TENTANG KELAS ===== -->
<section id="tentang" class="section section-about">
    <div class="container about-grid">
        <div class="about-text">
            <span class="section-label">TENTANG KELAS</span>
            <h2 class="about-title">Satu Kelas, Satu Keluarga.</h2>
            <p>
                Kelas A adalah salah satu kelas di Program Studi Teknik Informatika 
                Universitas Aisyah Pringsewu angkatan 2026. Kami percaya bahwa 
                belajar bukan hanya soal nilai, tetapi tentang tumbuh bersama.
            </p>
            <p>
                Dengan semangat kolaborasi, kami membangun lingkungan belajar yang 
                suportif, aktif, dan kreatif untuk mempersiapkan diri menjadi lulusan 
                yang siap menghadapi dunia teknologi.
            </p>
            <a href="#anggota" class="btn-cyan">Lihat Anggota Kami →</a>
        </div>
        <div class="about-img">
            <img src="assets/tentang.jpeg" alt="About Kelas A" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22600%22 height=%22400%22%3E%3Crect fill=%22%2300bcd4%22 width=%22600%22 height=%22400%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 fill=%22white%22 font-size=%2232%22 text-anchor=%22middle%22 dominant-baseline=%22middle%22%3EKelas A%3C/text%3E%3C/svg%3E'">
        </div>
    </div>
</section>

<!-- ===== STRUKTUR KELAS ===== -->
<section id="struktur" class="section section-struktur">
    <div class="container">
        <div class="section-head">
            <span class="section-label">STRUKTUR KELAS</span>
            <h2>Perangkat Kelas A</h2>
            <p class="section-sub">Pengurus yang menggerakkan kelas kami</p>
        </div>
        <div class="perangkat-grid">
            <?php 
            $perangkat->data_seek(0);
            while ($p = $perangkat->fetch_assoc()): 
            ?>
                <div class="perangkat-card">
                    <?php if (!empty($p['foto']) && file_exists("uploads/anggota/" . $p['foto'])): ?>
                        <img src="uploads/anggota/<?= e($p['foto']) ?>" alt="<?= e($p['nama']) ?>" class="perangkat-img">
                    <?php else: ?>
                        <div class="perangkat-avatar"><?= strtoupper(substr($p['nama'], 0, 1)) ?></div>
                    <?php endif; ?>
                    <h4><?= e($p['nama']) ?></h4>
                    <p class="perangkat-jabatan"><?= e($p['jabatan']) ?></p>
                    <p class="perangkat-npm"><?= e($p['npm']) ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<!-- ===== ANGGOTA ===== -->
<section id="anggota" class="section section-anggota">
    <div class="container">
        <div class="section-head">
            <span class="section-label">DAFTAR ANGGOTA</span>
            <h2>Semua Anggota Kelas A</h2>
            <p class="section-sub">Total <?= $conn->query("SELECT COUNT(*) c FROM anggota")->fetch_assoc()['c'] ?> mahasiswa</p>
        </div>

        <input type="text" id="searchAnggota" class="search-box" placeholder="🔍 Cari nama atau NPM...">

        <div class="anggota-grid" id="anggotaGrid">
            <?php $anggota->data_seek(0); while ($a = $anggota->fetch_assoc()): ?>
                <div class="anggota-card" data-search="<?= strtolower(e($a['nama']) . ' ' . e($a['npm'])) ?>">
                    <?php if (!empty($a['jabatan'])): ?>
                        <span class="jabatan-badge"><?= e($a['jabatan']) ?></span>
                    <?php endif; ?>
                    <?php if (!empty($a['foto']) && file_exists("uploads/anggota/" . $a['foto'])): ?>
                        <img src="uploads/anggota/<?= e($a['foto']) ?>" alt="<?= e($a['nama']) ?>" class="avatar-img">
                    <?php else: ?>
                        <div class="avatar"><?= strtoupper(substr($a['nama'], 0, 1)) ?></div>
                    <?php endif; ?>
                    <h4><?= e($a['nama']) ?></h4>
                    <p class="npm"><?= e($a['npm']) ?></p>
                    <?php if (!empty($a['instagram'])): ?>
                        <p class="kontak"><a href="https://instagram.com/<?= e($a['instagram']) ?>" target="_blank">📷 @<?= e($a['instagram']) ?></a></p>
                    <?php endif; ?>
                    <?php if (!empty($a['telepon'])): ?>
                        <p class="kontak"><a href="https://wa.me/62<?= ltrim(e($a['telepon']), '0') ?>" target="_blank">📱 <?= e($a['telepon']) ?></a></p>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<!-- ===== JADWAL ===== -->
<section id="jadwal" class="section section-jadwal">
    <div class="container">
        <div class="section-head">
            <span class="section-label">JADWAL MATA KULIAH</span>
            <h2>Jadwal Semester 1</h2>
            <p class="section-sub">Kelas A Teknik Informatika 2026</p>
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

<!-- ===== GALERI ===== -->
<section id="dokumentasi" class="section section-galeri">
    <div class="container">
        <div class="section-head">
            <span class="section-label">GALERI KELAS</span>
            <h2>Dokumentasi Kegiatan</h2>
            <p class="section-sub">Momen dan kenangan kelas A</p>
        </div>
        <?php if ($dokumentasi->num_rows === 0): ?>
            <div class="empty">Belum ada dokumentasi.</div>
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

<!-- ===== PENGUMUMAN ===== -->
<section id="pengumuman" class="section section-pengumuman">
    <div class="container">
        <div class="section-head">
            <span class="section-label">INFORMASI</span>
            <h2>Pengumuman Terbaru</h2>
        </div>
        <div class="pengumuman-list">
            <?php if ($pengumuman->num_rows === 0): ?>
                <div class="empty">Belum ada pengumuman.</div>
            <?php else: ?>
                <?php while ($p = $pengumuman->fetch_assoc()): ?>
                    <div class="pengumuman-item">
                        <div class="pengumuman-date">
                            <strong><?= date('d', strtotime($p['tanggal'])) ?></strong>
                            <small><?= date('M', strtotime($p['tanggal'])) ?></small>
                        </div>
                        <div class="pengumuman-body">
                            <h4><?= e($p['judul']) ?></h4>
                            <small><?= date('d M Y, H:i', strtotime($p['tanggal'])) ?> • <?= e($p['penulis']) ?></small>
                            <p><?= nl2br(e($p['isi'])) ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ===== CTA ===== -->
<section id="kontak" class="cta-band">
    <div class="container cta-inner">
        <h2>Punya Pertanyaan tentang Kelas A?</h2>
        <p>Hubungi kami lewat kontak di bawah.</p>
        <div class="cta-buttons">
            <a href="mailto:gilangfirmansyah830@gmail.com" class="btn-white">✉️ Email Kami</a>
            <a href="#anggota" class="btn-outline-white">Lihat Anggota</a>
        </div>
    </div>
</section>

<!-- ===== FOOTER ===== -->
<footer class="footer">
    <div class="container footer-grid">
        <div class="footer-col">
            <h3>KELAS A</h3>
            <p>Teknik Informatika 2026<br>Universitas Aisyah Pringsewu</p>
        </div>
        <div class="footer-col">
            <h4>Navigasi</h4>
            <ul>
                <li><a href="#beranda">Beranda</a></li>
                <li><a href="#anggota">Anggota</a></li>
                <li><a href="#jadwal">Jadwal</a></li>
                <li><a href="#dokumentasi">Galeri</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Kontak</h4>
            <p>📞 (+62) 877-6732-2080</p>
            <p>✉️ gilangfirmansyah830@gmail.com</p>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; <?= date('Y') ?> Kelas A Dibuat oleh Class A</p>
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