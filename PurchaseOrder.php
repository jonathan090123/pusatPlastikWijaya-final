<?php
include('required.php');
if (!empty($_SESSION) && $_SESSION['as'] != 'customer') {
    echo '<script type="text/javascript"> window.location.href = "login.php"; </script>';
}

$datas = getCustomerPO($pdo, $_SESSION['id']);
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
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, select {
            width: 100%;
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
        .add-item-button {
            margin-top: 10px;
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

    <table class="items-table" id="itemsTable">
        <thead>
        <tr>
            <th>No</th>
            <th>Tanggal Pesan</th>
            <th>Tanggal Kirim</th>
            <th>No Resi</th>
            <th>Surat PO</th>
            <th>Status</th>
            <th>Notes</th>
        </tr>
        </thead>
        <tbody>
            <!-- Items will be added here -->
            <?php $i=1; foreach($datas as $data): ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $data['tanggal_pesan']; ?></td>
                <td><?php echo $data['tanggal_kirim']; ?></td>
                <td><?php echo $data['no_resi']; ?></td>
                <td><?php echo !empty($data['surat_po'])?"<a href='".$data['surat_po']."' target='_blank'>View File</a>":"-"; ?></td>
                <td><?php echo ucfirst($data['status_po']); ?></td>
                <td><?php echo !empty($data['notes'])?$data['notes']:"-"; ?></td>
            </tr>
            <?php $i++; endforeach; ?>
        </tbody>
    </table>

    <br><br>
    <a href="POCustomerUI.php" class="add-item-button">Add Purchase Order</a>
</div>

</body>
</html>
