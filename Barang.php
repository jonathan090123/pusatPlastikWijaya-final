<?php
require_once 'dbConnection.php';
class Barang
{
    public $id_barang;
    public $nama_barang;
    public $detail_barang;
    public $stok_barang;
    public $harga_barang;
    public $status_barang;
    public $id_kategori;

    public static function getBarang($pdo, $idBarang)
    {
        try {
            $stmt = $pdo->prepare('SELECT * FROM `barang` WHERE id_barang = :idBarang');
            $stmt->execute(['idBarang' => $idBarang]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $barang = new Barang();
            $barang->id_barang = $result['id_barang'];
            $barang->nama_barang = $result['nama_barang'];
            $barang->detail_barang = $result['detail_barang'];
            $barang->stok_barang = $result['stok_barang'];
            $barang->harga_barang = $result['harga_barang'];
            $barang->status_barang = $result['status_barang'];
            $barang->id_kategori = $result['id_kategori'];
            return $barang;
        } catch (PDOException $e) {
            throw new Exception("Error fetching user: " . $e->getMessage());
        }
    }
    public static function checkStok($pdo, $barangs)
    {
        for ($i = 0; $i < count($barangs); $i ++) {
            $idDetailKeranjang = $barangs[$i][0];
            $idBarang = $barangs[$i][1];
            $qty = $barangs[$i][2];
            try {
                $stmt = $pdo->prepare('SELECT CASE WHEN (SELECT `stok_barang` FROM `barang` WHERE `id_barang` = :idBarang) > :jumlah THEN true ELSE false END AS "check" FROM DUAL');
                $stmt->execute(['idBarang' => $idBarang, 'jumlah'=>$qty]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($result['check'] == 0) {
                    return false;
                }
            } catch (PDOException $e) {
                throw new Exception("Error fetching user: " . $e->getMessage());
            }   
        }
        return true;
    }
    public static function updateStok($pdo, $idBarang, $jumlah)
    {
        try {
            $stmt = $pdo->prepare('UPDATE `barang` SET `stok_barang`= (SELECT `stok_barang` FROM `barang` WHERE `id_barang` = :idBarang) - :jumlah WHERE `id_barang` = :idBarang');
            $stmt->execute(['idBarang' => $idBarang, 'jumlah'=>$jumlah]);
            return true;
        } catch (PDOException $e) {
            throw new Exception("Error fetching user: " . $e->getMessage());
        }
    }
    public static function updateStat($pdo, $idBarang)
    {
        try {
            $stmt = $pdo->prepare("UPDATE barang SET status_barang = CASE WHEN (SELECT stok_barang FROM barang WHERE id_barang = :idBarang) <= 0 THEN 'habis' END WHERE id_barang = :idBarang");
            $stmt->execute(['idBarang' => $idBarang]);
            return true;
        } catch (PDOException $e) {
            throw new Exception("Error fetching user: " . $e->getMessage());
        }
    }
}

if (isset($_POST['list_barang'])) {
    $test = Barang::checkStok($pdo, $_POST['list_barang']);
    echo json_encode(array("check" => $test));
}
