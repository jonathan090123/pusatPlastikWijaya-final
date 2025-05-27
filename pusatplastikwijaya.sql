-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 05, 2024 at 04:58 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pusatplastikwijaya`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `nama_admin` varchar(20) NOT NULL,
  `no_telp_admin` varchar(12) NOT NULL,
  `email_admin` varchar(20) NOT NULL,
  `password_admin` text NOT NULL,
  `alamat_admin` varchar(20) NOT NULL,
  `id_admin` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`nama_admin`, `no_telp_admin`, `email_admin`, `password_admin`, `alamat_admin`, `id_admin`) VALUES
('admin', '0213456', 'admin@admin.com', '*84AAC12F54AB666ECFC2A83C676908C8BBC381B1', 'admin home', 1);

-- --------------------------------------------------------

--
-- Table structure for table `antriankilat`
--

CREATE TABLE `antriankilat` (
  `id_antrian` int(11) NOT NULL,
  `qr` text DEFAULT NULL,
  `status` varchar(20) NOT NULL,
  `id_customer` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `antriankilat`
--

INSERT INTO `antriankilat` (`id_antrian`, `qr`, `status`, `id_customer`) VALUES
(1, 'qr/1.png', 'end', 4);

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id_barang` int(11) NOT NULL,
  `nama_barang` varchar(20) NOT NULL,
  `detail_barang` text NOT NULL,
  `stok_barang` int(11) NOT NULL,
  `harga_barang` int(11) NOT NULL,
  `status_barang` varchar(20) NOT NULL,
  `id_kategori` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id_barang`, `nama_barang`, `detail_barang`, `stok_barang`, `harga_barang`, `status_barang`, `id_kategori`) VALUES
(1, 'Kresek', 'transparan', 950, 5000, '', 1),
(2, 'Plastik Klip', 'mini', 1500, 1000, 'Aktif', 1),
(3, 'Ember', 'super', 740, 10000, '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE `chat` (
  `id_chat` int(11) NOT NULL,
  `id_customer` int(11) NOT NULL,
  `id_admin` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat`
--

INSERT INTO `chat` (`id_chat`, `id_customer`, `id_admin`, `status`) VALUES
(17, 1, 1, 0),
(70, 1, 1, 0),
(71, 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id_customer` int(11) NOT NULL,
  `nama_customer` varchar(20) NOT NULL,
  `no_telp_customer` varchar(12) DEFAULT NULL,
  `email_customer` varchar(20) NOT NULL,
  `password_customer` text NOT NULL,
  `alamat_customer` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id_customer`, `nama_customer`, `no_telp_customer`, `email_customer`, `password_customer`, `alamat_customer`) VALUES
(1, 'jojo', NULL, 'jojo@usr.com', '*84AAC12F54AB666ECFC2A83C676908C8BBC381B1', 'rumah jojo'),
(2, 'alex', NULL, 'alex@usr.com', '*84AAC12F54AB666ECFC2A83C676908C8BBC381B1', 'rumah alex'),
(3, 'ecen', NULL, 'ecen@usr.com', '*84AAC12F54AB666ECFC2A83C676908C8BBC381B1', 'rumah ecen'),
(4, 'top', '23234', 'top@usr.com', '*84AAC12F54AB666ECFC2A83C676908C8BBC381B1', 'rumah top');

-- --------------------------------------------------------

--
-- Table structure for table `detailak`
--

