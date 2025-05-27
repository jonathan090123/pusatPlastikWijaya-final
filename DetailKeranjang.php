<?php
require_once 'dbConnection.php';
require_once 'Barang.php';
class DetailKeranjang
{
    public $id_detail;
    public $id_keranjang;
    public $id_barang;
    public $jumlah;
    public $barang;

    public static function getDetailKeranjang($pdo, $idKeranjang)
    {
        try {
            $stmt = $pdo->prepare('SELECT * FROM `detailkeranjang` WHERE id_keranjang = :idKeranjang');
            $stmt->execute(['idKeranjang' => $idKeranjang]);
            $listDetail = [];

            while ($keranjang = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $temp = new DetailKeranjang();
                $temp->id_detail = $keranjang['id_detail_keranjang'];
                $temp->id_keranjang = $keranjang['id_keranjang'];
                $temp->id_barang = $keranjang['id_barang'];
                $temp->jumlah = $keranjang['jumlah'];

                $barangTemp = new Barang();
                $temp->barang = $barangTemp->getBarang($pdo, $temp->id_barang);
                $listDetail[] = $temp;
            }
            return $listDetail;
        } catch (PDOException $e) {
            throw new Exception("Error fetching user: " . $e->getMessage());
        }
    }
    public static function delete($pdo, $idKeranjang)
    {
        try {
            $stmt = $pdo->prepare("DELETE FROM `detailkeranjang` WHERE `id_keranjang`= :idKeranjang");
            $stmt->execute(['idKeranjang' => $idKeranjang]);

            return true;
        } catch (PDOException $e) {
            throw new Exception("Error fetching user: " . $e->getMessage());
        }
    }
}

