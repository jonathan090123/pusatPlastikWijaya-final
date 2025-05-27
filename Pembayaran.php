<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'dbConnection.php';
require_once 'Keranjang2.php';

function generateUUID() {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

class Pembayaran {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function createPembayaran($tanggal_pengiriman, $id_customer, $total, $alamat_kirim, $bukti_bayar) {
        $target_dir = "uploads/bukti_bayar/";
        $bukti_bayar = $target_dir . uniqid() . basename($_FILES["bukti_bayar"]["name"]);
        
        $no_resi = generateUUID();
        $id_ekspedisi = 1;
        $tanggal_transaksi = date("Y-m-d");
        try {
            $stmtSales = $this->pdo->prepare("SELECT id_sales FROM sales LIMIT 1");
            $stmtSales->execute();
            $sales = $stmtSales->fetch(PDO::FETCH_ASSOC); 
            $id_sales = $sales['id_sales'];
        
            $stmt = $this->pdo->prepare("INSERT INTO transaksi (tanggal_transaksi, no_resi, tanggal_pengiriman, status_transaksi, id_customer, id_ekspedisi, id_sales, total, alamat_kirim, bukti_bayar) VALUES (:tanggal_transaksi, :no_resi, :tanggal_pengiriman, :status_transaksi, :id_customer, :id_ekspedisi, :id_sales, :total, :alamat_kirim, :bukti_bayar)");
            $stmt->execute([
                'tanggal_transaksi' => $tanggal_transaksi,
                'no_resi' => $no_resi,
                'tanggal_pengiriman' => $tanggal_pengiriman,
                'status_transaksi' => 'on going',
                'id_customer' => $id_customer,
                'id_ekspedisi' => $id_ekspedisi,
                'id_sales' => $id_sales,
                'total' => $total,
                'alamat_kirim' => $alamat_kirim,
                'bukti_bayar' => $bukti_bayar

            ]);
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }
    

    // public function deleteFromKeranjang($id_keranjang) {
    //     try {
    //         $stmt = $this->pdo->prepare("DELETE FROM detailkeranjang WHERE id_keranjang = :id_keranjang");
    //         $stmt->execute(['id_keranjang' => $id_keranjang]);

    //         $stmt = $this->pdo->prepare("DELETE FROM keranjang WHERE id_keranjang = :id_keranjang");
    //         $stmt->execute(['id_keranjang' => $id_keranjang]);
    //         return ['success' => 'Item removed from cart successfully'];
    //     } catch (PDOException $e) {
    //         return ['error' => $e->getMessage()];
    //     }
    // }

    public function insertDetailTransakasi($id_keranjang) {
        try {
            $stmtTransaksi = $this->pdo->prepare("SELECT id_transaksi FROM transaksi ORDER BY id_transaksi DESC LIMIT 1");
            $stmtTransaksi->execute();
            $transaksi = $stmtTransaksi->fetch(PDO::FETCH_ASSOC);
            if (!$transaksi) {
                throw new Exception("Failed to retrieve transaction data");
            }
            $id_transaksi = $transaksi['id_transaksi'];
        
            $stmtItems = $this->pdo->prepare("SELECT id_barang, jumlah FROM detailkeranjang WHERE id_keranjang = :id_keranjang");
            $stmtItems->execute(['id_keranjang' => $id_keranjang]);
            while ($item = $stmtItems->fetch(PDO::FETCH_ASSOC)) {
                $id_barang = $item['id_barang'];
                $jumlah = $item['jumlah'];
            
                $stmt = $this->pdo->prepare("INSERT INTO detailtransaksi (id_barang, jumlah, id_transaksi) VALUES (:id_barang, :jumlah, :id_transaksi)");
                $stmt->execute([
                    'id_barang' => $id_barang,
                    'jumlah' => $jumlah,
                    'id_transaksi' => $id_transaksi,
                ]);
            }

         } catch (PDOException $e) {
            error_log("PDOException in insertDetailTransakasi: " . $e->getMessage());
            return ['error' => $e->getMessage()];
        } catch (Exception $e) {
            error_log("Exception in insertDetailTransakasi: " . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    
}

$pembayaran = new Pembayaran($pdo);
$action = $_POST['action'];

switch ($action) {
    case 'createPembayaran':
        $tanggal_pengiriman = $_POST['tanggal_pengiriman'];
        $id_customer = $_POST['id_customer'];
        $total = $_POST['total'];
        $alamat_kirim = $_POST['alamat_kirim'];
        $bukti_bayar = $_POST['bukti_bayar'];
        echo json_encode([$pembayaran->createPembayaran($tanggal_pengiriman, $id_customer, $total, $alamat_kirim, $bukti_bayar)]);
        break;
    // case 'deleteFromKeranjang':
    //     $id_keranjang = $_POST['id_keranjang'];
    //     echo json_encode(['keranjang_id' => $pembayaran->deleteFromKeranjang($id_keranjang)]);
    //     break;
    case 'insertDetailTransakasi':
        $id_keranjang = $_POST['id_keranjang'];
        echo json_encode(['id_keranjang' => $pembayaran->insertDetailTransakasi(1)]);
    default:
        echo json_encode(['error' => 'Invalid action']);
        break;
}