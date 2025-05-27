<?php
require_once 'dbConnection.php';
require_once 'DetailKeranjang.php';
class Keranjang
{
    public $id_keranjang;
    public $id_customer;
    public $listDetail;

    public static function getAllKeranjang($pdo, $userID)
    {
        try {
            $stmt = $pdo->prepare('SELECT * FROM `keranjang` WHERE id_customer = :idcust');
            $stmt->execute(['idcust' => $userID]);
            $listKeranjang = [];

            while ($keranjang = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $temp = new Keranjang();
                $temp->id_keranjang = $keranjang['id_keranjang'];
                $temp->id_customer = $keranjang['id_customer'];
                $detailTemp = new DetailKeranjang();
                $temp->listDetail = $detailTemp->getDetailKeranjang($pdo, $temp->id_keranjang);
                $listKeranjang[] = $temp;
            }
            return $listKeranjang;
        } catch (PDOException $e) {
            throw new Exception("Error fetching user: " . $e->getMessage());
        }
    }
    public static function delete($pdo, $keranjang)
    {
        try {
            $detailHapus = new DetailKeranjang();
            $detailHapus->delete($pdo, $keranjang);

            $stmt = $pdo->prepare("DELETE FROM `keranjang` WHERE `id_keranjang`= :keranjang");
            $stmt->execute(['keranjang' => $keranjang]);
            return true;
        } catch (PDOException $e) {
            throw new Exception("Error fetching user: " . $e->getMessage());
        }
    }
}

if (isset($_POST['user_id'])) {
    $test = Keranjang::getAllKeranjang($pdo, $_POST['user_id']);
    echo json_encode($test);
}