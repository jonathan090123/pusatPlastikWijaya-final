<?php
require_once 'dbConnection.php';
class Sales
{
    public $idSales;
    public $namaSales;
    public $telpSales;
    public $addressSales;

    public static function authUser($pdo, $emailInput, $passwordInput)
    {
        try {
            $stmt = $pdo->prepare('SELECT * FROM sales WHERE email_sales = :emailUser and password_sales=password(:passwordUser)');
            $stmt->execute(['emailUser' => $emailInput, 'passwordUser' => $passwordInput]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            session_start();
            $_SESSION["user"] = $user['nama_sales'];
            $_SESSION["id"] = $user['id_sales'];
            $_SESSION["as"] = "sales";
            return $user;
        } catch (PDOException $e) {
            throw new Exception("Error fetching user: " . $e->getMessage());
        }
    }
}

if (isset($_POST['emailIn']) && isset($_POST['passIn'])) {
    $test = Sales::authUser($pdo, $_POST['emailIn'], $_POST['passIn']);
    echo json_encode($test);
}
