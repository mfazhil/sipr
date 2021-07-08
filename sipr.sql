-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 08 Jul 2021 pada 19.28
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
  `Atas` int(11) DEFAULT NULL,
  `Tengah` int(11) NOT NULL,
  `Bawah` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
(23, 12, 20, 1, '2021-07-05'),
(24, 12, 21, 12, '2021-07-05'),
(27, 12, 22, 2, '2021-07-05'),
(28, 12, 20, 1, '2021-07-06'),
(29, 12, 21, 2, '2021-07-06');

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
(23, 'prosedur 1', 'asdasdad'),
(24, 'prosedur 2', 'asdasd');

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
(22, 26, 24);

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
(25, 11, 'ruang 1', 1),
(26, 12, 'ruang 2', 2);

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
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `himpunan`
--
ALTER TABLE `himpunan`
  MODIFY `IdHimpunan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `jnsruang`
--
ALTER TABLE `jnsruang`
  MODIFY `IdJnsRuang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `pengecekan`
--
ALTER TABLE `pengecekan`
  MODIFY `idPengecekan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

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
  MODIFY `IdProsedur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT untuk tabel `pruang`
--
ALTER TABLE `pruang`
  MODIFY `Iddia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT untuk tabel `ruang`
--
ALTER TABLE `ruang`
  MODIFY `IdRuang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
