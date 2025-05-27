<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start();

include('dbConnection.php');

function dd($data, $die=true){
    echo "<pre>";
    echo "----------------------------------------------------";
    echo "----------------------------------------------------";
    echo "----------------------------------------------------";
    print_r($data);
    echo "</pre>";
    if($die) die();
}

function getData($pdo, $table) {
    $sql = "SELECT * FROM $table";
    try {
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    } catch (PDOException $e) {
        die("Could not retrieve data: " . $e->getMessage());
    }
}

function getCustomerPO($pdo, $id_customer) {
    $sql = "SELECT * FROM po WHERE id_customer = $id_customer";
    try {
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    } catch (PDOException $e) {
        die("Could not retrieve data: " . $e->getMessage());
    }
}

function getPOByStatus($pdo, $status) {
    $sql = "SELECT po.*, customer.* FROM po
            INNER JOIN customer ON po.id_customer = customer.id_customer
            WHERE status_po = '$status'";
    try {
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    } catch (PDOException $e) {
        die("Could not retrieve data: " . $e->getMessage());
    }
}

function getPODetails($pdo, $id_po) {
    $sql = "SELECT po.*, customer.* FROM po
            INNER JOIN customer ON po.id_customer = customer.id_customer
            WHERE po.id_po = $id_po";
    try {
        $stmt = $pdo->query($sql);
        // Fetch 1 data only
        $results = $stmt->fetch(PDO::FETCH_ASSOC);
        return $results;
    } catch (PDOException $e) {
        die("Could not retrieve data: " . $e->getMessage());
    }
}

function getEkspedisi($pdo) {
    $sql = "SELECT * FROM ekspedisi";
    try {
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    } catch (PDOException $e) {
        die("Could not retrieve data: " . $e->getMessage());
    }
}

function getAllProduct($pdo) {
    $sql = "SELECT * FROM barang";
    try {
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    } catch (PDOException $e) {
        die("Could not retrieve data: " . $e->getMessage());
    }
}

function getListDetailPo($pdo){
    $sql = "SELECT
                detailpo.*,
                po.*,
                barang.*
            FROM
                detailpo
            JOIN
                po ON detailpo.id_po = po.id_po
            JOIN
                barang ON detailpo.id_barang = barang.id_barang
            ORDER BY po.id_po DESC;";
    try {
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    } catch (PDOException $e) {
        die("Could not retrieve data: " . $e->getMessage());
    }
}

function getCustomer($pdo, $userId)
{
    $stmt = $pdo->prepare("SELECT * FROM `customer` WHERE `id_customer`= :idAK");
    $stmt->execute(['idAK' => $userId]);
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    return $res;
}