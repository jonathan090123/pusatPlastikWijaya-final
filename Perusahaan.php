<?php
require_once 'dbConnection.php';
class Perusahaan
{
    public $id;
    public $nama;
    public $email;
    public $telp;
    public $address;

    public static function getPerusahaan($pdo, $id)
    {
        $stmt = $pdo->prepare("SELECT * FROM `perusahaan` WHERE `id_perusahaan` = :idAK");
        $stmt->execute(['idAK' => $id]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        $cust = new Perusahaan();
        $cust->id = $res['id_perusahaan'];
        $cust->nama = $res['nama_perusahaan'];
        $cust->telp = $res['no_telp'];
        $cust->email = $res['email'];
        $cust->address = $res['alamat_perusahaan'];
        return $cust;
    }
}