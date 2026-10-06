CREATE DATABASE IF NOT EXISTS kelas_a 
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE kelas_a;

-- Tabel Anggota
CREATE TABLE anggota (
    id INT AUTO_INCREMENT PRIMARY KEY,
    npm VARCHAR(20) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    jabatan VARCHAR(50) DEFAULT NULL,
    telepon VARCHAR(20) DEFAULT NULL,
    email VARCHAR(100) DEFAULT NULL,
    alamat VARCHAR(255) DEFAULT NULL,
    urutan INT DEFAULT 0
);

-- Tabel Jadwal
CREATE TABLE jadwal (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hari VARCHAR(10) NOT NULL,
    jam_mulai VARCHAR(10) NOT NULL,
    jam_selesai VARCHAR(10) NOT NULL,
    mata_kuliah VARCHAR(100) NOT NULL,
    sks INT DEFAULT 0,
    dosen VARCHAR(100) DEFAULT NULL,
    ruang VARCHAR(50) DEFAULT NULL,
    catatan VARCHAR(150) DEFAULT NULL,
    urutan INT DEFAULT 0
);

-- Tabel Pengumuman
CREATE TABLE pengumuman (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(150) NOT NULL,
    isi TEXT NOT NULL,
    tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    penulis VARCHAR(50) DEFAULT 'Admin'
);

-- Tabel Dokumentasi
CREATE TABLE dokumentasi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(150) NOT NULL,
    file_foto VARCHAR(255) NOT NULL,
    keterangan VARCHAR(255) DEFAULT NULL,
    tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Admin
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL
);

-- Admin default: username=admin, password=admin123
INSERT INTO admin (username, password, nama_lengkap) VALUES
('admin', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1HlWp/7vGf5r8vXcVqF8Q8V8V8V8V8V', 'Administrator Kelas A');
-- CATATAN: Password hash di atas adalah placeholder. 
-- Jalankan file reset_admin.php (ada di bawah) untuk generate hash yang benar.

-- ===== DATA ANGGOTA =====
INSERT INTO anggota (npm, nama, jabatan, urutan) VALUES
('260201049', 'Faqih Zayyan', 'Ketua Kelas', 1),
('260201076', 'Brian Ajiwidodo', 'Wakil Ketua', 2),
('260201010', 'Fatimatuz Zahra', 'Sekretaris', 3),
('260201020', 'Nurzikri Hafidz AS', 'Bendahara', 4),
('260201003', 'Ayu Kharisma Putri', 'Sipen', 5),
('260201007', 'Rahel Ela Arinda', NULL, 6),
('260201009', 'Muhammad Rakha Surya Putra', NULL, 7),
('260201014', 'Annawaf Pratama', NULL, 8),
('260201018', 'Maulana Bima Ramadanu', NULL, 9),
('260201021', 'Naufalen Zahir Falahan', NULL, 10),
('260201022', 'Candra Ardiansyah', NULL, 11),
('260201030', 'Zahra Naysiela', NULL, 12),
('260201032', 'Muhammad Rizky', NULL, 13),
('260201033', 'Baasithu Gilang Firmansyah', NULL, 14),
('260201035', 'Dias Faqih Zakia', NULL, 15),
('260201041', 'Nurning Setiasih', NULL, 16),
('260201045', 'Nabil Musyafa', NULL, 17),
('260201047', 'Pandji Maulana Mukti', NULL, 18),
('260201052', 'Vicky Andrean', NULL, 19),
('260201057', 'Azis Mustolih', NULL, 20),
('260201063', 'Romeo Zein Sabil', NULL, 21),
('260201069', 'Fitri Sholehatul Aulya', NULL, 22),
('260201070', 'Nadila Dwi Driani', NULL, 23),
('260201071', 'Aldi Galih Saputra', NULL, 24),
('260201072', 'Nur Azizah Listiani', NULL, 25),
('260201074', 'Fahri Ahmad Syafiq', NULL, 26),
('260201077', 'Rahmat Muarif', NULL, 27),
('260201001P', 'Ali Dwi Laksha', NULL, 28),
('260201002P', 'Achmadz Fauzan', NULL, 29),
('260201083', 'Syifa Azahra', NULL, 30),
('260201087', 'Isna Barrah Rizki', NULL, 31);

-- ===== DATA JADWAL =====
INSERT INTO jadwal (hari, jam_mulai, jam_selesai, mata_kuliah, sks, dosen, ruang, catatan, urutan) VALUES
('Senin', '08.00', '10.30', 'Grafik Komputer', 3, 'Dita Septasari, M.Kom', 'L2-R2', NULL, 1),
('Selasa', '08.00', '09.40', 'Bahasa Indonesia', 2, 'Siti Sa''idah, M.Pd.', 'L1-R1', NULL, 1),
('Selasa', '10.00', '11.40', 'Agama', 2, 'Amir Syaifurrohman, S.Ag., M.Pd', 'L3-R3-Multimedia', 'Kelas gabungan: A, C', 2),
('Rabu', '-', '-', 'LIBUR', 0, NULL, NULL, 'Tidak ada kelas', 1),
('Kamis', '08.00', '11.20', 'Dasar Pemrograman', 4, 'Nur Aminudin, S.Kom, MTI.', 'L3-L4-Lab Komputer', NULL, 1),
('Jumat', '08.00', '09.40', 'Pengantar Pemrograman Sistem', 2, 'Dwi Yana Ayu Andini, MTI.', 'L1-R1', NULL, 1),
('Jumat', '10.00', '11.40', 'Kalkulus I', 2, 'Hafsah Mukaromah, M.Kom', 'L1-R1', NULL, 2),
('Jumat', '13.00', '14.40', 'Akhlaqul Karimah I', 2, 'Indel, S.Sos., M.Pd.', 'L2-R1', NULL, 3),
('Sabtu', '16.00', '17.40', 'Pancasila', 2, 'Hardo Adriyanto, M.Pd.', 'Online', 'Kelas gabungan: A, B, C', 1),
('Minggu', '-', '-', 'LIBUR', 0, NULL, NULL, 'Libur, jangan tanya lagi 😄', 1);

-- Pengumuman contoh
INSERT INTO pengumuman (judul, isi, penulis) VALUES
('Selamat Datang di Website Kelas A', 'Website ini dibuat sebagai pusat informasi kelas A. Silakan cek jadwal, anggota, dan pengumuman terbaru secara berkala.', 'Admin');