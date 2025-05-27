<?php
    include('required.php');
    if (!empty($_SESSION) && $_SESSION['as'] != 'admin') {
        echo '<script type="text/javascript"> window.location.href = "login.php"; </script>';
    }

    if (!empty($_POST)){
        $id_po = $_POST['id_po'];
        $status_po = $_POST['status_po'];
        if ($status_po == 'approve') {
            $status_po = 'process';
            $ekspedisi = $_POST['ekspedisi'];
            $tanggal_kirim = $_POST['tanggal_kirim'];
            $no_resi = $_POST['no_resi'];
            $sql = "UPDATE po SET status_po = '$status_po', id_ekspedisi = $ekspedisi, tanggal_kirim = '$tanggal_kirim', no_resi = '$no_resi' WHERE id_po = $id_po";
        } else {
            $status_po = 'cancel';
            $notes = '(Rejected by admin) '.$_POST['notes'];
            $sql = "UPDATE po SET status_po = '$status_po', notes = '$notes' WHERE id_po = $id_po";
        }
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            echo '<script type="text/javascript"> alert("PO has been updated!"); window.location.href = "AdminPO.php"; </script>';
        } catch (PDOException $e) {
            die("Could not update data: " . $e->getMessage());
        }
    }

    if (!empty($_GET['process']) && (!empty($_GET['process'])=='approve' || !empty($_GET['process'])=='reject') && !empty($_GET['id'])) {
        $ekspedisi = getEkspedisi($pdo);
        $datas = getPODetails($pdo, $_GET['id']);
        // dd($ekspedisi, false);
        // dd($datas);
    } else {
      echo '<script type="text/javascript"> window.location.href = "AdminPO.php"; </script>';
    }

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

<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Pusat Plastik Wijaya</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="keranjangUI.php">Keranjang</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="chatUI.php">Chat</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="PurchaseOrder.php">Purchase Order</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="AdminPO.php">Admin PO</a>
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
        <h1><?= ucfirst($_GET['process']) ?> Purchase Order Form</h1>
    </div>

    <table class="items-table" id="itemsTable">
      <tbody>
        <!-- Items will be added here -->
        <?php foreach($datas as $key=>$data): ?>
        <tr>
          <td><?= $key ?></td>
          <td>: <?= $data ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <br><br><br>

    <?php if ($_GET['process'] == 'approve'): ?>
      <form method="POST">
        <input type="hidden" name="id_po" value="<?= $_GET['id'] ?>">
        <input type="hidden" name="status_po" value="approve">
        <div class="form-group">
          <label for="ekspedisi">Ekspedisi</label>
          <select name="ekspedisi" id="ekspedisi">
            <option value="">-- Pilih Ekspedisi --</option>
            <?php foreach($ekspedisi as $data): ?>
            <option value="<?= $data['id_ekspedisi'] ?>"><?= $data['nama_ekspedisi'] ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label for="tanggal_kirim">Tanggal Kirim</label>
          <input type="date" name="tanggal_kirim" id="tanggal_kirim">
        </div>
        <div class="form-group">
          <label for="no_resi">No Resi</label>
          <input type="text" name="no_resi" id="no_resi">
        </div>
        <div class="form-group text-center">
          <button type="submit" class="btn btn-primary">Approve</button>
        </div>
      </form>
    <?php elseif($_GET['process'] == 'reject'): ?>
      <form method="POST">
        <input type="hidden" name="id_po" value="<?= $_GET['id'] ?>">
        <input type="hidden" name="status_po" value="reject">
        <div class="form-group">
          <label for="notes">Notes</label>
          <textarea name="notes" id="notes" cols="30" rows="10"></textarea>
        </div>
        <div class="form-group text-center">
          <button type="submit" class="btn btn-primary">Reject</button>
        </div>
      </form>
    <?php endif; ?>
</div>

</body>
</html>
