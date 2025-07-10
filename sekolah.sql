-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 10, 2025 at 10:27 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sekolah`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensi`
--

CREATE TABLE `absensi` (
  `id` int NOT NULL,
  `siswa_id` int DEFAULT NULL,
  `mapel_id` int DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `waktu_kehadiran` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('Hadir','Izin','Sakit','Alfa') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `absensi`
--

INSERT INTO `absensi` (`id`, `siswa_id`, `mapel_id`, `tanggal`, `waktu_kehadiran`, `status`) VALUES
(4, 20231001, 20, '2025-07-02', '2025-07-02 00:10:00', 'Sakit'),
(5, 20231001, 20, '2025-07-04', '2025-07-04 00:00:00', 'Hadir'),
(6, 20231001, 20, '2025-07-09', '2025-07-09 00:03:00', 'Hadir'),
(7, 20231001, 19, '2025-07-10', '2025-07-10 00:10:10', 'Hadir');

-- --------------------------------------------------------

--
-- Table structure for table `administrator`
--

CREATE TABLE `administrator` (
  `id` int NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `agenda_guru`
--

CREATE TABLE `agenda_guru` (
  `id` int NOT NULL,
  `guru_id` int DEFAULT NULL,
  `kelas_id` int DEFAULT NULL,
  `mapel_id` int DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `pertemuan` int DEFAULT NULL,
  `materi` varchar(255) DEFAULT NULL,
  `sub_materi` text,
  `jam` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `agenda_kegiatan`
--

CREATE TABLE `agenda_kegiatan` (
  `id` int NOT NULL,
  `judul` varchar(100) NOT NULL,
  `deskripsi` text,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `tempat` varchar(100) DEFAULT NULL,
  `penanggung_jawab` varchar(100) DEFAULT NULL,
  `peserta` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `agenda_kegiatan`
--

INSERT INTO `agenda_kegiatan` (`id`, `judul`, `deskripsi`, `tanggal_mulai`, `tanggal_selesai`, `tempat`, `penanggung_jawab`, `peserta`, `created_at`) VALUES
(1, 'MPLS 2025', 'Masa Pengenalan Lingkungan Sekolah untuk siswa baru.', '2025-07-15', '2025-07-17', 'Aula Utama', 'Waka Kesiswaan', 'Siswa Baru', '2025-07-10 05:02:53'),
(2, 'Upacara Hari Kemerdekaan', 'Peringatan Hari Kemerdekaan Republik Indonesia ke-80.', '2025-08-17', NULL, 'Lapangan SMAN 1', 'Kepala Sekolah', 'Semua Siswa & Guru', '2025-07-10 05:02:53'),
(3, 'Lomba 17 Agustus', 'Lomba-lomba seperti balap karung, makan kerupuk, dll.', '2025-08-18', '2025-08-18', 'Halaman Sekolah', 'OSIS', 'Seluruh Kelas', '2025-07-10 05:02:53');

-- --------------------------------------------------------

--
-- Table structure for table `anggota_ekskul`
--

CREATE TABLE `anggota_ekskul` (
  `id` int NOT NULL,
  `siswa_id` int DEFAULT NULL,
  `ekskul_id` int DEFAULT NULL,
  `tahun_ajaran` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `anggota_mapel`
--

CREATE TABLE `anggota_mapel` (
  `id` int NOT NULL,
  `siswa_id` int NOT NULL,
  `mapel_id` int NOT NULL,
  `tanggal_gabung` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `anggota_mapel`
--

INSERT INTO `anggota_mapel` (`id`, `siswa_id`, `mapel_id`, `tanggal_gabung`) VALUES
(1, 20231001, 19, '2025-07-07 12:48:52'),
(2, 20231001, 20, '2025-07-09 22:42:28');

-- --------------------------------------------------------

--
-- Table structure for table `anggota_perpus`
--

CREATE TABLE `anggota_perpus` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `role` enum('siswa','guru') NOT NULL,
  `no_anggota` varchar(20) DEFAULT NULL,
  `alamat` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `id` int NOT NULL,
  `kode_buku` varchar(20) DEFAULT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `pengarang` varchar(100) DEFAULT NULL,
  `penerbit` varchar(100) DEFAULT NULL,
  `tahun_terbit` int DEFAULT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `stok` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ekstrakurikuler`
--

CREATE TABLE `ekstrakurikuler` (
  `id` int NOT NULL,
  `nama_ekstrakurikuler` varchar(100) DEFAULT NULL,
  `deskripsi` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guru`
--

CREATE TABLE `guru` (
  `id` int NOT NULL,
  `nip` varchar(20) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `tempat_lahir` varchar(50) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `no_telepon` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `foto` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `guru`
--

INSERT INTO `guru` (`id`, `nip`, `nama`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `no_telepon`, `email`, `foto`) VALUES
(1001, '197801010012', 'Lina Kartikasari, M.Pd.', 'P', 'Sukabumi', '1980-02-10', '081234567812', 'lina@sma.sch.id', 'lina.jpg'),
(1002, '197801010013', 'Yoga Pratama Putra, S.Pd.', 'L', 'Bandung', '1981-03-15', '081234567813', 'yoga@sma.sch.id', 'yoga.jpg'),
(1003, '197801010014', 'Sinta Marlina, S.Pd.', 'P', 'Bogor', '1982-04-20', '081234567814', 'sinta@sma.sch.id', 'sinta.jpg'),
(1004, '197801010015', 'Andi Saputra, S.Kom.', 'L', 'Sukabumi', '1983-05-25', '081234567815', 'andi@sma.sch.id', 'andi.jpg'),
(1005, '197801010016', 'Rani Nurhayati, S.Pd.', 'P', 'Cianjur', '1984-06-30', '081234567816', 'rani@sma.sch.id', 'rani.jpg'),
(1006, '197801010017', 'Drs. Dedi Firmansyah, M.Pd.', 'L', 'Garut', '1979-07-05', '081234567817', 'dedi@sma.sch.id', 'dedi.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal`
--

CREATE TABLE `jadwal` (
  `id` int NOT NULL,
  `kelas_id` int DEFAULT NULL,
  `mapel_id` int DEFAULT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat') DEFAULT NULL,
  `jam_mulai` time DEFAULT NULL,
  `jam_selesai` time DEFAULT NULL,
  `ruang` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jadwal`
--

INSERT INTO `jadwal` (`id`, `kelas_id`, `mapel_id`, `hari`, `jam_mulai`, `jam_selesai`, `ruang`) VALUES
(1, 5, 19, 'Senin', '07:00:00', '08:30:00', 'Ruang 1'),
(2, 5, 20, 'Senin', '08:30:00', '10:00:00', 'Ruang 1'),
(3, 5, 20, 'Rabu', '10:15:00', '11:45:00', 'Ruang 2'),
(4, 5, 19, 'Kamis', '09:00:00', '10:30:00', 'Ruang 1');

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id` int NOT NULL,
  `kelas` varchar(30) NOT NULL,
  `tingkat` enum('X','XI','XII') NOT NULL,
  `tahun_ajaran` varchar(9) NOT NULL,
  `wali_kelas` varchar(100) DEFAULT NULL,
  `jumlah_siswa` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id`, `kelas`, `tingkat`, `tahun_ajaran`, `wali_kelas`, `jumlah_siswa`) VALUES
(1, 'XII-1', 'XII', '2022/2023', 'Lina Kartikasari, M.Pd.', 32),
(2, 'XII-2', 'XII', '2022/2023', 'Yoga Pratama Putra, S.Pd.', 30),
(3, 'XI-1', 'XI', '2023/2024', 'Sinta Marlina, S.Pd.', 29),
(4, 'XI-2', 'XI', '2023/2024', 'Andi Saputra, S.Kom.', 27),
(5, 'X-1', 'X', '2024/2025', 'Rani Nurhayati, S.Pd.', 30),
(6, 'X-2', 'X', '2024/2025', 'Drs. Dedi Firmansyah, M.Pd.', 28);

-- --------------------------------------------------------

--
-- Table structure for table `kepsek`
--

CREATE TABLE `kepsek` (
  `id` int NOT NULL,
  `nip` varchar(20) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `jabatan` varchar(50) DEFAULT NULL,
  `masa_jabatan` varchar(50) DEFAULT NULL,
  `foto` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mapel`
--

CREATE TABLE `mapel` (
  `id` int NOT NULL,
  `nama_mapel` varchar(50) DEFAULT NULL,
  `kode_mapel` varchar(10) DEFAULT NULL,
  `guru_id` int DEFAULT NULL,
  `kelas_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `mapel`
--

INSERT INTO `mapel` (`id`, `nama_mapel`, `kode_mapel`, `guru_id`, `kelas_id`) VALUES
(19, 'Pendidikan Pancasila', 'PPX1', 1005, 5),
(20, 'Matematika Wajib', 'MTX1', 1005, 5),
(21, 'Bahasa Indonesia', 'BIX2', 1006, 6),
(22, 'Informatika', 'IFX2', 1006, 6),
(23, 'Fisika', 'FSXI1', 1003, 3),
(24, 'Kimia', 'KMXI1', 1003, 3),
(25, 'Biologi', 'BGXI2', 1004, 4),
(26, 'Sosiologi', 'SSXI2', 1004, 4),
(27, 'Bahasa Inggris', 'ENXII1', 1001, 1),
(28, 'Ekonomi', 'EKXII1', 1001, 1),
(29, 'Geografi', 'GGXII2', 1002, 2),
(30, 'Sejarah', 'SJXII2', 1002, 2);

-- --------------------------------------------------------

--
-- Table structure for table `materi`
--

CREATE TABLE `materi` (
  `id` int NOT NULL,
  `mapel_id` int DEFAULT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `deskripsi` text,
  `file` varchar(100) DEFAULT NULL,
  `tanggal_upload` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `materi`
--

INSERT INTO `materi` (`id`, `mapel_id`, `judul`, `deskripsi`, `file`, `tanggal_upload`) VALUES
(1, 19, 'Nilai Pancasila', 'Penjelasan nilai-nilai dasar dalam Pancasila.', 'nilai_pancasila.pdf', '2025-07-01'),
(2, 20, 'Persamaan Kuadrat', 'Materi tentang rumus umum dan penyelesaian persamaan kuadrat.', 'persamaan_kuadrat.docx', '2025-07-02'),
(3, 21, 'Teks Eksposisi', 'Struktur dan ciri khas teks eksposisi dalam Bahasa Indonesia.', 'teks_eksposisi.pptx', '2025-07-03'),
(4, 22, 'Dasar Pemrograman', 'Konsep logika pemrograman dasar dan algoritma.', 'dasar_pemrograman.pdf', '2025-07-03'),
(5, 24, 'Ikatan Kimia', 'Jenis dan contoh ikatan ion dan kovalen dalam senyawa kimia.', 'ikatan_kimia.pdf', '2025-07-04');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int NOT NULL,
  `nama_menu` varchar(50) DEFAULT NULL,
  `ikon` varchar(50) DEFAULT NULL,
  `keterangan` text,
  `aktif` tinyint(1) DEFAULT '1',
  `untuk_role` enum('admin','siswa','guru','kepsek','ortu') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `nama_menu`, `ikon`, `keterangan`, `aktif`, `untuk_role`) VALUES
(1, 'Beranda', 'typcn-device-desktop', 'Halaman utama siswa', 1, 'siswa'),
(2, 'Profil Siswa', 'typcn-user', 'Lihat dan edit data diri', 1, 'siswa'),
(3, 'Absensi', 'typcn-calendar-outline', 'Lihat kehadiran harian', 1, 'siswa'),
(4, 'Nilai', 'typcn-chart-bar', 'Cek nilai akademik', 1, 'siswa'),
(5, 'Jadwal Pelajaran', 'typcn-time', 'Lihat jadwal harian', 1, 'siswa'),
(6, 'Agenda', 'typcn-clipboard', 'Agenda kegiatan belajar', 1, 'siswa'),
(7, 'Pembayaran', 'typcn-credit-card', 'Tagihan dan riwayat bayar', 1, 'siswa');

-- --------------------------------------------------------

--
-- Table structure for table `nilai`
--

CREATE TABLE `nilai` (
  `id` int NOT NULL,
  `siswa_id` int DEFAULT NULL,
  `mapel_id` int DEFAULT NULL,
  `semester` varchar(10) DEFAULT NULL,
  `tahun_ajaran` varchar(10) DEFAULT NULL,
  `nilai_tugas` int DEFAULT NULL,
  `nilai_uts` int DEFAULT NULL,
  `nilai_uas` int DEFAULT NULL,
  `predikat_tugas` varchar(5) DEFAULT NULL,
  `predikat_uts` varchar(5) DEFAULT NULL,
  `predikat_uas` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `nilai`
--

INSERT INTO `nilai` (`id`, `siswa_id`, `mapel_id`, `semester`, `tahun_ajaran`, `nilai_tugas`, `nilai_uts`, `nilai_uas`, `predikat_tugas`, `predikat_uts`, `predikat_uas`) VALUES
(1, 20231001, 19, '1', '2024/2025', 85, 88, 90, 'B', 'B', 'A'),
(2, 20231001, 20, '1', '2024/2025', 78, 80, 75, 'C', 'B', 'C');

-- --------------------------------------------------------

--
-- Table structure for table `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id` int NOT NULL,
  `siswa_id` int NOT NULL,
  `judul` varchar(255) NOT NULL,
  `isi` text,
  `dibaca` tinyint(1) DEFAULT '0',
  `waktu` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ortu`
--

CREATE TABLE `ortu` (
  `id` int NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `anak_id` int DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id` int NOT NULL,
  `siswa_id` int DEFAULT NULL,
  `tagihan_id` int DEFAULT NULL,
  `jumlah_bayar` decimal(12,2) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `status` enum('Lunas','Belum Lunas','Menunggu','Diproses') DEFAULT 'Belum Lunas',
  `keterangan` text,
  `kode_va` varchar(50) DEFAULT NULL,
  `metode` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id`, `siswa_id`, `tagihan_id`, `jumlah_bayar`, `tanggal`, `status`, `keterangan`, `kode_va`, `metode`) VALUES
(1, 20231001, 1, '200000.00', '2025-07-04', 'Lunas', 'Transfer via BCA, bukti sudah dikirim', '88820100000001', 'Transfer Bank'),
(2, 20231001, 2, '150000.00', '2025-07-05', 'Menunggu', 'Menunggu konfirmasi admin', '88820100000002', 'QRIS'),
(3, 20231001, 3, '100000.00', '2025-07-06', 'Diproses', 'Pembayaran sebagian, akan dilanjut', '88820100000003', 'Transfer Mandiri');

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id` int NOT NULL,
  `anggota_id` int DEFAULT NULL,
  `buku_id` int DEFAULT NULL,
  `tanggal_pinjam` date DEFAULT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `status` enum('Dipinjam','Dikembalikan') DEFAULT 'Dipinjam'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengumpulan_tugas`
--

CREATE TABLE `pengumpulan_tugas` (
  `id` int NOT NULL,
  `tugas_id` int DEFAULT NULL,
  `siswa_id` int DEFAULT NULL,
  `file_jawaban` varchar(255) DEFAULT NULL,
  `tanggal_kumpul` datetime DEFAULT NULL,
  `nilai` int DEFAULT NULL,
  `feedback` text,
  `status` enum('Terkumpul','Terlambat') DEFAULT 'Terkumpul'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pengumpulan_tugas`
--

INSERT INTO `pengumpulan_tugas` (`id`, `tugas_id`, `siswa_id`, `file_jawaban`, `tanggal_kumpul`, `nilai`, `feedback`, `status`) VALUES
(1, 1, 20231001, 'jawaban_makalah.pdf', '2025-07-11 09:30:00', 85, 'Sudah bagus, tapi perhatikan struktur paragraf.', 'Terkumpul'),
(2, 2, 20231001, 'diskusi_online.pdf', '2025-07-15 08:00:00', NULL, NULL, 'Terkumpul'),
(3, 3, 20231001, 'jawaban_kuadrat.pdf', '2025-07-13 10:15:00', 92, 'Jawaban sangat tepat.', 'Terkumpul'),
(4, 4, 20231001, 'quiz_aljabar.pdf', '2025-07-19 07:45:00', 60, 'Terlambat sedikit, harap perhatikan waktu.', 'Terlambat');

-- --------------------------------------------------------

--
-- Table structure for table `penilaian`
--

CREATE TABLE `penilaian` (
  `id` int NOT NULL,
  `id_guru` int DEFAULT NULL,
  `id_mapel` int DEFAULT NULL,
  `id_siswa` int DEFAULT NULL,
  `semester` varchar(10) DEFAULT NULL,
  `kedisiplinan` int DEFAULT NULL,
  `kehadiran` int DEFAULT NULL,
  `sikap` int DEFAULT NULL,
  `tugas` int DEFAULT NULL,
  `uts` int DEFAULT NULL,
  `uas` int DEFAULT NULL,
  `evaluasi` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `penilaian`
--

INSERT INTO `penilaian` (`id`, `id_guru`, `id_mapel`, `id_siswa`, `semester`, `kedisiplinan`, `kehadiran`, `sikap`, `tugas`, `uts`, `uas`, `evaluasi`) VALUES
(1, 1005, 19, 20231001, '1', 90, 95, 88, 85, 88, 90, 'Aktif dan disiplin, hasil belajar baik.'),
(2, 1005, 20, 20231001, '1', 85, 90, 80, 78, 80, 75, 'Perlu peningkatan di UAS, kehadiran baik.');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` int NOT NULL,
  `nama_role` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `nama_role`) VALUES
(1, 'admin'),
(3, 'guru'),
(5, 'kepsek'),
(4, 'ortu'),
(6, 'perpus'),
(2, 'siswa');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id` int NOT NULL,
  `nisn` varchar(12) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `agama` enum('Islam','Kristen Protestan','Katolik','Hindu','Buddha','Konghucu','Lainnya') DEFAULT NULL,
  `tempat_lahir` varchar(50) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text,
  `no_telepon` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `kelas_id` int DEFAULT NULL,
  `foto` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id`, `nisn`, `nama`, `jenis_kelamin`, `agama`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `no_telepon`, `email`, `kelas_id`, `foto`) VALUES
(20231001, '004312789011', 'Nazwa Akmalia Padla', 'Perempuan', 'Islam', 'Sukabumi', '2007-06-01', 'Jl. Merdeka No.12', '083838592645', 'nazwaakmalia036@ummi.ac.id', 5, 'foto_686b486407a770.80697071.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tagihan`
--

CREATE TABLE `tagihan` (
  `id` int NOT NULL,
  `nama_tagihan` varchar(100) DEFAULT NULL,
  `jenis_tagihan` varchar(50) DEFAULT NULL,
  `total` decimal(12,2) DEFAULT NULL,
  `keterangan` text,
  `ditujukan_kepada` enum('semua','kelas','siswa') DEFAULT 'semua',
  `kelas_id` int DEFAULT NULL,
  `siswa_id` int DEFAULT NULL,
  `tanggal_tagihan` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tagihan`
--

INSERT INTO `tagihan` (`id`, `nama_tagihan`, `jenis_tagihan`, `total`, `keterangan`, `ditujukan_kepada`, `kelas_id`, `siswa_id`, `tanggal_tagihan`) VALUES
(1, 'SPP Juli 2025', 'SPP', '200000.00', 'Pembayaran SPP bulan Juli', 'siswa', 5, 20231001, '2025-07-01'),
(2, 'Ekstrakurikuler Pramuka', 'Ekstrakurikuler', '150000.00', 'Iuran kegiatan ekstrakurikuler Pramuka', 'siswa', 5, 20231001, '2025-07-02'),
(3, 'Buku Paket Semester Ganjil', 'Buku', '250000.00', 'Pembayaran buku paket semester ganjil', 'siswa', 5, 20231001, '2025-07-03');

-- --------------------------------------------------------

--
-- Table structure for table `tugas`
--

CREATE TABLE `tugas` (
  `id` int NOT NULL,
  `mapel_id` int DEFAULT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `deskripsi` text,
  `deadline` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `file_tugas` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tugas`
--

INSERT INTO `tugas` (`id`, `mapel_id`, `judul`, `deskripsi`, `deadline`, `created_at`, `file_tugas`) VALUES
(1, 19, 'Makalah Pancasila', 'Tulis makalah tentang nilai-nilai Pancasila dalam kehidupan sehari-hari.', '2025-07-12', '2025-07-01 01:00:00', 'makalah_pancasila.pdf'),
(2, 19, 'Diskusi Online', 'Ikuti forum diskusi online tentang Bhineka Tunggal Ika.', '2025-07-15', '2025-07-03 02:30:00', NULL),
(3, 20, 'Latihan Soal Persamaan Kuadrat', 'Kerjakan 10 soal tentang persamaan kuadrat di buku paket halaman 45-46.', '2025-07-14', '2025-07-02 03:00:00', 'latihan_kuadrat.pdf'),
(4, 20, 'Quiz Online Aljabar', 'Kerjakan quiz aljabar di Google Form yang telah diberikan.', '2025-07-18', '2025-07-05 01:20:00', NULL),
(5, 21, 'Analisis Puisi', 'Analisis puisi Chairil Anwar dalam 200 kata.', '2025-07-13', '2025-07-04 04:00:00', 'analisis_puisi.docx');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `id_role` int NOT NULL,
  `picture` varchar(100) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `last_login` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `fullname`, `email`, `password`, `id_role`, `picture`, `status`, `last_login`, `created_at`) VALUES
(1001, NULL, 'Lina Kartikasari, M.Pd.', 'lina@sma.sch.id', NULL, 3, 'lina.jpg', 1, '2025-07-07 04:32:29', '2025-07-07 04:32:29'),
(1002, NULL, 'Yoga Pratama Putra, S.Pd.', 'yoga@sma.sch.id', NULL, 3, 'yoga.jpg', 1, '2025-07-07 04:32:29', '2025-07-07 04:32:29'),
(1003, NULL, 'Sinta Marlina, S.Pd.', 'sinta@sma.sch.id', NULL, 3, 'sinta.jpg', 1, '2025-07-07 04:32:29', '2025-07-07 04:32:29'),
(1004, NULL, 'Andi Saputra, S.Kom.', 'andi@sma.sch.id', NULL, 3, 'andi.jpg', 1, '2025-07-07 04:32:29', '2025-07-07 04:32:29'),
(1005, NULL, 'Rani Nurhayati, S.Pd.', 'rani@sma.sch.id', NULL, 3, 'rani.jpg', 1, '2025-07-07 04:32:29', '2025-07-07 04:32:29'),
(1006, NULL, 'Drs. Dedi Firmansyah, M.Pd.', 'dedi@sma.sch.id', NULL, 3, 'dedi.jpg', 1, '2025-07-07 04:32:29', '2025-07-07 04:32:29'),
(20231001, NULL, 'NAZWA AKMALIA PADLA', 'nazwaakmalia036@ummi.ac.id', NULL, 2, 'https://lh3.googleusercontent.com/a/ACg8ocJ_q04pxF4nNbYoZZrwe5j22qR8PHJI4cvlqIbBphsrUYsSxw=s96-c', 1, '2025-07-07 00:26:32', '2025-07-06 17:26:32'),
(20231003, NULL, 'Nazwa Akmalia padla', 'nazwaakmalia569@gmail.com', NULL, 1, 'https://lh3.googleusercontent.com/a/ACg8ocIG1g7XlpyosQlq9gwdT0FGsA6M4AsUsvJlI1mLgoEDFjFIxDlD=s96-c', 1, '2025-07-10 08:08:28', '2025-07-10 01:08:28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mapel_id` (`mapel_id`),
  ADD KEY `fk_absensi_siswa` (`siswa_id`);

--
-- Indexes for table `administrator`
--
ALTER TABLE `administrator`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_admin_user` (`user_id`);

--
-- Indexes for table `agenda_guru`
--
ALTER TABLE `agenda_guru`
  ADD PRIMARY KEY (`id`),
  ADD KEY `guru_id` (`guru_id`),
  ADD KEY `mapel_id` (`mapel_id`),
  ADD KEY `fk_agenda_kelas` (`kelas_id`);

--
-- Indexes for table `agenda_kegiatan`
--
ALTER TABLE `agenda_kegiatan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `anggota_ekskul`
--
ALTER TABLE `anggota_ekskul`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ekskul_id` (`ekskul_id`),
  ADD KEY `fk_anggota_ekskul_siswa` (`siswa_id`);

--
-- Indexes for table `anggota_mapel`
--
ALTER TABLE `anggota_mapel`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `siswa_id` (`siswa_id`,`mapel_id`),
  ADD KEY `mapel_id` (`mapel_id`);

--
-- Indexes for table `anggota_perpus`
--
ALTER TABLE `anggota_perpus`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `no_anggota` (`no_anggota`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_buku` (`kode_buku`);

--
-- Indexes for table `ekstrakurikuler`
--
ALTER TABLE `ekstrakurikuler`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nip` (`nip`);

--
-- Indexes for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mapel_id` (`mapel_id`),
  ADD KEY `fk_jadwal_kelas` (`kelas_id`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kepsek`
--
ALTER TABLE `kepsek`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mapel`
--
ALTER TABLE `mapel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `guru_id` (`guru_id`),
  ADD KEY `fk_mapel_kelas` (`kelas_id`);

--
-- Indexes for table `materi`
--
ALTER TABLE `materi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mapel_id` (`mapel_id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nilai`
--
ALTER TABLE `nilai`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mapel_id` (`mapel_id`),
  ADD KEY `fk_nilai_siswa` (`siswa_id`);

--
-- Indexes for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `siswa_id` (`siswa_id`);

--
-- Indexes for table `ortu`
--
ALTER TABLE `ortu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ortu_siswa` (`anak_id`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tagihan_id` (`tagihan_id`),
  ADD KEY `fk_pembayaran_siswa` (`siswa_id`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id`),
  ADD KEY `anggota_id` (`anggota_id`),
  ADD KEY `buku_id` (`buku_id`);

--
-- Indexes for table `pengumpulan_tugas`
--
ALTER TABLE `pengumpulan_tugas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tugas_id` (`tugas_id`),
  ADD KEY `siswa_id` (`siswa_id`);

--
-- Indexes for table `penilaian`
--
ALTER TABLE `penilaian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_guru` (`id_guru`),
  ADD KEY `id_mapel` (`id_mapel`),
  ADD KEY `fk_penilaian_siswa` (`id_siswa`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nama_role` (`nama_role`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nisn` (`nisn`),
  ADD KEY `fk_siswa_kelas` (`kelas_id`);

--
-- Indexes for table `tagihan`
--
ALTER TABLE `tagihan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tagihan_kelas` (`kelas_id`),
  ADD KEY `fk_tagihan_siswa` (`siswa_id`);

--
-- Indexes for table `tugas`
--
ALTER TABLE `tugas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mapel_id` (`mapel_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `fk_users_role` (`id_role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `agenda_guru`
--
ALTER TABLE `agenda_guru`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `agenda_kegiatan`
--
ALTER TABLE `agenda_kegiatan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `anggota_ekskul`
--
ALTER TABLE `anggota_ekskul`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `anggota_mapel`
--
ALTER TABLE `anggota_mapel`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `anggota_perpus`
--
ALTER TABLE `anggota_perpus`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `buku`
--
ALTER TABLE `buku`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ekstrakurikuler`
--
ALTER TABLE `ekstrakurikuler`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `mapel`
--
ALTER TABLE `mapel`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `materi`
--
ALTER TABLE `materi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `nilai`
--
ALTER TABLE `nilai`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengumpulan_tugas`
--
ALTER TABLE `pengumpulan_tugas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `penilaian`
--
ALTER TABLE `penilaian`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tagihan`
--
ALTER TABLE `tagihan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tugas`
--
ALTER TABLE `tugas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20231004;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absensi`
--
ALTER TABLE `absensi`
  ADD CONSTRAINT `absensi_ibfk_2` FOREIGN KEY (`mapel_id`) REFERENCES `mapel` (`id`),
  ADD CONSTRAINT `fk_absensi_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `administrator`
--
ALTER TABLE `administrator`
  ADD CONSTRAINT `administrator_ibfk_1` FOREIGN KEY (`id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_admin_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `agenda_guru`
--
ALTER TABLE `agenda_guru`
  ADD CONSTRAINT `agenda_guru_ibfk_1` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id`),
  ADD CONSTRAINT `agenda_guru_ibfk_3` FOREIGN KEY (`mapel_id`) REFERENCES `mapel` (`id`),
  ADD CONSTRAINT `fk_agenda_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `anggota_ekskul`
--
ALTER TABLE `anggota_ekskul`
  ADD CONSTRAINT `anggota_ekskul_ibfk_2` FOREIGN KEY (`ekskul_id`) REFERENCES `ekstrakurikuler` (`id`),
  ADD CONSTRAINT `fk_anggota_ekskul_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `anggota_mapel`
--
ALTER TABLE `anggota_mapel`
  ADD CONSTRAINT `anggota_mapel_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `anggota_mapel_ibfk_2` FOREIGN KEY (`mapel_id`) REFERENCES `mapel` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `anggota_perpus`
--
ALTER TABLE `anggota_perpus`
  ADD CONSTRAINT `anggota_perpus_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `guru`
--
ALTER TABLE `guru`
  ADD CONSTRAINT `guru_ibfk_1` FOREIGN KEY (`id`) REFERENCES `users` (`id`);

--
-- Constraints for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD CONSTRAINT `fk_jadwal_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `jadwal_ibfk_2` FOREIGN KEY (`mapel_id`) REFERENCES `mapel` (`id`);

--
-- Constraints for table `kepsek`
--
ALTER TABLE `kepsek`
  ADD CONSTRAINT `kepsek_ibfk_1` FOREIGN KEY (`id`) REFERENCES `users` (`id`);

--
-- Constraints for table `mapel`
--
ALTER TABLE `mapel`
  ADD CONSTRAINT `fk_mapel_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `mapel_ibfk_1` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id`);

--
-- Constraints for table `materi`
--
ALTER TABLE `materi`
  ADD CONSTRAINT `materi_ibfk_1` FOREIGN KEY (`mapel_id`) REFERENCES `mapel` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `nilai`
--
ALTER TABLE `nilai`
  ADD CONSTRAINT `fk_nilai_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nilai_ibfk_2` FOREIGN KEY (`mapel_id`) REFERENCES `mapel` (`id`);

--
-- Constraints for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD CONSTRAINT `notifikasi_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ortu`
--
ALTER TABLE `ortu`
  ADD CONSTRAINT `fk_ortu_siswa` FOREIGN KEY (`anak_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ortu_ibfk_1` FOREIGN KEY (`id`) REFERENCES `users` (`id`);

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `fk_pembayaran_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pembayaran_ibfk_2` FOREIGN KEY (`tagihan_id`) REFERENCES `tagihan` (`id`);

--
-- Constraints for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `peminjaman_ibfk_1` FOREIGN KEY (`anggota_id`) REFERENCES `anggota_perpus` (`id`),
  ADD CONSTRAINT `peminjaman_ibfk_2` FOREIGN KEY (`buku_id`) REFERENCES `buku` (`id`);

--
-- Constraints for table `pengumpulan_tugas`
--
ALTER TABLE `pengumpulan_tugas`
  ADD CONSTRAINT `pengumpulan_tugas_ibfk_1` FOREIGN KEY (`tugas_id`) REFERENCES `tugas` (`id`),
  ADD CONSTRAINT `pengumpulan_tugas_ibfk_2` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`);

--
-- Constraints for table `penilaian`
--
ALTER TABLE `penilaian`
  ADD CONSTRAINT `fk_penilaian_siswa` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `penilaian_ibfk_1` FOREIGN KEY (`id_guru`) REFERENCES `guru` (`id`),
  ADD CONSTRAINT `penilaian_ibfk_2` FOREIGN KEY (`id_mapel`) REFERENCES `mapel` (`id`);

--
-- Constraints for table `siswa`
--
ALTER TABLE `siswa`
  ADD CONSTRAINT `fk_siswa_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_siswa_user` FOREIGN KEY (`id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tagihan`
--
ALTER TABLE `tagihan`
  ADD CONSTRAINT `fk_tagihan_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tagihan_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tugas`
--
ALTER TABLE `tugas`
  ADD CONSTRAINT `tugas_ibfk_1` FOREIGN KEY (`mapel_id`) REFERENCES `mapel` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_role` FOREIGN KEY (`id_role`) REFERENCES `role` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
