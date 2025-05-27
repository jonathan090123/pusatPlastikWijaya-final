<?php
require_once 'dbConnection.php';
require_once 'DetailAntrianKilat.php';
require_once 'Keranjang2.php';
include 'phpqrcode\qrlib.php';
class AntrianKilat
{
    public $id_AK;
    public $qr;
    public $status;
    public $id_cust;
    public $detailAK;

    public static function getLastID($pdo)
    {
        try {
            $stmt = $pdo->query("SELECT * FROM `antriankilat` ORDER BY `id_antrian` DESC LIMIT 1");
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                return json_decode('{"id_antrian":0}', true);;
            } else {
                return $row;
            }
        } catch (PDOException $e) {
            throw new Exception("Error fetching user: " . $e->getMessage());
        }
    }
    public static function createAK($pdo, $idAK, $qr, $idCustomer, $keranjang_id, $keranjang)
    {
        try {
            //generate QR
            $text = $idAK;
            QRcode::png($text, $qr);
            //Insert AK
            $stmt = $pdo->prepare("INSERT INTO `antriankilat` (`id_antrian`, `qr`, `status`, `id_customer`) VALUES (:idAK, :qrPath, 'on going', :id_customer);");
            $stmt->execute(['idAK' => $idAK, 'qrPath' => $qr, 'id_customer' => $idCustomer]);
            //Insert Detail AK
            //keranjang -> idDetail, idBarang, qty
            for ($i = 0; $i < count($keranjang); $i += 3) {
                $idDetailKeranjang = $keranjang[$i];
                $idBarang = $keranjang[$i + 1];
                $qty = $keranjang[$i + 2];
                $tempDetail = new DetailAntrianKilat();
                $tempDetail->createDetail($pdo, $idAK, $idBarang, $qty);
            }
            //delete DetailKeranjang dan keranjang
            $keranjangDelete = new Keranjang2();
            $keranjangDelete->delete($pdo, $keranjang_id);
            return $qr;
        } catch (PDOException $e) {
            throw new Exception("Error fetching user: " . $e->getMessage());
        }
    }
    public static function getAK($pdo, $idAK)
    {
        try {
            $stmt = $pdo->prepare("SELECT * FROM `antriankilat` WHERE `id_antrian` = :idAK");
            $stmt->execute(['idAK' => $idAK]);
            $ak = new AntrianKilat();
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            $ak->id_AK = $res['id_antrian'];
            $ak->qr = $res['qr'];
            $ak->status = $res['status'];
            $ak->id_cust = $res['id_customer'];
            $detailTemp = new DetailAntrianKilat();
            $ak->detailAK = $detailTemp->getDetail($pdo, $ak->id_AK);
            return $ak;
        } catch (PDOException $e) {
            throw new Exception("Error fetching user: " . $e->getMessage());
        }
    }
    public static function updateAK($pdo, $idAK)
    {
        try {
            $stmt = $pdo->prepare("UPDATE `antriankilat` SET `status`='end' WHERE `id_antrian`= :idAK");
            $stmt->execute(['idAK' => $idAK]);
            return true;
        } catch (PDOException $e) {
            throw new Exception("Error fetching user: " . $e->getMessage());
        }
    }
}

if (isset($_POST['user_id']) && isset($_POST['ak_id']) && isset($_POST['qr']) && isset($_POST['keranjang_id']) && isset($_POST['detail'])) {
    $test = AntrianKilat::createAK($pdo, $_POST['ak_id'], $_POST['qr'], $_POST['user_id'], $_POST["keranjang_id"], $_POST['detail']);
    echo json_encode($test);
} else if (isset($_POST['ak_id'])) {
    $test = AntrianKilat::getAK($pdo, $_POST['ak_id']);
    echo json_encode($test);
} else if (isset($_POST['getID'])) {
    $test = AntrianKilat::getLastID($pdo);
    echo json_encode($test);
}
