<?php
    require_once 'dbConnection.php';
    require_once 'Barang.php';
    class DetailTransaksi{
        public $id_detail;
        public $id_keranjang;
        public $id_barang;
        public $jumlah;
        public $barang;
        //blom edit blas
        public static function createDetail($pdo, $idTrans, $idBarang, $qty){
            try {
                $stmt = $pdo->prepare("INSERT INTO `detailtransaksi`(`id_barang`, `jumlah`, `id_transaksi`) VALUES (:idBarang, :jumlah, :idTrans)");
                $stmt->execute(['idBarang' => $idBarang, 'jumlah'=>$qty,'idTrans'=>$idTrans]);
                return true;
            } catch (PDOException $e) {
                throw new Exception("Error fetching user: " . $e->getMessage());
            }
        }
    }
?>