CREATE TABLE `detailak` (
  `id_detail_AK` int(11) NOT NULL,
  `id_antrian` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detailak`
--

INSERT INTO `detailak` (`id_detail_AK`, `id_antrian`, `jumlah`, `id_barang`) VALUES
(1, 1, 10, 3),
(2, 1, 50, 1);

-- --------------------------------------------------------

--
-- Table structure for table `detailchat`
--

CREATE TABLE `detailchat` (
  `id_detail_chat` int(11) NOT NULL,
  `id_chat` int(11) NOT NULL,
  `message` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `sender_type` varchar(255) NOT NULL DEFAULT 'customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detailkeranjang`
--

CREATE TABLE `detailkeranjang` (
  `id_detail_keranjang` int(11) NOT NULL,
  `id_keranjang` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detailkeranjang`
--

INSERT INTO `detailkeranjang` (`id_detail_keranjang`, `id_keranjang`, `id_barang`, `jumlah`) VALUES
(3, 2, 1, 10),
(4, 2, 3, 5),
(5, 3, 3, 1),
(6, 4, 1, 10),
(7, 4, 2, 5),
(8, 4, 3, 10);

-- --------------------------------------------------------

--
-- Table structure for table `detailpo`
--

CREATE TABLE `detailpo` (
  `id_detail_po` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `id_po` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detailpo`
--

INSERT INTO `detailpo` (`id_detail_po`, `id_barang`, `jumlah`, `id_po`) VALUES
(1, 1, 100, 1),
(2, 2, 200, 2),
(3, 3, 300, 3),
(4, 3, 400, 4),
(5, 3, 500, 5),
(6, 1, 253, 7),
(7, 1, 944, 8),
(8, 3, 999, 9),
(9, 2, 123, 10);

-- --------------------------------------------------------

--
-- Table structure for table `detailtransaksi`
--

CREATE TABLE `detailtransaksi` (
  `id_detailTransaksi` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `id_transaksi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detailtransaksi`
--

INSERT INTO `detailtransaksi` (`id_detailTransaksi`, `id_barang`, `jumlah`, `id_transaksi`) VALUES
(1, 3, 10, 1),
(2, 1, 50, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ekspedisi`
--

CREATE TABLE `ekspedisi` (
  `id_ekspedisi` int(11) NOT NULL,
  `nama_ekspedisi` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ekspedisi`
--

INSERT INTO `ekspedisi` (`id_ekspedisi`, `nama_ekspedisi`) VALUES
(1, 'Toko'),
(2, 'JNE'),
(3, 'TIKI'),
(4, 'POS'),
(5, 'Wahana'),
(6, 'Sicepat'),
(7, 'Ninja'),
(8, 'Lion'),
(9, 'J&T'),
(10, 'AnterAja'),
(11, 'SiCepat'),
(12, 'JET'),
(13, 'Pahala'),
(14, 'Pandu');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(20) NOT NULL,
  `deskripsi` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`, `deskripsi`) VALUES
(1, 'Plastik', 'Lezat lo');

-- --------------------------------------------------------

--
-- Table structure for table `keranjang`
--

CREATE TABLE `keranjang` (
  `id_keranjang` int(11) NOT NULL,
  `id_customer` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `keranjang`
--

INSERT INTO `keranjang` (`id_keranjang`, `id_customer`) VALUES
(2, 4),
(3, 4),
(4, 4);

-- --------------------------------------------------------

--
-- Table structure for table `po`
--

CREATE TABLE `po` (
  `id_po` int(11) NOT NULL,
  `tanggal_pesan` date NOT NULL DEFAULT current_timestamp(),
  `tanggal_kirim` date DEFAULT NULL,
  `no_resi` varchar(20) DEFAULT NULL,
  `status_po` varchar(20) NOT NULL,
  `id_customer` int(11) NOT NULL,
  `id_admin` int(11) DEFAULT NULL,
  `id_ekspedisi` int(11) DEFAULT NULL,
  `surat_po` varchar(255) DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `po`
--

INSERT INTO `po` (`id_po`, `tanggal_pesan`, `tanggal_kirim`, `no_resi`, `status_po`, `id_customer`, `id_admin`, `id_ekspedisi`, `surat_po`, `notes`) VALUES
(1, '2024-06-01', '2024-06-01', 'RESI1234567890', 'Processed', 1, 1, 1, NULL, NULL),
(2, '2024-06-01', '2024-06-05', 'RESI0987654321', 'Shipped', 2, 1, 2, NULL, NULL),
(3, '2024-06-01', '2024-06-10', 'RESI1122334455', 'Delivered', 3, 1, 3, NULL, NULL),
(4, '2024-06-01', '2024-06-15', 'RESI2233445566', 'Cancelled', 4, 1, 4, NULL, NULL),
(5, '2024-06-01', '2024-06-20', 'RESI3344556677', 'Pending', 4, 1, 5, NULL, NULL),
(6, '2024-06-02', NULL, '123', 'pending', 4, 1, 4, NULL, NULL),
(7, '2024-06-02', NULL, '123', 'pending', 4, 1, 5, NULL, NULL),
(8, '2024-06-02', NULL, '123', 'pending', 4, 1, 4, NULL, NULL),
(9, '2024-06-02', NULL, '123', 'pending', 4, 1, 5, NULL, NULL),
(10, '2024-06-02', NULL, '123', 'pending', 4, 1, 4, NULL, NULL),
(11, '2024-06-04', '2024-06-11', 'RESI123981123', 'process', 1, NULL, 1, 'uploads/surat_po/665ecd015c9aeCapture.PNG', NULL),
(12, '2024-06-04', '2024-06-04', '12EE12DAWDAD', 'process', 4, NULL, 3, 'uploads/surat_po/665f20c9d47b6gtg.jpg', NULL),
(13, '2024-06-04', NULL, NULL, 'cancel', 4, NULL, NULL, 'uploads/surat_po/665f21229521fCapture.PNG', '(Rejected by admin) barang entek'),
(14, '2024-06-04', NULL, NULL, 'pending', 4, NULL, NULL, 'uploads/surat_po/665f221f73765gtg.jpg', NULL),
(15, '2024-06-05', '2024-06-06', '87565', 'process', 4, NULL, 2, 'uploads/surat_po/665fbdaf190f8Template SRS.docx', NULL),
(16, '2024-06-05', NULL, NULL, 'cancel', 4, NULL, NULL, 'uploads/surat_po/665fbde2781fb665fb891b8373Template SRS.docx', '(Rejected by admin) lol');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id_sales` int(11) NOT NULL,
  `nama_sales` varchar(20) NOT NULL,
  `no_telp_sales` varchar(12) NOT NULL,
  `email_sales` varchar(20) NOT NULL,
  `password_sales` text NOT NULL,
  `alamat_sales` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id_sales`, `nama_sales`, `no_telp_sales`, `email_sales`, `password_sales`, `alamat_sales`) VALUES
(1, 'saya', '01235984', 'saya@sales.com', '*84AAC12F54AB666ECFC2A83C676908C8BBC381B1', 'sales home');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `tanggal_transaksi` date NOT NULL,
  `no_resi` varchar(20) NOT NULL,
  `tanggal_pengiriman` date NOT NULL,
  `status_transaksi` varchar(20) NOT NULL,
  `id_customer` int(11) NOT NULL,
  `id_ekspedisi` int(11) NOT NULL,
  `id_sales` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `alamat_kirim` text DEFAULT NULL,
  `bukti_bayar` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `tanggal_transaksi`, `no_resi`, `tanggal_pengiriman`, `status_transaksi`, `id_customer`, `id_ekspedisi`, `id_sales`, `total`, `alamat_kirim`, `bukti_bayar`) VALUES
(1, '2024-06-05', '0', '2024-06-05', 'end', 4, 1, 1, 350000, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `antriankilat`
--
ALTER TABLE `antriankilat`
  ADD PRIMARY KEY (`id_antrian`),
  ADD KEY `cust_AK` (`id_customer`);

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id_barang`),
  ADD KEY `kategori_barang` (`id_kategori`);

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id_chat`),
  ADD KEY `cust_chat` (`id_customer`),
  ADD KEY `admin_chat` (`id_admin`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id_customer`);

--
-- Indexes for table `detailak`
--
ALTER TABLE `detailak`
  ADD PRIMARY KEY (`id_detail_AK`),
  ADD KEY `AK_detailAK` (`id_antrian`),
  ADD KEY `barang_AK` (`id_barang`);

--
-- Indexes for table `detailchat`
--
ALTER TABLE `detailchat`
  ADD PRIMARY KEY (`id_detail_chat`),
  ADD KEY `chat_detailChat` (`id_chat`);

--
-- Indexes for table `detailkeranjang`
--
ALTER TABLE `detailkeranjang`
  ADD PRIMARY KEY (`id_detail_keranjang`),
  ADD KEY `barang_detailKeranjang` (`id_barang`),
  ADD KEY `keranjang_detailKeranjang` (`id_keranjang`);

--
-- Indexes for table `detailpo`
--
ALTER TABLE `detailpo`
  ADD PRIMARY KEY (`id_detail_po`),
  ADD KEY `po_detPO` (`id_po`),
  ADD KEY `barang_po` (`id_barang`);

--
-- Indexes for table `detailtransaksi`
--
ALTER TABLE `detailtransaksi`
  ADD PRIMARY KEY (`id_detailTransaksi`),
  ADD KEY `barang_detTrans` (`id_barang`),
  ADD KEY `trans_detTrans` (`id_transaksi`);

--
-- Indexes for table `ekspedisi`
--
ALTER TABLE `ekspedisi`
  ADD PRIMARY KEY (`id_ekspedisi`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`id_keranjang`),
  ADD KEY `cust_keranjang_fk` (`id_customer`);

--
-- Indexes for table `po`
--
ALTER TABLE `po`
  ADD PRIMARY KEY (`id_po`),
  ADD KEY `cust_po` (`id_customer`),
  ADD KEY `admin_po` (`id_admin`),
  ADD KEY `ekspe_po` (`id_ekspedisi`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id_sales`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `cust_trans` (`id_customer`),
  ADD KEY `sales_trans` (`id_sales`),
  ADD KEY `ekspe_trans` (`id_ekspedisi`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `antriankilat`
--
ALTER TABLE `antriankilat`
  MODIFY `id_antrian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `id_chat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id_customer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `detailak`
--
ALTER TABLE `detailak`
  MODIFY `id_detail_AK` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `detailchat`
--
ALTER TABLE `detailchat`
  MODIFY `id_detail_chat` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detailkeranjang`
--
ALTER TABLE `detailkeranjang`
  MODIFY `id_detail_keranjang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `detailpo`
--
ALTER TABLE `detailpo`
  MODIFY `id_detail_po` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `detailtransaksi`
--
ALTER TABLE `detailtransaksi`
  MODIFY `id_detailTransaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ekspedisi`
--
ALTER TABLE `ekspedisi`
  MODIFY `id_ekspedisi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `id_keranjang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `po`
--
ALTER TABLE `po`
  MODIFY `id_po` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id_sales` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `antriankilat`
--
ALTER TABLE `antriankilat`
  ADD CONSTRAINT `cust_AK` FOREIGN KEY (`id_customer`) REFERENCES `customer` (`id_customer`);

--
-- Constraints for table `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `kategori_barang` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`);

--
-- Constraints for table `chat`
--
ALTER TABLE `chat`
  ADD CONSTRAINT `admin_chat` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`),
  ADD CONSTRAINT `cust_chat` FOREIGN KEY (`id_customer`) REFERENCES `customer` (`id_customer`);

--
-- Constraints for table `detailak`
--
ALTER TABLE `detailak`
  ADD CONSTRAINT `AK_detailAK` FOREIGN KEY (`id_antrian`) REFERENCES `antriankilat` (`id_antrian`),
  ADD CONSTRAINT `barang_AK` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`);

--
-- Constraints for table `detailchat`
--
ALTER TABLE `detailchat`
  ADD CONSTRAINT `chat_detailChat` FOREIGN KEY (`id_chat`) REFERENCES `chat` (`id_chat`);

--
-- Constraints for table `detailkeranjang`
--
ALTER TABLE `detailkeranjang`
  ADD CONSTRAINT `barang_detailKeranjang` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`),
  ADD CONSTRAINT `keranjang_detailKeranjang` FOREIGN KEY (`id_keranjang`) REFERENCES `keranjang` (`id_keranjang`);

--
-- Constraints for table `detailpo`
--
ALTER TABLE `detailpo`
  ADD CONSTRAINT `barang_po` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`),
  ADD CONSTRAINT `po_detPO` FOREIGN KEY (`id_po`) REFERENCES `po` (`id_po`);

--
-- Constraints for table `detailtransaksi`
--
ALTER TABLE `detailtransaksi`
  ADD CONSTRAINT `barang_detTrans` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`),
  ADD CONSTRAINT `trans_detTrans` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`);

--
-- Constraints for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD CONSTRAINT `cust_keranjang_fk` FOREIGN KEY (`id_customer`) REFERENCES `customer` (`id_customer`);

--
-- Constraints for table `po`
--
ALTER TABLE `po`
  ADD CONSTRAINT `admin_po` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`),
  ADD CONSTRAINT `cust_po` FOREIGN KEY (`id_customer`) REFERENCES `customer` (`id_customer`),
  ADD CONSTRAINT `ekspe_po` FOREIGN KEY (`id_ekspedisi`) REFERENCES `ekspedisi` (`id_ekspedisi`);

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `cust_trans` FOREIGN KEY (`id_customer`) REFERENCES `customer` (`id_customer`),
  ADD CONSTRAINT `ekspe_trans` FOREIGN KEY (`id_ekspedisi`) REFERENCES `ekspedisi` (`id_ekspedisi`),
  ADD CONSTRAINT `sales_trans` FOREIGN KEY (`id_sales`) REFERENCES `sales` (`id_sales`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
