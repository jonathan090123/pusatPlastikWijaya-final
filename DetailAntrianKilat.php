<?php
require_once 'dbConnection.php';
require_once 'Barang.php';
class DetailAntrianKilat
{
    public $id_detail;
    public $id_ak;
    public $id_barang;
    public $jumlah;
    public $barang;

    public static function createDetail($pdo, $ak, $barang, $jumlah)
    {
        try {
            $stmt = $pdo->prepare("INSERT INTO `detailak`(`id_antrian`, `jumlah`, `id_barang`) VALUES (:ak,:qty,:barang)");
            $stmt->execute(['ak' => $ak, 'qty' => $jumlah, 'barang' => $barang]);
        } catch (PDOException $e) {
            throw new Exception("Error fetching user: " . $e->getMessage());
        }
    }

    public static function getDetail($pdo, $ak)
    {
        try {
            $stmt = $pdo->prepare("SELECT * FROM `detailak` WHERE `id_antrian` = :idAK");
            $stmt->execute(['idAK' => $ak]);
            $listDetail = [];
            while ($res = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $temp = new DetailAntrianKilat();
                $temp->id_detail = $res['id_detail_AK'];
                $temp->id_ak = $res['id_antrian'];
                $temp->id_barang = $res['id_barang'];
                $temp->jumlah = $res['jumlah'];

                $barangTemp = new Barang();
                $temp->barang = $barangTemp->getBarang($pdo, $temp->id_barang);
                $listDetail[] = $temp;
            }
            return $listDetail;
        } catch (PDOException $e) {
            throw new Exception("Error fetching user: " . $e->getMessage());
        }
    }
}

