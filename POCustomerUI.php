<?php
include('required.php');

if(!empty($_POST)){
    $stmt = $pdo->prepare("UPDATE customer SET nama_customer = :nama_customer, alamat_customer = :alamat_customer, no_telp_customer = :no_telp_customer WHERE id_customer = :id_customer");
    $stmt->execute([
        'nama_customer' => $_POST['name'],
        'alamat_customer' => $_POST['address'],
        'no_telp_customer' => $_POST['phone'],
        'id_customer' => $_SESSION['id']
    ]);

    // Upload file
    $target_dir = "uploads/surat_po/";
    $filename = $target_dir . uniqid() . basename($_FILES["surat_po"]["name"]);
    if (move_uploaded_file($_FILES["surat_po"]["tmp_name"], $filename)) {
        // // insert detail po
        $stmt = $pdo->prepare("INSERT INTO po (id_customer, tanggal_pesan, status_po, surat_po) VALUES (:id_customer, :tanggal_pesan, :status_po, :surat_po)");
        $stmt->execute([
            'id_customer' => $_SESSION['id'],
            'tanggal_pesan' => date('Y-m-d'),
            'status_po' => 'pending',
            'surat_po' => $filename
        ]);

        echo '
            <script>
                confirm("Success insert");
                window.location.href = "PurchaseOrder.php";
            </script>
        ';
        die();
    }
}

$customer = getCustomer($pdo, $_SESSION['id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Order Form</title>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            padding: 10px 0;
            background-color: #f8f8f8;
        }
        .form-group {
            margin-bottom: 15px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
        }
        .form-group label {
            width: 20%;
            margin-right: 10px;
        }
        .form-group input, .form-group textarea, .form-group select {
            width: 75%;
            padding: 8px;
            box-sizing: border-box;
        }
        .form-group input[type=number]::-webkit-outer-spin-button,
        .form-group input[type=number]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        .form-group input[type=number] {
            -moz-appearance: textfield;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .items-table th, .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .items-table th {
            background-color: #f2f2f2;
        }
        .form-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }
        .add-item-button {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        .add-item-button:hover {
            background-color: #0056b3;
        }
    </style>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>

<nav class="navbar navbar-expand-lg bg-dark" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Pusat Plastik Wijaya</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="KeranjangUI.php">Keranjang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="PurchaseOrder.php">Purchase Order</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="chatUI.php">Chat</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

<div class="container">
    <div class="header">
        <h1>Purchase Order Form</h1>
    </div>

    <form id="purchaseOrderForm" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Nama Perusahaan</label>
            <input type="text" id="name" name="name" value="<?=$customer['nama_customer']?>" required>
        </div>
        <div class="form-group">
            <label for="address">Alamat</label>
            <textarea id="address" name="address" required><?=$customer['alamat_customer']?></textarea>
        </div>
        <div class="form-group">
            <label for="phone">No HP</label>
            <input type="text" id="phone" name="phone" value="<?=$customer['no_telp_customer']?>" required>
        </div>
        <div class="form-group">
            <label for="surat_po">Upload Surat PO</label>
            <input type="file" id="surat_po" name="surat_po" required>
        </div>
        <div class="form-actions">
            <button type="submit" class="add-item-button">Submit</button>
        </div>
    </form>
    
</div>

</body>
</html>
