<?php
session_start();

if (!$_SESSION) {
    echo '<script type="text/javascript"> window.location.href = "login.php"; </script>';
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pusat Plastik Wijaya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
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
                        <a class="nav-link active" href="KeranjangUI.php">Keranjang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="PurchaseOrder.php">Purchase Order</a>
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

    <div class="container mt-4">
        <input type="hidden" id="id_customer" value="<?php echo $_SESSION['id'] ?>">
        <button type="button" class="btn btn-danger" onClick="window.location.href='index.php'">Cancel</button>
        <h1>Pilih keranjang!</h1>
        <div id="tabelKeranjang" class="container mt-5">

        </div>
    </div>

    <script>
        function antrian(id) {
            const targetKeranjang = id;
            const daftarBarang = eval($('#' + id).attr('list-barang'));
            $.ajax({
                url: "Barang.php",
                method: "POST",
                dataType: "json",
                data: {
                    "list_barang": daftarBarang
                },
                success: function(data) {
                    if (data['check']) {
                        sessionStorage.setItem("id_keranjang", targetKeranjang);
                        sessionStorage.setItem("keranjang", daftarBarang);
                        window.location.href = "antrianKilatUI.php";
                        console.log("true");
                    } else {
                        console.log("false");
                        alert("stok barang habis");
                        $("."+id+"-1").attr("disabled", true);
                        $("."+id+"-2").attr("disabled", true);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                }
            });
        };

        function online(id) {
            const targetKeranjang = id;
            const daftarBarang = eval($('#' + id).attr('list-barang'));
            console.log(daftarBarang);
            $.ajax({
                url: "Barang.php",
                method: "POST",
                dataType: "json",
                data: {
                    "list_barang": daftarBarang
                },
                success: function(data) {
                    if (data['check']) {
                        sessionStorage.setItem("id_keranjang", targetKeranjang);
                        sessionStorage.setItem("keranjang", daftarBarang);
                        window.location.href = "PembayaranUI.php";
                        console.log("true");
                    } else {
                        console.log("false");
                        alert("stok barang habis");
                        $("."+id+"-1").attr("disabled", true);
                        $("."+id+"-2").attr("disabled", true);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                }
            });
            
        };

        $(document).ready(function() {
            let id_customer = $("#id_customer").val();
            console.log(id_customer);
            $.ajax({
                url: "Keranjang.php",
                method: "POST",
                dataType: "json",
                data: {
                    "user_id": id_customer
                },
                success: function(data) {
                    data.forEach((keranjang, index) => {
                        const tableId = `keranjangTable-${index}`;
                        let keranjang_id = keranjang['id_keranjang'];
                        $('#tabelKeranjang').append('<table class="table table-striped table-bordered" id="' + tableId + '">' +
                            '<thead>' +
                            '<tr>' +
                            '<th colspan="2">Keranjang ' + (index + 1) + '</th>' +
                            '<th><button type="button" id="' + keranjang_id + '" class="btn btn-success '+ keranjang_id+'-1" onClick="antrian(' + keranjang_id + ')" >Antrian Kilat</button></th>' +
                            '<th><button type="button" id="' + keranjang_id + '" class="btn btn-info '+ keranjang_id+'-2" onClick="online(' + keranjang_id + ')">Beli Online</button></th>' +
                            '</tr>' +
                            '<tr>' +
                            '<th>Barang</th>' +
                            '<th>Qty</th>' +
                            '<th>Harga</th>' +
                            '<th>Total</th>' +
                            '</tr>' +
                            '</thead>' +
                            '<tbody>' +
                            '</tbody>' +
                            '<tfoot>' +
                            '</tfoot>' +
                            '</table>');

                        const tableBody = $('#' + tableId + ' tbody');
                        const tableFoot = $('#' + tableId + ' tfoot');
                        let totalKeranjang = 0;
                        let listBarang = [];
                        keranjang["listDetail"].forEach(detailKeranjang => {
                            let localQty = parseInt(detailKeranjang['jumlah']);
                            let localprice = parseInt(detailKeranjang['barang']['harga_barang']);
                            let formatlocalPrice = localprice.toLocaleString('en-US');
                            let subtotal = localQty * localprice;
                            let formatSubTotal = subtotal.toLocaleString('en-US');
                            let data = [detailKeranjang['id_detail'], detailKeranjang['barang']['id_barang'], detailKeranjang['jumlah']];
                            totalKeranjang += subtotal;

                            const row = $('<tr></tr>');
                            row.append('<td>' + detailKeranjang['barang']['nama_barang'] + '</td>');
                            row.append('<td>' + detailKeranjang['jumlah'] + '</td>');
                            row.append('<td> Rp. ' + formatlocalPrice + '</td>');
                            row.append('<td> Rp. ' + formatSubTotal + '</td>');
                            tableBody.append(row);

                            listBarang.push(data);
                        });
                        let formatTotal = totalKeranjang.toLocaleString('en-US');
                        const footerRow = $('<tr></tr>');
                        footerRow.append('<td colspan="3">Total Harga</td>');
                        footerRow.append('<td> Rp. ' + formatTotal + '</td>');
                        tableFoot.append(footerRow);
                        let dataJSON = JSON.stringify(listBarang);
                        const table = document.getElementById(keranjang_id);
                        table.setAttribute("list-barang", dataJSON);
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                }

            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>