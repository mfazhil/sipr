-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 11 Jul 2021 pada 06.54
-- Versi server: 10.4.17-MariaDB
-- Versi PHP: 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sipr`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `himpunan`
--

CREATE TABLE `himpunan` (
  `IdHimpunan` int(11) NOT NULL,
  `IdProsedur` int(11) NOT NULL,
  `NamaHimpunan` varchar(255) NOT NULL,
  `Atas` int(255) NOT NULL,
  `Tengah` int(255) DEFAULT NULL,
  `Bawah` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `himpunan`
--

INSERT INTO `himpunan` (`IdHimpunan`, `IdProsedur`, `NamaHimpunan`, `Atas`, `Tengah`, `Bawah`) VALUES
(3, 23, 'Tidak Sesuai', 50, NULL, 25),
(4, 23, 'Cukup Sesuai', 75, 50, 25),
(5, 23, 'Sesuai', 100, 75, 50),
(6, 23, 'Sangat Sesuai', 100, NULL, 75),
(7, 24, 'Tidak Sesuai', 50, NULL, 25),
(8, 24, 'Cukup Sesuai', 75, 50, 25),
(9, 24, 'Sesuai', 100, 75, 50),
(10, 24, 'Sangat Sesuai', 100, NULL, 75),
(11, 25, 'Tidak Sesuai', 50, NULL, 25),
(12, 25, 'Cukup Sesuai', 75, 50, 25),
(13, 25, 'Sesuai', 100, 75, 50),
(14, 25, 'Sangat Sesuai', 100, NULL, 75);

-- --------------------------------------------------------

--
-- Struktur dari tabel `jnsruang`
--

CREATE TABLE `jnsruang` (
  `IdJnsRuang` int(11) NOT NULL,
  `NamaJnsRuang` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `jnsruang`
--

INSERT INTO `jnsruang` (`IdJnsRuang`, `NamaJnsRuang`) VALUES
(11, 'jenis 1'),
(12, 'jenis 2');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengecekan`
--

