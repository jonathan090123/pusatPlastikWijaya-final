<?php
require_once 'dbConnection.php';
require_once 'Perusahaan.php';
class Customer
{
    public $idCustomer;
    public $namaCustomer;
    public $emailCustomer;
    public $telpCustomer;
    public $addressCustomer;
    public $listKeranjang;
    public $perusahaan;

    public static function authUser($pdo, $emailInput, $passwordInput)
    {
        try {
            $stmt = $pdo->prepare('SELECT * FROM customer WHERE email_customer = :emailUser and password_customer=password(:passwordUser)');
            $stmt->execute(['emailUser' => $emailInput, 'passwordUser' => $passwordInput]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            session_start();
            $_SESSION["user"] = $user['nama_customer'];
            $_SESSION["id"] = $user['id_customer'];
            $_SESSION["as"] = "customer";
            return $user;
        } catch (PDOException $e) {
            throw new Exception("Error fetching user: " . $e->getMessage());
        }
    }
    public static function getAllKeranjang($pdo, $userId)
    {
        $customer = new Customer();
        $customer->idCustomer = [];
        $customer->listKeranjang = $userId;
        $keranjangs = new Keranjang();
        $keranjangs->getAllKeranjang($pdo, $customer->idCustomer);
    }
    public static function getCustomer($pdo, $userId)
    {
        $stmt = $pdo->prepare("SELECT * FROM `customer` WHERE `id_customer`= :idAK");
        $stmt->execute(['idAK' => $userId]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        $cust = new Customer();
        $cust->idCustomer = $res['id_customer'];
        $cust->namaCustomer = $res['nama_customer'];
        $cust->telpCustomer = $res['no_telp_customer'];
        $cust->emailCustomer = $res['email_customer'];
        $cust->addressCustomer = $res['alamat_customer'];
        if ($res['id_perusahaan'] != null) {
            $perusahaan = new Perusahaan();
            $cust->perusahaan = $perusahaan->getPerusahaan($pdo, $res['id_perusahaan']);
        }
        return $cust;
    }
}

if (isset($_POST['customer'])) {
    $test = Customer::getCustomer($pdo, $_POST['customer']);
    echo json_encode($test);
} else if (isset($_POST['emailIn']) && isset($_POST['passIn'])) {
    $test = Customer::authUser($pdo, $_POST['emailIn'], $_POST['passIn']);
    echo json_encode($test);
}
