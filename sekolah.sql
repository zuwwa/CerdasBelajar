-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 18, 2025 at 08:41 AM
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
-- Table structure for table `agenda_guru`
--

CREATE TABLE `agenda_guru` (
  `id` int NOT NULL,
  `tanggal` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pertemuan` int NOT NULL,
  `materi` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `sub_materi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama_guru` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `kelas` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `mata_pelajaran` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nuptk` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jam` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `agenda_guru`
--

INSERT INTO `agenda_guru` (`id`, `tanggal`, `pertemuan`, `materi`, `sub_materi`, `nama_guru`, `kelas`, `mata_pelajaran`, `nuptk`, `jam`) VALUES
(1, '2023-07-21', 10, 'Integral', 'Mengenal dan latihan integral', 'Kim Minji', '1', 'Fisika', '040507', '10:20'),
(10, '2023-07-28', 6, 'Eksponen 2', 'Ulangan Harian', 'Danielle Marsh', '11', 'Matematika', '050411', '9:00'),
(11, '19/07/2023', 3, 'Kalkulus', 'Penjelasan kalkulus', 'Kang Haerin', '11', 'Matematika', '060515', ''),
(12, '18/07/2023', 8, 'Aritmatika', 'UAS', 'Lee Hyein', '11', 'Matematika', '080421', ''),
(21, '17/07/2023', 1, 'test delete', 'Test delete', 'Test Delete', '10', 'Matematika', '080808', ''),
(22, '17/07/2023', 1, 'Test Edit', 'Test Edit', 'Test Edit', '10', 'Matematika', '212121', '');

-- --------------------------------------------------------

--
-- Table structure for table `agenda_kegiatan`
--

CREATE TABLE `agenda_kegiatan` (
  `id` int NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text,
  `tempat` varchar(255) DEFAULT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `penanggung_jawab` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `agenda_kegiatan`
--

INSERT INTO `agenda_kegiatan` (`id`, `judul`, `deskripsi`, `tempat`, `tanggal_mulai`, `tanggal_selesai`, `penanggung_jawab`) VALUES
(1, 'MPLS 2025', 'Masa Pengenalan Lingkungan Sekolah untuk siswa baru.', 'Aula Utama', '2025-07-15', '2025-07-17', 'Wakasek Kesiswaan'),
(2, 'Lomba 17 Agustus', 'Berbagai lomba kemerdekaan antar kelas.', 'Lapangan', '2025-08-17', NULL, 'OSIS');

-- --------------------------------------------------------

--
-- Table structure for table `anggota_mapel`
--

CREATE TABLE `anggota_mapel` (
  `id` int NOT NULL,
  `siswa_id` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mapel_id` int NOT NULL,
  `tanggal_gabung` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `anggota_mapel`
--

INSERT INTO `anggota_mapel` (`id`, `siswa_id`, `mapel_id`, `tanggal_gabung`) VALUES
(5, '2330511036', 13, '2025-07-12 10:34:57'),
(6, '2330511036', 1, '2025-07-13 11:35:35');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int NOT NULL,
  `BookID` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Author` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Publisher` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `PublicationYear` int DEFAULT NULL,
  `Category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `AvailableCount` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `BookID`, `Title`, `Author`, `Publisher`, `PublicationYear`, `Category`, `AvailableCount`) VALUES
(1, '2', 'Book 2', 'Author 2', 'Publisher 2', 2002, 'Category 2', NULL),
(2, '3', 'Book 3', 'Author 3', 'Publisher 3', 2003, 'Category 1', NULL),
(3, '4', 'Book 4', 'Author 4', 'Publisher 4', 2004, 'Category 3', NULL),
(4, '5', 'Book 5', 'Author 5', 'Publisher 5', 2005, 'Category 2', NULL),
(5, '6', 'Book 6', 'Author 6', 'Publisher 6', 2006, 'Category 1', NULL),
(6, '7', 'Book 7', 'Author 7', 'Publisher 7', 2007, 'Category 2', NULL),
(7, '8', 'Book 8', 'Author 8', 'Publisher 8', 2008, 'Category 3', NULL),
(8, '9', 'Book 9', 'Author 9', 'Publisher 9', 2009, 'Category 1', NULL),
(10, 'oghje;kgle', 'Petruk Gareng', 'UMMI', 'UMMI', 2020, 'Fiksi', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `books_0`
--

CREATE TABLE `books_0` (
  `BookID` int NOT NULL,
  `Title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Author` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Publisher` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `PublicationYear` int DEFAULT NULL,
  `Category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `AvailableCount` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books_0`
--

INSERT INTO `books_0` (`BookID`, `Title`, `Author`, `Publisher`, `PublicationYear`, `Category`, `AvailableCount`) VALUES
(1, 'Book 1', 'Author 1', NULL, NULL, 'Category 1', NULL),
(2, 'Book 2', 'Author 2', NULL, NULL, 'Category 2', NULL),
(3, 'Book 3', 'Author 3', NULL, NULL, 'Category 1', NULL),
(4, 'Book 4', 'Author 4', NULL, NULL, 'Category 3', NULL),
(5, 'Book 5', 'Author 5', NULL, NULL, 'Category 2', NULL),
(6, 'Book 6', 'Author 6', NULL, NULL, 'Category 1', NULL),
(7, 'Book 7', 'Author 7', NULL, NULL, 'Category 2', NULL),
(8, 'Book 8', 'Author 8', NULL, NULL, 'Category 3', NULL),
(9, 'Book 9', 'Author 9', NULL, NULL, 'Category 1', NULL),
(10, 'Book 10', 'Author 10', NULL, NULL, 'Category 2', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jadwal`
--

CREATE TABLE `jadwal` (
  `id` int NOT NULL,
  `id_mapel` int NOT NULL,
  `id_kelas` int NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `ruang` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jadwal`
--

INSERT INTO `jadwal` (`id`, `id_mapel`, `id_kelas`, `hari`, `jam_mulai`, `jam_selesai`, `ruang`, `created_at`) VALUES
(1, 13, 20, 'Senin', '07:00:00', '08:30:00', 'Ruang 1', '2025-07-12 03:23:55'),
(2, 13, 20, 'Selasa', '07:00:00', '08:30:00', 'Ruang 1', '2025-07-12 03:23:55'),
(3, 13, 20, 'Rabu', '07:00:00', '08:30:00', 'Ruang 1', '2025-07-12 03:23:55'),
(4, 13, 20, 'Kamis', '07:00:00', '08:30:00', 'Ruang 1', '2025-07-12 03:23:55'),
(5, 13, 20, 'Jumat', '07:00:00', '08:30:00', 'Ruang 1', '2025-07-12 03:23:55');

-- --------------------------------------------------------

--
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `LoanID` int NOT NULL,
  `BookID` int DEFAULT NULL,
  `MemberID` int DEFAULT NULL,
  `LoanDate` date DEFAULT NULL,
  `DueDate` date DEFAULT NULL,
  `ReturnDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loans`
--

INSERT INTO `loans` (`LoanID`, `BookID`, `MemberID`, `LoanDate`, `DueDate`, `ReturnDate`) VALUES
(4, 4, 4, '2002-02-02', '2002-02-09', '2002-04-04'),
(2, 2, 2, '2023-07-17', '2023-07-24', '2023-07-19'),
(500, 900, 900, '2023-07-18', '2023-07-25', '2003-04-04'),
(4, 4, 4, '2002-02-02', '2002-02-09', '2002-04-04'),
(4, 4, 4, '2002-02-02', '2002-02-09', '2002-04-04'),
(3, 0, 0, '2023-07-28', '2023-08-04', '2023-07-28');

-- --------------------------------------------------------

--
-- Table structure for table `loans_0`
--

CREATE TABLE `loans_0` (
  `LoanID` int NOT NULL,
  `BookID` int DEFAULT NULL,
  `MemberID` int DEFAULT NULL,
  `LoanDate` date DEFAULT NULL,
  `DueDate` date DEFAULT NULL,
  `ReturnDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loans_0`
--

INSERT INTO `loans_0` (`LoanID`, `BookID`, `MemberID`, `LoanDate`, `DueDate`, `ReturnDate`) VALUES
(1, 1, 1, '2023-05-01', '2023-05-15', '2023-05-16'),
(2, 2, 2, '2023-05-02', '2023-05-16', '2023-05-14'),
(3, 3, 3, '2023-05-03', '2023-05-17', '2023-05-19'),
(4, 4, 4, '2023-05-04', '2023-05-18', '2023-05-17'),
(5, 5, 5, '2023-05-05', '2023-05-19', '2023-05-20'),
(6, 6, 6, '2023-05-06', '2023-05-20', '2023-05-21'),
(7, 7, 7, '2023-05-07', '2023-05-21', '2023-05-23'),
(8, 8, 8, '2023-05-08', '2023-05-22', '2023-05-20'),
(9, 9, 9, '2023-05-09', '2023-05-23', '2023-05-24'),
(10, 10, 10, '2023-05-10', '2023-05-24', '2023-05-23'),
(11, 1, 1, '2023-05-01', '2023-05-15', NULL),
(12, 2, 2, '2023-05-01', '2023-05-15', NULL),
(13, 3, 3, '2023-05-01', '2023-05-15', NULL),
(14, 4, 4, '2023-05-01', '2023-05-15', NULL),
(15, 5, 5, '2023-05-01', '2023-05-15', NULL),
(16, 1, 1, '2023-05-09', '2023-06-01', NULL),
(17, 1, 4, '2023-05-11', '2023-06-01', NULL),
(18, 1, 1, '2023-05-12', '2023-06-01', NULL),
(19, 2, 6, '2023-05-12', '2023-06-07', NULL),
(20, 4, 4, '2023-05-13', '2023-06-01', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mata_pelajaran`
--

CREATE TABLE `mata_pelajaran` (
  `id` int NOT NULL,
  `Kode` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Nama` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Guru` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mata_pelajaran`
--

INSERT INTO `mata_pelajaran` (`id`, `Kode`, `Nama`, `Guru`) VALUES
(2, '002', 'Bahasa Indonesia', 'Imas Kusmiati'),
(3, '003', 'Bahasa Inggris', 'Dina Mardiah'),
(4, '004', 'Kimia', 'Lilis Nurlela'),
(5, '005', 'Biologi', 'Ade Rustandy'),
(6, '006', 'PPKn', 'Eni Nurhaeni'),
(7, '007', 'Geografi', 'Iman Suratman'),
(8, '008', 'Sosiologi', 'Anggia Amanda'),
(9, '009', 'Fisika', 'Lilis Kurniasih'),
(10, '010', 'PAI', 'Herwan'),
(11, '011', 'Bahasa Arab', 'Elsan Nasrillah'),
(12, '012', 'Seni Budaya', 'Kusuma Dwi Prasetia'),
(13, '013', 'PKWU', 'Peni Apriani'),
(14, '014', 'Sejarah Minat', 'Putri Permata Sakti'),
(15, '015', 'PJOK', 'Kartika Pamungkas');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `MemberID` int NOT NULL,
  `Name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `PhoneNumber` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`MemberID`, `Name`, `Address`, `PhoneNumber`, `Email`) VALUES
(0, NULL, NULL, NULL, NULL),
(1, 'John Doe', '123 Main Street', '1234567890', 'johndoe@example.com'),
(2, 'Jane Smith', '456 Elm Street', '9876543210', 'janesmith@example.com'),
(3, 'Michael Johnson', '789 Oak Street', '1111111111', 'michaeljohnson@example.com'),
(4, 'Emily Davis', '321 Pine Street', '2222222222', 'emilydavis@example.com'),
(5, 'Daniel Wilson', '654 Maple Street', '3333333333', 'danielwilson@example.com'),
(6, 'Olivia Brown', '987 Cedar Street', '4444444444', 'oliviabrown@example.com'),
(7, 'William Taylor', '654 Birch Street', '5555555555', 'williamtaylor@example.com'),
(8, 'Sophia Anderson', '321 Spruce Street', '6666666666', 'sophiaanderson@example.com'),
(9, 'James Lee', '789 Walnut Street', '7777777777', 'jameslee@example.com'),
(10, 'Ava Martinez', '123 Cherry Street', '8888888888', 'avamartinez@example.com');

-- --------------------------------------------------------

--
-- Table structure for table `members_0`
--

CREATE TABLE `members_0` (
  `MemberID` int NOT NULL,
  `Name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `PhoneNumber` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members_0`
--

INSERT INTO `members_0` (`MemberID`, `Name`, `Address`, `PhoneNumber`, `Email`) VALUES
(0, NULL, NULL, NULL, NULL),
(1, 'John Doe', '123 Main Street', '1234567890', 'johndoe@example.com'),
(2, 'Jane Smith', '456 Elm Street', '9876543210', 'janesmith@example.com'),
(3, 'Michael Johnson', '789 Oak Street', '1111111111', 'michaeljohnson@example.com'),
(4, 'Emily Davis', '321 Pine Street', '2222222222', 'emilydavis@example.com'),
(5, 'Daniel Wilson', '654 Maple Street', '3333333333', 'danielwilson@example.com'),
(6, 'Olivia Brown', '987 Cedar Street', '4444444444', 'oliviabrown@example.com'),
(7, 'William Taylor', '654 Birch Street', '5555555555', 'williamtaylor@example.com'),
(8, 'Sophia Anderson', '321 Spruce Street', '6666666666', 'sophiaanderson@example.com'),
(9, 'James Lee', '789 Walnut Street', '7777777777', 'jameslee@example.com'),
(10, 'Ava Martinez', '123 Cherry Street', '8888888888', 'avamartinez@example.com');

-- --------------------------------------------------------

--
-- Table structure for table `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id` int NOT NULL,
  `siswa_id` int DEFAULT NULL,
  `judul` varchar(100) DEFAULT NULL,
  `isi` text,
  `waktu` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `nis` int NOT NULL,
  `nama` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `kelas` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `alamat` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jml_bayar` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jenis_tagihan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`nis`, `nama`, `kelas`, `alamat`, `jml_bayar`, `jenis_tagihan`) VALUES
(2130511001, 'Aldy Ramadani', '9A', 'Sukalarang, Kabupaten Sukabumi', 'Rp. 500.000', 'SPP'),
(2130511002, 'Sandia Anggara', '9B', 'Kadudampit, Kabupaten Sukabumi', 'Rp. 1.000.000', 'Study Tour'),
(2130511003, 'Kayla Sandi Putri', '9C', 'Cisaat, Kabupaten Sukabumi', 'Rp. 200.000', 'UTS'),
(2130511004, 'Abdul Gofar Fauzan', '9D', 'Gunung Puyuh, Kota Sukabumi', 'Rp. 300.000', 'UAS'),
(2130511005, 'Haikal Lukman', '9E', 'Sukalarang, Kabupaten Sukabumi', 'Rp. 1.500.000', 'Seragam'),
(2130511006, 'Muhamad Agan Suganda', '9A', 'Sukalarang, Kabupaten Sukabumi', 'Rp. 300.000', 'LKS'),
(2130511007, 'Alviani Nur Rahmadanti', '9B', 'Baros, Kota Sukabumi', 'Lunas', 'SPP'),
(2130511008, 'Reyhan Khidir', '9C', 'Cisaat, Kabupaten Sukabumi', 'Lunas', 'SPP'),
(2130511009, 'M. Herdi Al-Fachri', '9D', 'Citamiang, Kota Sukabumi', 'Lunas', 'Study Tour'),
(2130511010, 'Agung Prayoga', '9E', 'Jampang Kulon, Kabupaten Sukabumi', 'Lunas', 'Study Tour'),
(2130511011, 'Raihan Herlambang', '9A', 'Cikole, Kota Sukabumi', 'Lunas', 'LKS'),
(2130511012, 'Aisya Syakira Purnama', '9B', 'Cicurug, Kabupaten Sukabumi', 'Lunas', 'LKS'),
(2130511013, 'Ziyah Sakinah', '9C', 'Cikodang, Kota Sukabumi', 'Lunas', 'Seragam'),
(2130511014, 'Raina Rahmawati', '9D', 'Nangeleng, Kota Sukabumi', 'Lunas', 'Seragam'),
(2130511015, 'Siti Nurazizah', '9E', 'Cicurug, Kabupaten Sukabumi', 'Lunas', 'UTS'),
(2130511016, 'Kevin Saputra', '9A', 'Ciaul, Kota Sukabumi', 'Lunas', 'UTS'),
(2130511017, 'Agung Syahril', '9B', 'Sagaranten, Kabupaten Sukabumi', 'Lunas', 'UAS'),
(2130511018, 'Wili Septiandi', '9C', 'Sagaranten, Kabupaten Sukabumi', 'Lunas', 'UAS');

-- --------------------------------------------------------

--
-- Table structure for table `pengumpulan_tugas`
--

CREATE TABLE `pengumpulan_tugas` (
  `id` int NOT NULL,
  `tugas_id` int NOT NULL,
  `siswa_id` int NOT NULL,
  `file_jawaban` varchar(255) DEFAULT NULL,
  `catatan` text,
  `nilai` int DEFAULT NULL,
  `tanggal_upload` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pengumpulan_tugas`
--

INSERT INTO `pengumpulan_tugas` (`id`, `tugas_id`, `siswa_id`, `file_jawaban`, `catatan`, `nilai`, `tanggal_upload`) VALUES
(1, 1, 3, '1752380859_WhatsApp_Image_2024_07_04_at_20.11.31_6d3aa99c.jpg', NULL, NULL, '2025-07-13 04:27:39');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `nisn` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(30) NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tempat_lahir` varchar(30) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `agama` varchar(20) NOT NULL,
  `alamat` varchar(100) NOT NULL,
  `no_telepon` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`nisn`, `nama`, `email`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `alamat`, `no_telepon`) VALUES
('12345', 'Raden Ajeng Ayu Hanum Trihapsa', NULL, 'Ngayojoakrto Hadiningrat', '2021-01-10', 'Perempuan', 'Islam', 'Panembahan Senopati Raya 60,Yogyakarta', '02746728293'),
('1430511056', 'HAMZAH MAULANA PRATAMA', NULL, 'Jakarta', '2000-02-01', 'Laki-laki', 'Islam', 'Jalan Gatot Subroto No. 10, Sukabumi', '085758857775'),
('1730511026', 'ALFRIDA SALSA FEBIOLA', NULL, 'Jakarta', '1998-05-02', 'Laki-laki', 'Islam', 'Jalan Raya Cissat  No. 15, Cisaat Sukabumi', '085789892909'),
('1830511002', 'HANDIKA FEBRIAN', NULL, 'Purwokerto', '1999-05-15', 'Laki-laki', 'Islam', 'Sukaraja Sukabumi', '085669919769'),
('1830511003', 'TAUFIQ HIDAYATULLAH', NULL, 'Sukabumi', '1999-12-19', 'Laki-laki', 'Islam', 'Jalan Radin Intan No. 77, Karang Tengah, Sukabumi', '089977955772'),
('1830511120', 'RHEZA FAHRY ABDILLAH', NULL, 'Jakarta', '1995-06-06', 'Perempuan', 'Islam', 'Jalan Ahmad Yani,Sukabumi', '081388955767'),
('1830521019', 'DITO ADITYA', NULL, 'Manado', '2001-01-20', 'Laki-laki', 'Islam', 'Jalan Pemuda Sukabumi', '081269962201'),
('2330511036', 'Nazwa Akmalia Padla', 'nazwaakmalia036@ummi.ac.id', 'Sukabumi', '2004-01-10', 'Perempuan', 'Islam', 'Jl. Pajajaran 1 KM.4 Desa Cikujang', '08123456789');

-- --------------------------------------------------------

--
-- Table structure for table `tabel_pinjaman`
--

CREATE TABLE `tabel_pinjaman` (
  `id_pinjam` int NOT NULL,
  `nama` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nis` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `judul_buku` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tgl_peminjaman` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tgl_pengembalian` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `no_tlp` int NOT NULL,
  `id_sekolah` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tabel_pinjaman`
--

INSERT INTO `tabel_pinjaman` (`id_pinjam`, `nama`, `nis`, `judul_buku`, `tgl_peminjaman`, `tgl_pengembalian`, `no_tlp`, `id_sekolah`) VALUES
(1, 'Aldy Ramadani', '101', 'Laskar Pelangi', '2 April 2023', '10 April 2023', 628131415, 0),
(2, 'Salman Dermawan', '102', 'The Lord of the Rings', '14 April 2023', '20 April 2023', 628131416, 0),
(3, 'Aziel', '103', 'Komik Detektive Conan', '1 Mei 2023', '13 Mei 2023', 628131417, 0),
(5, 'asep', '21', 'test', '2 April 2023', '2 April 2023', 98, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tagihan`
--

CREATE TABLE `tagihan` (
  `nis` int NOT NULL,
  `nama` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `kelas` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `alamat` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jml_tagihan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jenis_tagihan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tagihan`
--

INSERT INTO `tagihan` (`nis`, `nama`, `kelas`, `alamat`, `jml_tagihan`, `jenis_tagihan`) VALUES
(2130511001, 'Aldy Ramadani', '9A', 'Sukalarang, Kabupaten Sukabumi', 'Rp. 500.000', 'SPP'),
(2130511002, 'Sandia Anggara', '9B', 'Kadudampit, Kabupaten Sukabumi', 'Rp. 1.000.000', 'Study Tour'),
(2130511003, 'Kayla Sandi Putri', '9C', 'Cisaat, Kabupaten Sukabumi', 'Rp. 200.000', 'UTS'),
(2130511004, 'Abdul Gofar Fauzan', '9D', 'Gunung Puyuh, Kota Sukabumi', 'Rp. 300.000', 'UAS'),
(2130511005, 'Haikal Lukman', '9E', 'Sukalarang, Kabupaten Sukabumi', 'Rp. 1.500.000', 'Seragam'),
(2130511006, 'Muhamad Agan Suganda', '9A', 'Sukalarang, Kabupaten Sukabumi', 'Rp. 300.000', 'LKS');

-- --------------------------------------------------------

--
-- Table structure for table `tb_nilai`
--

CREATE TABLE `tb_nilai` (
  `id_siswa` int NOT NULL,
  `mata_pelajaran` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `kkm` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nilai_mapel` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `predikat_mapel` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nilai_tugas` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `predikat_tugas` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nilai_uts` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `predikat_uts` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nilai_uas` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `predikat_uas` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_nilai`
--

INSERT INTO `tb_nilai` (`id_siswa`, `mata_pelajaran`, `kkm`, `nilai_mapel`, `predikat_mapel`, `nilai_tugas`, `predikat_tugas`, `nilai_uts`, `predikat_uts`, `nilai_uas`, `predikat_uas`) VALUES
(25, 'Matematika', '70', '80', 'B', '80', 'B', '80', 'B', '80', 'B'),
(26, 'Akidah Ahklak', '80', '90', 'A', '90', 'A', '90', 'A', '90', 'A'),
(27, 'B.Inggris', '70', '85', 'B', '85', 'B', '85', 'B', '85', 'B'),
(36, 'Fisika', '70', '85', 'A', '85', 'A', '85', 'A', '85', 'A'),
(37, 'Sejarah', '70', '90', 'A', '90', 'A', '90', 'A', '90', 'A');

-- --------------------------------------------------------

--
-- Table structure for table `tugas`
--

CREATE TABLE `tugas` (
  `id` int NOT NULL,
  `mapel_id` int NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text,
  `deadline` datetime NOT NULL,
  `dibuat_pada` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tugas`
--

INSERT INTO `tugas` (`id`, `mapel_id`, `judul`, `deskripsi`, `deadline`, `dibuat_pada`) VALUES
(1, 1, 'Tugas Fisika Bab 1', 'Kerjakan soal dari buku halaman 20-25', '2025-07-15 23:59:00', '2025-07-11 04:28:48'),
(2, 2, 'Tugas Bahasa Indonesia', 'Tulis ringkasan cerpen', '2025-07-14 18:00:00', '2025-07-11 04:28:48');

-- --------------------------------------------------------

--
-- Table structure for table `t_absensi`
--

CREATE TABLE `t_absensi` (
  `id` int NOT NULL,
  `nama` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `kelas` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `waktu_kehadiran` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `id_siswa` int NOT NULL,
  `id_mapel` int NOT NULL,
  `materi_id` int DEFAULT NULL,
  `id_kelas` int NOT NULL,
  `id_sekolah` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_absensi`
--

INSERT INTO `t_absensi` (`id`, `nama`, `kelas`, `waktu_kehadiran`, `id_siswa`, `id_mapel`, `materi_id`, `id_kelas`, `id_sekolah`) VALUES
(160, 'Nazwa Akmalia Padla', '20', '2025-07-11 23:02:16', 11, 1, 1, 10, 1);

-- --------------------------------------------------------

--
-- Table structure for table `t_administrator`
--

CREATE TABLE `t_administrator` (
  `id` int NOT NULL,
  `nama_pengguna` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `kata_kunci` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama_sekolah` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Jenjang` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `foto_sekolah` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email_pengguna` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_administrator`
--

INSERT INTO `t_administrator` (`id`, `nama_pengguna`, `username`, `kata_kunci`, `nama_sekolah`, `Jenjang`, `foto_sekolah`, `email_pengguna`) VALUES
(1, 'admin SMP 1', 'adminsmp', '202cb962ac59075b964b07152d234b70', 'SMP Negeri 1', '', '', 'asriladi@ummi.ac.id'),
(2, 'Admin Sekolah', 'smp2', '202cb962ac59075b964b07152d234b70', 'SMP Negeri 2 Kota Sukabumi', 'SMP', '', 'smp2@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `t_data`
--

CREATE TABLE `t_data` (
  `id` int NOT NULL,
  `nama` varchar(50) NOT NULL,
  `noinduk` varchar(10) NOT NULL,
  `kelas` varchar(20) NOT NULL,
  `kehadiran` varchar(3) NOT NULL,
  `tugas` varchar(3) NOT NULL,
  `evaluasi` varchar(3) NOT NULL,
  `kedisiplinan` varchar(3) NOT NULL,
  `nilai` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `t_data`
--

INSERT INTO `t_data` (`id`, `nama`, `noinduk`, `kelas`, `kehadiran`, `tugas`, `evaluasi`, `kedisiplinan`, `nilai`) VALUES
(1, 'Reyhan', '2113322', '12 IPA 4', '90', '85', '85', '90', 90),
(2, 'Muhamad Zildan TM', '2114433', '12 IPA 4', '85', '90', '90', '85', 85),
(3, 'Muhammad Rafi', '2115544', '12 IPA 4', '90', '90', '85', '85', 90),
(4, 'M. Herdi Al-Fachri', '2116655', '12 IPA 4', '85', '85', '90', '85', 85),
(5, 'M. Ripal Perdiansyah', '2117766', '12 IPA 4', '90', '85', '90', '85', 90),
(6, 'Zulhaydar Fathurrahman Sidiq', '2118899', '12 IPA 4', '90', '85', '85', '90', 85);

-- --------------------------------------------------------

--
-- Table structure for table `t_ekstrakurikuler`
--

CREATE TABLE `t_ekstrakurikuler` (
  `id` int NOT NULL,
  `ekstrakurikuler` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_sekolah` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_ekstrakurikuler`
--

INSERT INTO `t_ekstrakurikuler` (`id`, `ekstrakurikuler`, `deskripsi`, `id_sekolah`) VALUES
(1, 'Senam', 'Senam lantai', 0),
(2, 'Futsal', 'Olahraga', 0),
(100, 'Basket', 'Olahraga', 0);

-- --------------------------------------------------------

--
-- Table structure for table `t_grafiknilai`
--

CREATE TABLE `t_grafiknilai` (
  `id` int NOT NULL,
  `mata_pelajaran` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `kkm` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nilai_mapel` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nilai_keterampilan` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nilai_uts` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nilai_uas` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_kelas` int NOT NULL,
  `id_sekolah` int NOT NULL,
  `tahun_pelajaran` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `semester` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nis` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_grafiknilai`
--

INSERT INTO `t_grafiknilai` (`id`, `mata_pelajaran`, `kkm`, `nilai_mapel`, `nilai_keterampilan`, `nilai_uts`, `nilai_uas`, `id_kelas`, `id_sekolah`, `tahun_pelajaran`, `semester`, `nis`) VALUES
(1, 'Matematika', '70', '80', '89', '78', '90', 1, 1, '2021/2022', 'genap', 1),
(2, 'Matematika', '70', '80', '78', '60', '80', 2, 1, '2022/2023', 'genap', 1),
(3, 'Matematika', '80', '90', '90', '87', '78', 1, 1, '2023/2024', 'genap', 1),
(4, 'IPA', '70', '85', '85', '90', '98', 1, 1, '2021/2022', 'genap', 1),
(5, 'IPA', '70', '85', '85', '78', '80', 1, 1, '2022/2023', 'genap', 1),
(6, 'IPA', '70', '90', '90', '89', '80', 1, 1, '2023/2024', 'genap', 1),
(7, 'Sejarah', '80', '90', '90', '89', '95', 2, 1, '2021/2022', 'genap', 1),
(8, 'Sejarah', '80', '85', '85', '67', '90', 3, 1, '2022/2023', 'genap', 1),
(9, 'Bahasa Indonesia', '70', '75', '80', '90', '86', 3, 1, '2021/2022', 'genap', 1),
(10, 'Bahasa Indonesia', '70', '90', '75', '80', '90', 1, 1, '2022/2023', 'genap', 1),
(11, 'Bahasa Indonesia', '80', '80', '75', '90', '90', 1, 1, '2023/2024', 'genap', 1),
(12, 'Bahasa Inggris', '70', '80', '75', '85', '80', 1, 1, '2021/2022', 'genap', 1),
(13, 'Bahasa Inggris', '70', '80', '75', '90', '87', 1, 1, '2022/2023', 'genap', 1),
(14, 'Bahasa Inggris', '80', '85', '78', '80', '80', 1, 1, '2023/2024', 'genap', 2),
(15, 'Sejarah', '70', '80', '75', '85', '85', 1, 1, '2023/2024', 'genap', 2);

-- --------------------------------------------------------

--
-- Table structure for table `t_guru`
--

CREATE TABLE `t_guru` (
  `id` int NOT NULL,
  `nip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jenis_kelamin` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tempat_lahir` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `kelas` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_sekolah` int NOT NULL,
  `password` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `foto` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jk` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_guru`
--

INSERT INTO `t_guru` (`id`, `nip`, `nama`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `kelas`, `id_sekolah`, `password`, `foto`, `jk`) VALUES
(1, '1', 'Luhut Binsar Panjaitan', 'L', 'Sukabumi', '2023-06-25', '2', 1, 'c4ca4238a0b923820dcc509a6f75849b', '2ewdw.jpg', 'L'),
(124, '124', 'ASEP, S.Pd', 'L', 'Bandung', '1980-01-01', '-', 1, '202cb962ac59075b964b07152d234b70', 'default.jpg', 'L'),
(2333, '2333', 'UJANG, S.Si', 'L', 'Sukabumi', '1979-02-02', '-', 1, '202cb962ac59075b964b07152d234b70', 'default.jpg', 'L'),
(2344, '2344', 'USEP, M.Pd', 'L', 'Bogor', '1982-03-03', '-', 1, '202cb962ac59075b964b07152d234b70', 'default.jpg', 'L'),
(2544, '2544', 'ABI, S.Pd', 'L', 'Tasikmalaya', '1981-04-04', '-', 1, '202cb962ac59075b964b07152d234b70', 'default.jpg', 'L'),
(2545, '2545', 'SITA, S.Pd', 'P', 'Ciamis', '1983-05-05', '-', 1, '202cb962ac59075b964b07152d234b70', 'default.jpg', 'P'),
(2546, '2546', 'WAHYU, S.Pd', 'L', 'Cirebon', '1984-06-06', '-', 1, '202cb962ac59075b964b07152d234b70', 'default.jpg', 'L'),
(3333, '3333', 'RINA, S.Kom', 'P', 'Jakarta', '1985-07-07', '-', 1, '202cb962ac59075b964b07152d234b70', 'default.jpg', 'P'),
(3334, '3334', 'NINA, S.Pd', 'P', 'Bekasi', '1986-08-08', '-', 1, '202cb962ac59075b964b07152d234b70', 'default.jpg', 'P'),
(3335, '3335', 'RONY, S.E', 'L', 'Depok', '1987-09-09', '-', 1, '202cb962ac59075b964b07152d234b70', 'default.jpg', 'L'),
(3336, '3336', 'YANTI, M.Sos', 'P', 'Garut', '1988-10-10', '-', 1, '202cb962ac59075b964b07152d234b70', 'default.jpg', 'P'),
(3337, '3337', 'ANDI, M.Si', 'L', 'Bandung', '1989-11-11', '-', 1, '202cb962ac59075b964b07152d234b70', 'default.jpg', 'L'),
(3338, '3338', 'HERU, S.Pd', 'L', 'Bogor', '1990-12-12', '-', 1, '202cb962ac59075b964b07152d234b70', 'default.jpg', 'L'),
(3339, '3339', 'INA, S.Pd', 'P', 'Sukabumi', '1991-01-13', '-', 1, '202cb962ac59075b964b07152d234b70', 'default.jpg', 'P');

-- --------------------------------------------------------

--
-- Table structure for table `t_guru_penilaian`
--

CREATE TABLE `t_guru_penilaian` (
  `id` int NOT NULL,
  `nama` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `noinduk` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `kelas` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `kehadiran` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tugas` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `evaluasi` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `kedisiplinan` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nilai` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_guru_penilaian`
--

INSERT INTO `t_guru_penilaian` (`id`, `nama`, `noinduk`, `kelas`, `kehadiran`, `tugas`, `evaluasi`, `kedisiplinan`, `nilai`) VALUES
(1, 'Raihan Herlambang', '2113322', '12 IPA 4', '90', '85', '85', '90', 90),
(2, 'Muhamad Zildan TM', '2114433', '12 IPA 4', '85', '90', '90', '85', 85),
(3, 'Muhammad Rafi', '2115544', '12 IPA 4', '90', '90', '85', '85', 90),
(4, 'M. Herdi Al-Fachri', '2116655', '12 IPA 4', '85', '85', '90', '85', 85),
(5, 'M. Ripal Perdiansyah', '2117766', '12 IPA 4', '90', '85', '90', '85', 90),
(6, 'Zulhaydar Fathurrahman Sidiq', '2118899', '12 IPA 4', '90', '85', '85', '90', 85),
(7, 'Faiz Akhmad Daulay', '2119910', '12 IPA 4', '85', '90', '85', '90', 90),
(8, 'Faisal Al-Munawar Fathur Rahman', '2119911', '12 IPA 4', '85', '85', '90', '90', 85),
(9, 'Garuh Meidy Putra', '2119912', '12 IPA 4', '85', '80', '85', '85', 85),
(10, 'Reyhan Khidir', '2119913', '12 IPA 4', '85', '90', '85', '85', 85),
(11, 'Ahmad', '2112231', '12 IPA 4', '', '', '', '', 0),
(12, 'Budi', '2112232', '12 IPA 4', '', '', '', '', 0),
(13, 'Cinta', '2112233', '12 IPA 4', '', '', '', '', 0),
(14, 'Dian', '2112231', '12 IPA 4', '', '', '', '', 0),
(15, 'Eko', '2112234', '12 IPA 4', '', '', '', '', 0),
(16, 'Fitri', '2112235', '12 IPA 4', '', '', '', '', 0),
(17, 'Gunawan', '2112236', '12 IPA 4', '', '', '', '', 0),
(18, 'Hadi', '2112237', '12 IPA 4', '', '', '', '', 0),
(19, 'Indah', '2112238', '12 IPA 4', '', '', '', '', 0),
(20, 'Joko', '2112239', '12 IPA 4', '', '', '', '', 0),
(21, 'Kurniawan', '2112239', '12 IPA 4', '', '', '', '', 0),
(22, 'Lina', '2112240', '12 IPA 4', '', '', '', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `t_kelas`
--

CREATE TABLE `t_kelas` (
  `id` int NOT NULL,
  `idsekolah` int NOT NULL,
  `kelas` varchar(30) NOT NULL,
  `jumlah_siswa` int NOT NULL,
  `angkatan` varchar(30) NOT NULL,
  `wali_kelas` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `t_kelas`
--

INSERT INTO `t_kelas` (`id`, `idsekolah`, `kelas`, `jumlah_siswa`, `angkatan`, `wali_kelas`) VALUES
(10, 1, 'X 1', 0, '2023', 'Pa Kevin S.Kom'),
(11, 1, 'X 2', 0, '2023', 'Agan Ginajar S.Kom'),
(12, 1, 'X 3', 0, '2023', 'Pa Herdi S.Kom'),
(13, 1, 'X 4', 0, '2023', 'Pa Garuh S.Kom'),
(14, 1, 'X 5', 0, '2023', 'Pa Haikal S.Kom'),
(15, 1, 'XI 1', 0, '2022', 'Pa Kevin S.Kom'),
(16, 1, 'XI 2', 0, '2022', 'Agan Ginajar S.Kom'),
(17, 1, 'XI 3', 0, '2022', 'Pa Herdi S.Kom'),
(18, 1, 'XI 4', 0, '2022', 'Pa Garuh S.Kom'),
(19, 1, 'XI 5', 0, '2022', 'Pa Haikal S.Kom'),
(20, 1, 'XII 1', 0, '2021', 'Pa Kevin S.Kom'),
(21, 1, 'XII 2', 0, '2021', 'Agan Ginajar S.Kom'),
(22, 1, 'XII 3', 0, '2021', 'Pa Herdi S.Kom'),
(23, 1, 'XII 4', 0, '2021', 'Pa Garuh S.Kom'),
(24, 1, 'XII 5', 0, '2021', 'Pa Haikal S.Kom');

-- --------------------------------------------------------

--
-- Table structure for table `t_keuangan`
--

CREATE TABLE `t_keuangan` (
  `id` int NOT NULL,
  `nip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jenis_kelamin` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tempat_lahir` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `id_sekolah` int NOT NULL,
  `password` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `foto` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jk` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_keuangan`
--

INSERT INTO `t_keuangan` (`id`, `nip`, `nama`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `id_sekolah`, `password`, `foto`, `jk`) VALUES
(1, '1', 'nana', 'L', '1', '2023-07-04', 1, 'c4ca4238a0b923820dcc509a6f75849b', '1.jpg', 'L');

-- --------------------------------------------------------

--
-- Table structure for table `t_keuangan_daftar`
--

CREATE TABLE `t_keuangan_daftar` (
  `id_tagihan` int NOT NULL,
  `nama_tagihan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `total_tagihan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_keuangan_daftar`
--

INSERT INTO `t_keuangan_daftar` (`id_tagihan`, `nama_tagihan`, `total_tagihan`) VALUES
(1001, 'SPP', 'Rp. 1.000.000'),
(1002, 'Study Tour', 'Rp. 2.000.000'),
(1003, 'UTS', 'Rp. 400.000'),
(1004, 'UAS', 'Rp. 600.000'),
(1005, 'Seragam', 'Rp. 3.000.000'),
(1006, 'LKS', 'Rp. 600.000');

-- --------------------------------------------------------

--
-- Table structure for table `t_keuangan_pembayaran`
--

CREATE TABLE `t_keuangan_pembayaran` (
  `id` int NOT NULL,
  `nis` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `kelas` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `alamat` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jml_bayar` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jenis_tagihan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `metode` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal_bayar` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_keuangan_pembayaran`
--

INSERT INTO `t_keuangan_pembayaran` (`id`, `nis`, `nama`, `kelas`, `alamat`, `jml_bayar`, `jenis_tagihan`, `metode`, `tanggal_bayar`) VALUES
(21, '2130511001', 'Abi Hambali', '9C', '', 'Rp. 1.600.000', 'Study Tour', NULL, '2025-07-12 11:38:20'),
(22, '2130511002', 'Aliyaa', '9B', '', 'Rp. 2.700.000', 'Seragam', NULL, '2025-07-12 11:38:20'),
(23, '2130511003', 'Andika', '9G', '', 'Rp. 400.000', 'SPP', NULL, '2025-07-12 11:38:20'),
(24, '2130511004', 'Faizal', '9A', '', 'Rp. 400.000', 'UAS', NULL, '2025-07-12 11:38:20'),
(25, '2130511005', 'Haikal Lukman', '9E', '', 'Rp. 2.100.000', 'Seragam', NULL, '2025-07-12 11:38:20'),
(26, '2130511006', 'Juliandi', '9F', '', 'Rp. 550.000', 'UAS', NULL, '2025-07-12 11:38:20'),
(27, '2130511007', 'Kayla Sandi Putri', '9C', '', 'Rp. 400.000', 'LKS', NULL, '2025-07-12 11:38:20'),
(28, '2130511008', 'Riza Nursyah', '9E', '', 'Rp. 350.000', 'UTS', NULL, '2025-07-12 11:38:20'),
(29, '2130511009', 'Salsa Aulia', '9B', '', 'Lunas', 'Study Tour', NULL, '2025-07-12 11:38:20'),
(30, '2130511010', 'Salma Latifah', '9C', '', 'Lunas', 'UAS', NULL, '2025-07-12 11:38:20'),
(31, '2130511011', 'Sarah Anindi', '9D', '', 'Lunas', 'SPP', NULL, '2025-07-12 11:38:20'),
(32, '2130511012', 'Sauqi Alvi', '9D', '', 'Lunas', 'Seragam', NULL, '2025-07-12 11:38:20'),
(33, '2130511013', 'Sendi Dendi', '9G', '', 'Lunas', 'UTS', NULL, '2025-07-12 11:38:20'),
(35, '2130511014', 'Susila Anggraeni', '9A', '', 'Lunas', 'LKS', NULL, '2025-07-12 11:38:20'),
(36, '2330511036', 'Nazwa Akmalia Padla', '20', 'Jl. Pajajaran 1 KM.4', '250000', 'SPP Bulan Juli 2025', 'Transfer Bank', '2025-07-12 11:38:20'),
(37, '2330511036', 'Nazwa Akmalia Padla', '20', 'Jl. Pajajaran 1 KM.4', '1000000', 'SPP Bulan Juli 2025', 'Tunai', '2025-07-12 05:09:08');

-- --------------------------------------------------------

--
-- Table structure for table `t_keuangan_tagihan`
--

CREATE TABLE `t_keuangan_tagihan` (
  `id` int NOT NULL,
  `nis` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `kelas` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `alamat` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jml_tagihan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jenis_tagihan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_keuangan_tagihan`
--

INSERT INTO `t_keuangan_tagihan` (`id`, `nis`, `nama`, `kelas`, `alamat`, `jml_tagihan`, `jenis_tagihan`) VALUES
(3, '2130511007', 'Kayla Sandi Putri', '9C', 'Cisaat, Kabupaten Sukabumi', 'Rp. 200.000', 'LKS'),
(5, '2130511005', 'Haikal Lukman', '9E', 'Sukalarang, Kabupaten Sukabumi', 'Rp. 900.000', 'Seragam'),
(7, '2130511003', 'Andika', '9G', 'Cugenang', 'Rp. 600.000', 'SPP'),
(9, '2130511004', 'Faizal', '9A', 'Baros', 'Rp. 200.000', 'UAS'),
(10, '2130511006', 'Juliandi', '9F', 'Tipar', 'Rp. 50.000', 'UAS'),
(11, '2130511002', 'Aliyaa', '9B', 'Jalan Raya Bogor', 'Rp. 300.000', 'Seragam'),
(19, '2130511001', 'Abi Hambali', '9C', '', 'Rp. 400.000', 'Study Tour'),
(21, '2130511008', 'Riza Nursyah', '9E', '', 'Rp. 50.000', 'UTS'),
(22, '2', 'Reyhan', 'xiii ', '', '100000', 'Study Tour'),
(31, '2330511036', 'Nazwa Akmalia Padla', '20', 'Jl. Pajajaran 1 KM.4', '500000', 'SPP Bulan Juli 2025');

-- --------------------------------------------------------

--
-- Table structure for table `t_mahasiswa`
--

CREATE TABLE `t_mahasiswa` (
  `id` int NOT NULL,
  `nim` varchar(5) NOT NULL,
  `nama` varchar(40) NOT NULL,
  `ipk` double NOT NULL,
  `jurusan` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `t_mahasiswa`
--

INSERT INTO `t_mahasiswa` (`id`, `nim`, `nama`, `ipk`, `jurusan`) VALUES
(1, 'M0002', 'Hakko Bio Richard', 3, 'Manajemen Informatika'),
(2, 'M0003', 'Dede Rizki Ramadhan', 2.8, 'Manajemen Informatika'),
(3, 'M0004', 'Anton Sugianto', 3.2, 'Teknik Informatika'),
(4, 'M0005', 'Ujang Walim', 3.1, 'Sistem Informasi'),
(5, 'M0016', 'Dony', 3, 'Teknik Industri'),
(6, 'M0100', 'Dimas', 3.1, 'Psikologi'),
(7, 'M0016', 'Dion', 3, 'Teknik Industri'),
(8, 'M0016', 'Mayang', 3, 'Teknik Industri'),
(9, 'M0016', 'Susi', 3, 'Teknik Industri'),
(10, 'M0016', 'Niqo', 3, 'Teknik Industri'),
(11, 'M0016', 'Esbeye', 3, 'Teknik Industri'),
(12, 'M0016', 'Joko', 3, 'Teknik Industri'),
(13, 'M0016', 'Jaka', 3, 'Teknik Industri'),
(14, 'M0016', 'Wira', 3, 'Sistem Informasi'),
(15, 'M0016', 'Maradona', 3, 'Sistem Informasi'),
(16, 'M0016', 'Ujang', 3, 'Sistem Informasi'),
(17, 'M0016', 'Sugiarto', 3, 'Sistem Informasi'),
(18, 'M0016', 'Karman', 3, 'Teknik Informatika'),
(19, 'M0016', 'Anto', 3, 'Teknik Informatika'),
(20, 'M0016', 'Rosada', 3, 'Teknik Informatika'),
(21, 'M0016', 'Bima', 3, 'Manajemen Informatika'),
(22, 'M0016', 'Lusi', 3, 'Manajemen Informatika'),
(23, 'M0016', 'Ipul', 3, 'Manajemen Informatika'),
(24, 'M0016', 'Erik', 3, 'Administrasi Bisnis'),
(25, 'M0016', 'Siffa', 3, 'PGSD'),
(26, 'M0016', 'Sebastian', 3, 'Teknik Industri'),
(27, 'M0016', 'George', 3, 'Teknik Industri'),
(28, 'M0016', 'Richard', 3, 'Teknik Industri'),
(29, 'M0016', 'Dony', 3, 'Teknik Industri');

-- --------------------------------------------------------

--
-- Table structure for table `t_mapel`
--

CREATE TABLE `t_mapel` (
  `id` int NOT NULL,
  `id_guru` int NOT NULL,
  `nama_mapel` varchar(30) DEFAULT NULL,
  `nama_guru` varchar(30) DEFAULT NULL,
  `kode` varchar(20) DEFAULT NULL,
  `id_sekolah` int NOT NULL,
  `id_kelas` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `t_mapel`
--

INSERT INTO `t_mapel` (`id`, `id_guru`, `nama_mapel`, `nama_guru`, `kode`, `id_sekolah`, `id_kelas`) VALUES
(1, 1, 'FISIKA', 'UJANG, S.P.d', '2344', 1, 10),
(2, 124, 'B. INDONESIA', 'ASEP, S.Pd', '2311', 1, 10),
(3, 1, 'MATEMATIKA', 'IDA, M.Pd', '2541', 1, 0),
(4, 2333, 'B. INGGRIS', 'UJANG, S.Si', '122', 1, 11),
(5, 2344, 'B. JEPANG', 'USEP, M.Pd', '2341', 1, 12),
(6, 2544, 'KIMIA', 'ABI, S.Pd', '231', 1, 13),
(7, 2545, 'BIOLOGI', 'SITA, S.Pd', '212', 1, 14),
(8, 2546, 'SEJARAH', 'WAHYU, S.Pd', '3001', 1, 15),
(9, 124, 'B. INGGRIS LANJUTAN', 'ASEP, S.Pd', '2312', 1, 16),
(10, 124, 'B. SUNDA', 'ASEP, S.Pd', '2313', 1, 17),
(11, 3333, 'TIK', 'RINA, S.Kom', 'TIKX1', 1, 18),
(12, 3334, 'PPKN', 'NINA, S.Pd', 'PPKN1', 1, 19),
(13, 3335, 'EKONOMI', 'RONY, S.E', 'EKO1', 1, 20),
(14, 3336, 'SOSIOLOGI', 'YANTI, M.Sos', 'SOS1', 1, 21),
(15, 3337, 'GEOGRAFI', 'ANDI, M.Si', 'GEO1', 1, 22),
(16, 3338, 'SBK', 'HERU, S.Pd', 'SBK1', 1, 23),
(17, 3339, 'PRAKARYA', 'INA, S.Pd', 'PKY1', 1, 24);

-- --------------------------------------------------------

--
-- Table structure for table `t_materi`
--

CREATE TABLE `t_materi` (
  `id` int NOT NULL,
  `mapel_id` int NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text,
  `file` varchar(255) DEFAULT NULL,
  `tanggal_upload` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `t_materi`
--

INSERT INTO `t_materi` (`id`, `mapel_id`, `judul`, `deskripsi`, `file`, `tanggal_upload`) VALUES
(1, 1, 'Hukum Newton', 'Pembahasan lengkap mengenai hukum Newton 1, 2, dan 3 serta penerapannya dalam kehidupan sehari-hari.', 'fisika_hukum_newton.pdf', '2025-07-12');

-- --------------------------------------------------------

--
-- Table structure for table `t_menu`
--

CREATE TABLE `t_menu` (
  `id` int NOT NULL,
  `nama_menu` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `keterangan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `aktif` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `role` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_menu`
--

INSERT INTO `t_menu` (`id`, `nama_menu`, `keterangan`, `aktif`, `role`) VALUES
(1, 'matapelajaran', 'Mata Pelajaran', '1', '1'),
(2, 'grafik_pinjaman', 'Grafik Pinjaman', '1', '4'),
(3, 'profil', 'Profil Guru', '1', '2'),
(4, 'penilaian', 'Penilaian Siswa', '1', '2'),
(5, 'grafik_tagihan', 'Grafik Tagihan', '4', '3'),
(6, 'pembelajaran', 'Pembelajaran', '1', '2'),
(8, 'ujian_essay', 'Ujian Essay', '1', '1'),
(10, 'data_buku', 'Data Buku', '1', '4'),
(11, 'mapel', 'Mata Pelajaran', '1', '2'),
(12, 'grafik_pembayaran_tagihan', 'Grafik Pembayaran Tagihan', '1', '3'),
(14, 'pembayaran', 'Pembayaran', '1', '3'),
(15, 'profil', 'Profil', '1', '3'),
(16, 'grafik_nilaisiswa', 'Grafik Penilaian', '1', '1'),
(18, 'kelas', 'Kelas', '1', '1'),
(19, 'daftar_kelas', 'Rekap Nilai', '1', '2'),
(20, 'mata_pelajaran', 'Mata Pelajaran', '1', '0'),
(21, 'absensi', 'Absensi', '1', '2'),
(22, 'adminkelas', 'Kelas', '1', '0'),
(23, 'tabel_guru', 'Data Guru', '1', '0'),
(24, 'data_peminjam_buku', 'Peminjaman', '1', '4'),
(25, 'dashboard_pembayaran', 'Pembayaran', '1', '3'),
(26, 'walikelas', 'Walikelas', '1', '0'),
(27, 'grafik_kehadiran', 'Grafik Kehadiran Siswa', '1', '2'),
(28, 'ekskul', 'Ekstrakurikuler', '1', '1'),
(29, 'dashboard_agenda', 'Agenda Mengajar', '1', '2'),
(30, 'datasiswa', 'Data Siswa', '1', '0'),
(31, 'profil_keuangan', 'Profil Keuangan', '1', '0'),
(32, 'pengajaran', 'Data Pembelajaran', '1', '0'),
(33, 'mata_pelajaran', 'Pembelajaran Mata Pelajaran ', '1', '1');

-- --------------------------------------------------------

--
-- Table structure for table `t_nilai`
--

CREATE TABLE `t_nilai` (
  `id` int NOT NULL,
  `nama_mapel` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nilai` int NOT NULL,
  `semester` int NOT NULL,
  `id_sekolah` int NOT NULL,
  `id_siswa` int NOT NULL,
  `kelas` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_nilai`
--

INSERT INTO `t_nilai` (`id`, `nama_mapel`, `nilai`, `semester`, `id_sekolah`, `id_siswa`, `kelas`) VALUES
(1, 'Matematika', 90, 1, 1, 1, 'XII ipa 1'),
(2, 'Fisika', 80, 1, 0, 1, 'XI ipa 1'),
(3, 'Kimia', 88, 1, 0, 1, 'X'),
(4, 'Biologi', 75, 1, 0, 10, '1'),
(5, 'Fisika', 85, 1, 1, 11, 'XII 1');

-- --------------------------------------------------------

--
-- Table structure for table `t_nilai_candra`
--

CREATE TABLE `t_nilai_candra` (
  `id` int NOT NULL,
  `mata_pelajaran` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `kkm` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nilai_mapel` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `predikat_mapel` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nilai_tugas` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `predikat_tugas` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nilai_uts` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `predikat_uts` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nilai_uas` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `predikat_uas` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_nilai_candra`
--

INSERT INTO `t_nilai_candra` (`id`, `mata_pelajaran`, `kkm`, `nilai_mapel`, `predikat_mapel`, `nilai_tugas`, `predikat_tugas`, `nilai_uts`, `predikat_uts`, `nilai_uas`, `predikat_uas`) VALUES
(1, 'Matematika', '70', '85', '', '85', '', '75', '', '80', ''),
(2, 'Sejarah', '80', '90', 'A', '87', 'A', '88', 'A', '86', 'A'),
(3, 'Akidah Ahklak', '85', '90', 'A', '90', 'A', '90', 'A', '90', 'A'),
(4, 'Fisika', '70', '65', 'C', '55', 'C', '65', 'C', '65', 'C'),
(5, 'Kimia', '65', '90', '', '90', '', '90', '', '90', ''),
(20, 'Bahasa Indonesia', '75', '70', 'C', '80', 'B', '85', 'A', '75', 'B'),
(21, 'Bahasa Inggris', '70', '85', 'A', '75', 'B', '80', 'B', '90', 'A'),
(22, 'Biologi', '70', '80', 'B', '86', 'B', '75', 'B', '80', 'B'),
(23, 'Geografi', '60', '75', 'B', '80', 'B', '85', 'B', '90', 'A'),
(24, 'Ekonomi', '65', '70', 'B', '75', 'B', '80', 'B', '85', 'A'),
(25, 'Sosiologi', '65', '80', 'B', '85', 'B', '75', 'B', '80', 'B'),
(26, 'Seni Rupa', '70', '75', 'B', '80', 'B', '85', 'B', '90', 'A'),
(27, 'Pendidikan Jasmani', '75', '90', 'A', '85', 'A', '87', 'A', '86', 'A'),
(28, 'Komputer', '65', '90', 'A', '90', 'A', '90', 'A', '90', 'A'),
(29, 'Teknik Gambar', '70', '90', 'A', '90', 'A', '90', 'A', '90', 'A'),
(0, 'Olahraga', '40', '90', '', '80', '', '77', '', '66', '');

-- --------------------------------------------------------

--
-- Table structure for table `t_penilaian`
--

CREATE TABLE `t_penilaian` (
  `id` int NOT NULL,
  `id_mapel` int NOT NULL,
  `id_siswa` int NOT NULL,
  `id_guru` int NOT NULL,
  `kehadiran` int NOT NULL,
  `tugas` int NOT NULL,
  `uts` int NOT NULL,
  `uas` int NOT NULL,
  `sikap` int NOT NULL,
  `kedisiplinan` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_penilaian`
--

INSERT INTO `t_penilaian` (`id`, `id_mapel`, `id_siswa`, `id_guru`, `kehadiran`, `tugas`, `uts`, `uas`, `sikap`, `kedisiplinan`) VALUES
(1, 1, 11, 2113322, 90, 85, 80, 87, 4, 4);

-- --------------------------------------------------------

--
-- Table structure for table `t_pustakawan`
--

CREATE TABLE `t_pustakawan` (
  `id` int NOT NULL,
  `nip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jenis_kelamin` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tempat_lahir` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `kelas` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_sekolah` int NOT NULL,
  `password` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `foto` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jk` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_pustakawan`
--

INSERT INTO `t_pustakawan` (`id`, `nip`, `nama`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `kelas`, `id_sekolah`, `password`, `foto`, `jk`) VALUES
(4, '4', '4', '', '4', '2023-06-01', '4', 1, '202cb962ac59075b964b07152d234b70', '', 'L');

-- --------------------------------------------------------

--
-- Table structure for table `t_role`
--

CREATE TABLE `t_role` (
  `id` int NOT NULL,
  `role` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_role`
--

INSERT INTO `t_role` (`id`, `role`) VALUES
(0, 'administra'),
(1, 'siswa'),
(2, 'guru'),
(3, 'keuangan'),
(4, 'pustakawan');

-- --------------------------------------------------------

--
-- Table structure for table `t_siswa`
--

CREATE TABLE `t_siswa` (
  `id` int NOT NULL,
  `nis` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jenis_kelamin` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tempat_lahir` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `kelas` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_sekolah` int NOT NULL,
  `password` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `foto` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_siswa`
--

INSERT INTO `t_siswa` (`id`, `nis`, `nama`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `kelas`, `id_sekolah`, `password`, `foto`) VALUES
(1, '1', 'Andri Rini', 'L', '2022-1-1', '2022-01-01', '5', 1, 'c4ca4238a0b923820dcc509a6f75849b', '64b8cd36b8d6b.jpg'),
(2, '3', 'Muhamad Zildan TM', 'P', '2022-1-1', '2022-01-01', '1', 1, 'c4ca4238a0b923820dcc509a6f75849b', '1.jpg'),
(3, '4', 'Muhammad Rafi', 'P', '2022-1-1', '2022-01-01', '1', 1, 'c4ca4238a0b923820dcc509a6f75849b', '1.jpg'),
(4, '5', 'M. Herdi Al-Fachri', 'P', '2022-1-1', '2022-01-01', '1', 1, 'c4ca4238a0b923820dcc509a6f75849b', '1.jpg'),
(5, '6', 'M. Ripal Perdiansyah', 'P', '2022-1-1', '2022-01-01', '1', 1, 'c4ca4238a0b923820dcc509a6f75849b', '1.jpg'),
(6, '7', 'Zulhaydar Fathurrahman Sidiq', 'P', '2022-1-1', '2022-01-01', '1', 1, 'c4ca4238a0b923820dcc509a6f75849b', '1.jpg'),
(8, '930949049390430', 'Ghina', '1', 'Palembang', '2222-01-01', '1 smp', 3333, 'abc', '1.jpg'),
(11, '2330511036', 'NAZWA AKMALIA PADLA', 'P', 'Sukabumi', '2004-01-10', '20', 1, '5f4dcc3b5aa765d61d8327deb882cf99', 'foto_6873358062bd46.14939144.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `t_siswa_absensi`
--

CREATE TABLE `t_siswa_absensi` (
  `id` int NOT NULL,
  `id_siswa` int NOT NULL,
  `waktu_kehadiran` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  `kehadiran` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_siswa_absensi`
--

INSERT INTO `t_siswa_absensi` (`id`, `id_siswa`, `waktu_kehadiran`, `kehadiran`) VALUES
(1, 1, '2023-06-26 04:23:42.660789', 's'),
(2, 2, '2023-06-26 04:23:46.323762', 'H'),
(3, 11, '2025-07-11 17:26:08.000000', 'H'),
(4, 11, '2025-07-11 17:29:34.000000', 'H'),
(5, 11, '2025-07-11 11:40:04.000000', 'H'),
(6, 11, '2025-07-11 23:02:16.000000', 'H');

-- --------------------------------------------------------

--
-- Table structure for table `t_walikelas`
--

CREATE TABLE `t_walikelas` (
  `id_walikelas` int NOT NULL,
  `nama` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `walikelas` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jumlah_siswa` int NOT NULL,
  `id_sekolah` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_walikelas`
--

INSERT INTO `t_walikelas` (`id_walikelas`, `nama`, `walikelas`, `jumlah_siswa`, `id_sekolah`) VALUES
(1, 'Agan Ginajar S.Kom', 'VII B', 40, 1),
(2, 'Pa Herdi S.Kom', 'VIII B', 35, 0),
(3, 'Pa Kevin S.Kom', 'X A', 29, 0),
(4, 'Pa Garuh S.Kom', 'XI B', 38, 0),
(5, 'Pa Haikal S.Kom', 'VIII C', 40, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `fullname` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '-',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `picture` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `type` enum('admin','siswa','guru','ortu','perpus','kepsek') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'siswa',
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `password`, `status`, `picture`, `type`, `last_login`, `created_at`) VALUES
(1, 'Adi Sunarto Asril', 'odong1983@gmail.com', '-', 1, 'https://lh3.googleusercontent.com/a/ACg8ocKGG6dt1vwONqa3rm0hICNgBexmiki34ePGyv88Rmrckll9Qz4Z=s96-c', 'admin', '2025-06-01 09:22:30', '2025-06-01 02:22:30'),
(2, 'Sunarto Asril Adi', 'asriladi@ummi.ac.id', '-', 1, 'https://lh3.googleusercontent.com/a/ACg8ocL-489oiObyR269ezHYR7_bzmQckdjIMOYnP28IOhIM9QSFuRw=s96-c', 'admin', '2025-06-13 02:12:27', '2025-06-12 19:12:27'),
(3, 'AKMALIA PADLA NAZWA', 'nazwaakmalia036@ummi.ac.id', '-', 1, 'https://lh3.googleusercontent.com/a/ACg8ocJ_q04pxF4nNbYoZZrwe5j22qR8PHJI4cvlqIbBphsrUYsSxw=s96-c', 'siswa', '2025-07-11 21:51:23', '2025-06-14 02:18:16'),
(5, 'Akmalia padla Nazwa', 'nazwaakmalia569@gmail.com', '-', 1, 'https://lh3.googleusercontent.com/a/ACg8ocIG1g7XlpyosQlq9gwdT0FGsA6M4AsUsvJlI1mLgoEDFjFIxDlD=s96-c', 'guru', '2025-06-20 21:13:37', '2025-06-20 14:13:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `agenda_guru`
--
ALTER TABLE `agenda_guru`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `agenda_kegiatan`
--
ALTER TABLE `agenda_kegiatan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `anggota_mapel`
--
ALTER TABLE `anggota_mapel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mapel_id` (`mapel_id`),
  ADD KEY `anggota_mapel_ibfk_1` (`siswa_id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_mapel` (`id_mapel`),
  ADD KEY `id_kelas` (`id_kelas`);

--
-- Indexes for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pengumpulan_tugas`
--
ALTER TABLE `pengumpulan_tugas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tugas_id` (`tugas_id`),
  ADD KEY `siswa_id` (`siswa_id`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`nisn`);

--
-- Indexes for table `tugas`
--
ALTER TABLE `tugas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_absensi`
--
ALTER TABLE `t_absensi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_administrator`
--
ALTER TABLE `t_administrator`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_ekstrakurikuler`
--
ALTER TABLE `t_ekstrakurikuler`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_grafiknilai`
--
ALTER TABLE `t_grafiknilai`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_guru`
--
ALTER TABLE `t_guru`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_guru_penilaian`
--
ALTER TABLE `t_guru_penilaian`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_kelas`
--
ALTER TABLE `t_kelas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_keuangan`
--
ALTER TABLE `t_keuangan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_keuangan_pembayaran`
--
ALTER TABLE `t_keuangan_pembayaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_keuangan_tagihan`
--
ALTER TABLE `t_keuangan_tagihan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_mapel`
--
ALTER TABLE `t_mapel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_materi`
--
ALTER TABLE `t_materi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mapel_id` (`mapel_id`);

--
-- Indexes for table `t_menu`
--
ALTER TABLE `t_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_nilai`
--
ALTER TABLE `t_nilai`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `t_penilaian`
--
ALTER TABLE `t_penilaian`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_pustakawan`
--
ALTER TABLE `t_pustakawan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_role`
--
ALTER TABLE `t_role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_siswa`
--
ALTER TABLE `t_siswa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_siswa_absensi`
--
ALTER TABLE `t_siswa_absensi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_walikelas`
--
ALTER TABLE `t_walikelas`
  ADD PRIMARY KEY (`id_walikelas`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `agenda_guru`
--
ALTER TABLE `agenda_guru`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `agenda_kegiatan`
--
ALTER TABLE `agenda_kegiatan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `anggota_mapel`
--
ALTER TABLE `anggota_mapel`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengumpulan_tugas`
--
ALTER TABLE `pengumpulan_tugas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tugas`
--
ALTER TABLE `tugas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `t_absensi`
--
ALTER TABLE `t_absensi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=161;

--
-- AUTO_INCREMENT for table `t_administrator`
--
ALTER TABLE `t_administrator`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `t_ekstrakurikuler`
--
ALTER TABLE `t_ekstrakurikuler`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `t_grafiknilai`
--
ALTER TABLE `t_grafiknilai`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `t_guru`
--
ALTER TABLE `t_guru`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3113214;

--
-- AUTO_INCREMENT for table `t_guru_penilaian`
--
ALTER TABLE `t_guru_penilaian`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `t_kelas`
--
ALTER TABLE `t_kelas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `t_keuangan`
--
ALTER TABLE `t_keuangan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `t_keuangan_pembayaran`
--
ALTER TABLE `t_keuangan_pembayaran`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `t_keuangan_tagihan`
--
ALTER TABLE `t_keuangan_tagihan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `t_mapel`
--
ALTER TABLE `t_mapel`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `t_materi`
--
ALTER TABLE `t_materi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `t_menu`
--
ALTER TABLE `t_menu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `t_nilai`
--
ALTER TABLE `t_nilai`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `t_penilaian`
--
ALTER TABLE `t_penilaian`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `t_pustakawan`
--
ALTER TABLE `t_pustakawan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `t_role`
--
ALTER TABLE `t_role`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `t_siswa`
--
ALTER TABLE `t_siswa`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `t_siswa_absensi`
--
ALTER TABLE `t_siswa_absensi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `t_walikelas`
--
ALTER TABLE `t_walikelas`
  MODIFY `id_walikelas` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `anggota_mapel`
--
ALTER TABLE `anggota_mapel`
  ADD CONSTRAINT `anggota_mapel_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`nisn`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD CONSTRAINT `jadwal_ibfk_1` FOREIGN KEY (`id_mapel`) REFERENCES `t_mapel` (`id`),
  ADD CONSTRAINT `jadwal_ibfk_2` FOREIGN KEY (`id_kelas`) REFERENCES `t_kelas` (`id`);

--
-- Constraints for table `pengumpulan_tugas`
--
ALTER TABLE `pengumpulan_tugas`
  ADD CONSTRAINT `pengumpulan_tugas_ibfk_1` FOREIGN KEY (`tugas_id`) REFERENCES `tugas` (`id`),
  ADD CONSTRAINT `pengumpulan_tugas_ibfk_2` FOREIGN KEY (`siswa_id`) REFERENCES `t_siswa` (`id`);

--
-- Constraints for table `t_materi`
--
ALTER TABLE `t_materi`
  ADD CONSTRAINT `t_materi_ibfk_1` FOREIGN KEY (`mapel_id`) REFERENCES `t_mapel` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
