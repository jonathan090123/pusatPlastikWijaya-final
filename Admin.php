<?php
require_once 'dbConnection.php';
class Admin
{
    public $idAdmin;
    public $namaAdmin;
    public $telpAdmin;
    public $addressAdmin;

    public static function authUser($pdo, $emailInput, $passwordInput)
    {
        try {
            $stmt = $pdo->prepare('SELECT * FROM admin WHERE email_admin = :emailUser and password_admin=password(:passwordUser)');
            $stmt->execute(['emailUser' => $emailInput, 'passwordUser' => $passwordInput]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            session_start();
            $_SESSION["user"] = $user['nama_admin'];
            $_SESSION["id"] = $user['id_admin'];
            $_SESSION["as"] = "admin";
            return $user;
        } catch (PDOException $e) {
            throw new Exception("Error fetching user: " . $e->getMessage());
        }
    }
}

if (isset($_POST['emailIn']) && isset($_POST['passIn'])) {
    $test = Admin::authUser($pdo, $_POST['emailIn'], $_POST['passIn']);
    echo json_encode($test);
}
