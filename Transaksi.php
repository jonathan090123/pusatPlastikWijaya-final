<?php
require_once 'dbConnection.php';
require_once 'DetailTransaksi.php';
require_once 'AntrianKilat2.php';
require_once 'Barang.php';
class Transaksi
{
    public $id_transaksi;
    public $tanggal_transaksi;
    public $no_resi;
    public $tanggal_pengiriman;
    public $status_transaksi;
    public $id_customer;
    public $id_ekspedisi;
    public $id_sales;
    public $total;
    public $detailTransaksi;

    public static function getLastID($pdo)
    {
        try {
            $stmt = $pdo->query("SELECT * FROM `transaksi` ORDER BY `id_transaksi` DESC LIMIT 1");
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                return json_decode('{"id_transaksi":0}', true);;
            } else {
                return $row;
            }
        } catch (PDOException $e) {
            throw new Exception("Error fetching user: " . $e->getMessage());
        }
    }

    public static function createTransaksi($pdo, $transID, $userID, $salesID, $bayar, $barang, $ak)
    {
        try {
            $stmt = $pdo->prepare("INSERT INTO `transaksi`(`id_transaksi`, `tanggal_transaksi`, `no_resi`, `tanggal_pengiriman`, `status_transaksi`, `id_customer`, `id_ekspedisi`, `id_sales`, `total`) VALUES (:transID, CURRENT_DATE,'0',CURRENT_DATE,'end', :id_customer,'1',:id_sales, :total)");
            $stmt->execute(['transID'=> $transID, 'id_customer' => $userID, "id_sales"=>$salesID, 'total'=>$bayar]);
            for ($i = 0; $i < count($barang); $i += 3) {
                $idDetailAntrian = $barang[$i];
                $idBarang = $barang[$i + 1];
                $qty = $barang[$i + 2];
                $tempDetail = new DetailTransaksi();
                $tempDetail->createDetail($pdo, $transID, $idBarang, $qty);
                //update barang
                $barangUpdate = new Barang();
                $barangUpdate->updateStok($pdo, $idBarang, $qty);
                $barangUpdate->updateStat($pdo, $idBarang);
            }
            $akUpdate = new AntrianKilat2();
            $akUpdate->updateAK($pdo, $ak);
            return true;
        } catch (PDOException $e) {
            throw new Exception("Error fetching user: " . $e->getMessage());
        }
    }
}

if (isset($_POST['trans_id']) &&isset($_POST['user_id']) && isset($_POST['sales_id']) && isset($_POST['bayar']) && isset($_POST['bayar']) && isset($_POST['antrian_id'])) {
    $test = Transaksi::createTransaksi($pdo, $_POST['trans_id'], $_POST['user_id'], $_POST['sales_id'], $_POST['bayar'], $_POST['barang'], $_POST['antrian_id']);
    echo json_encode($test);
}
if (isset($_POST['getID'])) {
    $test = Transaksi::getLastID($pdo);
    echo json_encode($test);
}