CREATE TABLE `pengecekan` (
  `idPengecekan` int(11) NOT NULL,
  `idPetugas` int(11) NOT NULL,
  `IdPRuang` int(11) NOT NULL,
  `Nilai` int(11) NOT NULL,
  `TglPengecekan` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `pengecekan`
--

INSERT INTO `pengecekan` (`idPengecekan`, `idPetugas`, `IdPRuang`, `Nilai`, `TglPengecekan`) VALUES
(30, 12, 20, 68, '2021-07-10'),
(31, 12, 21, 83, '2021-07-10'),
(32, 12, 23, 95, '2021-07-10');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengguna`
--

CREATE TABLE `pengguna` (
  `IdPengguna` int(11) NOT NULL,
  `IdPetugas` int(11) DEFAULT NULL,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `jnspengguna` enum('ADMIN','USER','','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `pengguna`
--

INSERT INTO `pengguna` (`IdPengguna`, `IdPetugas`, `Username`, `Password`, `jnspengguna`) VALUES
(4, NULL, 'admin', 'admin', 'ADMIN'),
(12, 12, 'padil', '123', 'USER');

-- --------------------------------------------------------

--
-- Struktur dari tabel `petugas`
--

CREATE TABLE `petugas` (
  `IdPetugas` int(11) NOT NULL,
  `NamaPetugas` varchar(255) NOT NULL,
  `Jk` varchar(255) NOT NULL,
  `Alamat` text NOT NULL,
  `NoHP` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `petugas`
--

INSERT INTO `petugas` (`IdPetugas`, `NamaPetugas`, `Jk`, `Alamat`, `NoHP`) VALUES
(6, '123', 'laki-laki', '132', '123'),
(7, '123', 'laki-laki', '132', '123'),
(8, '123', 'laki-laki', '123', '123'),
(12, 'padil', 'laki-laki', '123', '123');

-- --------------------------------------------------------

--
-- Struktur dari tabel `prosedur`
--

CREATE TABLE `prosedur` (
  `IdProsedur` int(11) NOT NULL,
  `NamaProsedur` varchar(255) NOT NULL,
  `Keterangan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `prosedur`
--

INSERT INTO `prosedur` (`IdProsedur`, `NamaProsedur`, `Keterangan`) VALUES
(23, 'Prosedur A', 'Keterangan prosedur A'),
(24, 'Prosedur B', 'Keterangan prosedur B'),
(25, 'Prosedur C', 'Keterangan prosedur C');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pruang`
--

CREATE TABLE `pruang` (
  `Iddia` int(11) NOT NULL,
  `idruang` int(11) NOT NULL,
  `idprosedur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `pruang`
--

INSERT INTO `pruang` (`Iddia`, `idruang`, `idprosedur`) VALUES
(20, 25, 23),
(21, 25, 24),
(23, 25, 25);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ruang`
--

CREATE TABLE `ruang` (
  `IdRuang` int(11) NOT NULL,
  `IdJnsRuang` int(11) NOT NULL,
  `NamaRuang` varchar(255) NOT NULL,
  `Kapasitas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `ruang`
--

INSERT INTO `ruang` (`IdRuang`, `IdJnsRuang`, `NamaRuang`, `Kapasitas`) VALUES
(25, 11, 'ruang 1', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `rule`
--

CREATE TABLE `rule` (
  `IdRule` int(11) NOT NULL,
  `IdRuang` int(11) NOT NULL,
  `Rule` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `rule`
--

INSERT INTO `rule` (`IdRule`, `IdRuang`, `Rule`) VALUES
(1, 25, 'Tidak Layak'),
(2, 25, 'Tidak Layak'),
(3, 25, 'Tidak Layak'),
(4, 25, 'Tidak Layak'),
(5, 25, 'Tidak Layak'),
(6, 25, 'Tidak Layak'),
(7, 25, 'Tidak Layak'),
(8, 25, 'Tidak Layak'),
(9, 25, 'Tidak Layak'),
(10, 25, 'Tidak Layak'),
(11, 25, 'Tidak Layak'),
(12, 25, 'Tidak Layak'),
(13, 25, 'Tidak Layak'),
(14, 25, 'Tidak Layak'),
(15, 25, 'Tidak Layak'),
(16, 25, 'Tidak Layak'),
(17, 25, 'Tidak Layak'),
(18, 25, 'Tidak Layak'),
(19, 25, 'Tidak Layak'),
(20, 25, 'Tidak Layak'),
(21, 25, 'Tidak Layak'),
(22, 25, 'Tidak Layak'),
(23, 25, 'Layak'),
(24, 25, 'Layak'),
(25, 25, 'Tidak Layak'),
(26, 25, 'Layak'),
(27, 25, 'Layak'),
(28, 25, 'Layak'),
(29, 25, 'Tidak Layak'),
(30, 25, 'Layak'),
(31, 25, 'Layak'),
(32, 25, 'Sangat Layak'),
(33, 25, 'Tidak Layak'),
(34, 25, 'Tidak Layak'),
(35, 25, 'Tidak Layak'),
(36, 25, 'Tidak Layak'),
(37, 25, 'Tidak Layak'),
(38, 25, 'Layak'),
(39, 25, 'Layak'),
(40, 25, 'Layak'),
(41, 25, 'Tidak Layak'),
(42, 25, 'Layak'),
(43, 25, 'Layak'),
(44, 25, 'Sangat Layak'),
(45, 25, 'Tidak Layak'),
(46, 25, 'Layak'),
(47, 25, 'Sangat Layak'),
(48, 25, 'Sangat Layak'),
(49, 25, 'Tidak Layak'),
(50, 25, 'Tidak Layak'),
(51, 25, 'Tidak Layak'),
(52, 25, 'Tidak Layak'),
(53, 25, 'Tidak Layak'),
(54, 25, 'Layak'),
(55, 25, 'Layak'),
(56, 25, 'Layak'),
(57, 25, 'Tidak Layak'),
(58, 25, 'Layak'),
(59, 25, 'Sangat Layak'),
(60, 25, 'Sangat Layak'),
(61, 25, 'Tidak Layak'),
(62, 25, 'Sangat Layak'),
(63, 25, 'Sangat Layak'),
(64, 25, 'Sangat Layak');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `himpunan`
--
ALTER TABLE `himpunan`
  ADD PRIMARY KEY (`IdHimpunan`),
  ADD KEY `fk_prosedur_himpunan` (`IdProsedur`);

--
-- Indeks untuk tabel `jnsruang`
--
ALTER TABLE `jnsruang`
  ADD PRIMARY KEY (`IdJnsRuang`);

--
-- Indeks untuk tabel `pengecekan`
--
ALTER TABLE `pengecekan`
  ADD PRIMARY KEY (`idPengecekan`),
  ADD KEY `petugass_fk` (`idPetugas`),
  ADD KEY `pruang_fk` (`IdPRuang`);

--
-- Indeks untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`IdPengguna`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD KEY `petugas_fk` (`IdPetugas`);

--
-- Indeks untuk tabel `petugas`
--
ALTER TABLE `petugas`
  ADD PRIMARY KEY (`IdPetugas`);

--
-- Indeks untuk tabel `prosedur`
--
ALTER TABLE `prosedur`
  ADD PRIMARY KEY (`IdProsedur`);

--
-- Indeks untuk tabel `pruang`
--
ALTER TABLE `pruang`
  ADD PRIMARY KEY (`Iddia`),
  ADD KEY `pruang_ruang_fk` (`idruang`),
  ADD KEY `pruang_prosedur_fk` (`idprosedur`);

--
-- Indeks untuk tabel `ruang`
--
ALTER TABLE `ruang`
  ADD PRIMARY KEY (`IdRuang`),
  ADD KEY `jnsruang_fk` (`IdJnsRuang`);

--
-- Indeks untuk tabel `rule`
--
ALTER TABLE `rule`
  ADD PRIMARY KEY (`IdRule`),
  ADD KEY `fk_rule_ruangan` (`IdRuang`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `himpunan`
--
ALTER TABLE `himpunan`
  MODIFY `IdHimpunan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `jnsruang`
--
ALTER TABLE `jnsruang`
  MODIFY `IdJnsRuang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `pengecekan`
--
ALTER TABLE `pengecekan`
  MODIFY `idPengecekan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `IdPengguna` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `petugas`
--
ALTER TABLE `petugas`
  MODIFY `IdPetugas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `prosedur`
--
ALTER TABLE `prosedur`
  MODIFY `IdProsedur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT untuk tabel `pruang`
--
ALTER TABLE `pruang`
  MODIFY `Iddia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT untuk tabel `ruang`
--
ALTER TABLE `ruang`
  MODIFY `IdRuang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT untuk tabel `rule`
--
ALTER TABLE `rule`
  MODIFY `IdRule` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `himpunan`
--
ALTER TABLE `himpunan`
  ADD CONSTRAINT `fk_prosedur_himpunan` FOREIGN KEY (`IdProsedur`) REFERENCES `prosedur` (`IdProsedur`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pengecekan`
--
ALTER TABLE `pengecekan`
  ADD CONSTRAINT `petugass_fk` FOREIGN KEY (`idPetugas`) REFERENCES `petugas` (`IdPetugas`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pruang_fk` FOREIGN KEY (`IdPRuang`) REFERENCES `pruang` (`Iddia`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  ADD CONSTRAINT `petugas_fk` FOREIGN KEY (`IdPetugas`) REFERENCES `petugas` (`IdPetugas`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pruang`
--
ALTER TABLE `pruang`
  ADD CONSTRAINT `pruang_prosedur_fk` FOREIGN KEY (`idprosedur`) REFERENCES `prosedur` (`IdProsedur`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pruang_ruang_fk` FOREIGN KEY (`idruang`) REFERENCES `ruang` (`IdRuang`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `ruang`
--
ALTER TABLE `ruang`
  ADD CONSTRAINT `jnsruang_fk` FOREIGN KEY (`IdJnsRuang`) REFERENCES `jnsruang` (`IdJnsRuang`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `rule`
--
ALTER TABLE `rule`
  ADD CONSTRAINT `fk_rule_ruangan` FOREIGN KEY (`IdRuang`) REFERENCES `ruang` (`IdRuang`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
