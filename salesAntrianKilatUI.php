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
            <a class="navbar-brand" href="salesAntrianKilatUI.php">Pusat Plastik Wijaya</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5 full-height d-flex flex-column  justify-content-center align-items-center" id="here">
        <input type="hidden" id="id_sales" value="<?php echo $_SESSION['id'] ?>">
        <h3 id="sembunyikan">Scan QR Customer!</h3>
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-12 col-md-6">
                    <div id="reader" class="bg-primary">
                        <div id="reader-content" class="d-flex align-items-center justify-content-center">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4" id="toHide">
        <div class="table-responsive">
            <table class="table table-striped table-bordered" id="thisTable">
                <thead>
                    <tr>
                        <th colspan="4" id="namaCustomer">Keranjang 1</th>
                    </tr>
                    <tr>
                        <th>Barang</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                </tfoot>
            </table>
        </div>
        <div class="text-center mt-3">
            <button type="button" id="lunas" class="btn btn-primary" onClick="lunas()">Lunas</button>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
    <script>
        $('#toHide').hide();
        var id_sales = $('#id_sales').val();
        var id_antrian;
        var id_cust;
        var customerObject;
        var detailAKObject;
        var id_trans;
        var totalBayar;
        var barang = [];

        const html5QrCode = new Html5Qrcode("reader");
        const qrCodeSuccessCallback = (decodedText, decodedResult) => {
            console.log(decodedText);
            id_antrian = parseInt(decodedText);
            html5QrCode.stop().then((ignore) => {
                $.ajax({
                    url: "AntrianKilat.php",
                    method: "POST",
                    dataType: "json",
                    data: {
                        "ak_id": id_antrian
                    },
                    success: function(data) {
                        console.log(data);
                        if (data['status']=="on going") {
                            id_cust = data['id_cust'];
                            detailAKObject = data;
                            console.log(detailAKObject);
                            $("#thisTable tbody").empty();
                            $("#thisTable tfoot").empty();
                            $('#sembunyikan').hide();
                            $('#toHide').show();
                            $.ajax({
                                url: "Customer.php",
                                method: "POST",
                                dataType: "json",
                                data: {
                                    "customer": id_cust
                                },
                                success: function(data2) {
                                    customerObject = data2;
                                    console.log(customerObject);
                                    $('#namaCustomer').html("customer: " + data2['namaCustomer'] + " | sales: " + "<?php echo $_SESSION['user'] ?>");
                                },
                                error: function(xhr, status, error) {
                                    console.error("Error:", error);
                                }
                            });
                            const tableBody = $('#thisTable tbody');
                            const tableFoot = $('#thisTable tfoot');
                            let listBarang = [];
                            totalBayar = 0;

                            data["detailAK"].forEach(detailAntrianKilat => {
                                let localQty = parseInt(detailAntrianKilat['jumlah']);
                                let localprice = parseInt(detailAntrianKilat['barang']['harga_barang']);
                                let formatlocalPrice = localprice.toLocaleString('en-US');
                                let subtotal = localQty * localprice;
                                let formatSubTotal = subtotal.toLocaleString('en-US');
                                barang.push(detailAntrianKilat['id_detail'], detailAntrianKilat['barang']['id_barang'], detailAntrianKilat['jumlah']);

                                totalBayar += subtotal;

                                const row = $('<tr></tr>');
                                row.append('<td>' + detailAntrianKilat['barang']['nama_barang'] + '</td>');
                                row.append('<td>' + detailAntrianKilat['jumlah'] + '</td>');
                                row.append('<td> Rp. ' + formatlocalPrice + '</td>');
                                row.append('<td> Rp. ' + formatSubTotal + '</td>');
                                tableBody.append(row);

                                listBarang.push(data);
                            });
                            let formatTotal = totalBayar.toLocaleString('en-US');
                            const footerRow = $('<tr></tr>');
                            footerRow.append('<td colspan="3">Total Harga</td>');
                            footerRow.append('<td> Rp. ' + formatTotal + '</td>');
                            tableFoot.append(footerRow);
                        } else {
                            alert('qr tidak valid');
                            $('#toHide').hide();
                            $('#sembunyikan').show();
                            html5QrCode.start({
                                facingMode: "user"
                            }, config, qrCodeSuccessCallback);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error:", error);
                    }
                });
            }).catch((err) => {
                console.error("Failed to stop the QR code scanner.", err);
            });
        };
        const config = {
            fps: 10,
            qrbox: {
                width: 250,
                height: 250
            }
        };

        html5QrCode.start({
            facingMode: "user"
        }, config, qrCodeSuccessCallback);

        

        function lunas() {
            $.ajax({
                url: "Transaksi.php",
                method: "POST",
                dataType: "json",
                data: {
                    "getID": true
                },
                success: function(data) {
                    if (data["id_transaksi"] == 0) {
                        id_trans = 1;
                    } else {
                        id_trans = parseInt(data["id_transaksi"]) + 1;
                    }
                    $.ajax({
                        url: "Transaksi.php",
                        method: "POST",
                        dataType: "json",
                        data: {
                            "trans_id": id_trans,
                            "user_id": id_cust,
                            "sales_id": id_sales,
                            "bayar": totalBayar,
                            "barang": barang,
                            "antrian_id": id_antrian
                        },
                        success: function(data) {
                            console.log(data);
                            $('#sembunyikan').show();
                            $('#toHide').hide();
                            $('#sembunyikan').show();
                            html5QrCode.start({
                                facingMode: "user"
                            }, config, qrCodeSuccessCallback);
                            $('#sembunyikan').show();
                        },
                        error: function(xhr, status, error) {
                            console.error("Error:", error);
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                }
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>