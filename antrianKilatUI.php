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
            <div class="navbar-brand">
                <a class="navbar-brand" href="index.php">Pusat Plastik Wijaya</a>
                <a class="navbar-brand" href="#">/</a>
                <b>
                    <a class="navbar-brand" href="#">Antrian Kilat</a>
                </b>
            </div>
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

    <div class="container mt-5 full-height d-flex flex-column  justify-content-center align-items-center" id="here">
        <input type="hidden" id="id_customer" value="<?php echo $_SESSION['id'] ?>">
        <h3 class="mt-4">Silahkan tunjukan QR ini kepada sales!</h3>
    </div>

    <script>
        $(document).ready(function() {
            let id_customer = $("#id_customer").val();
            console.log(id_customer);
            let id_keranjang = sessionStorage.getItem('id_keranjang');
            let keranjang = sessionStorage.getItem('keranjang');
            let temp = keranjang.split(',');
            keranjang = temp;
            console.log(keranjang);

            var id_antrian;

            $.ajax({
                url: "AntrianKilat.php",
                method: "POST",
                dataType: "json",
                data: {
                    "getID": true
                },
                success: function(data) {
                    if (data["id_antrian"] == 0) {
                        id_antrian = 1;
                    } else {
                        id_antrian = parseInt(data["id_antrian"]) + 1;
                    }
                    console.log(id_antrian);
                    $.ajax({
                        url: "AntrianKilat.php",
                        method: "POST",
                        dataType: "json",
                        data: {
                            "user_id": id_customer,
                            "ak_id": id_antrian,
                            "qr": "qr/" + id_antrian + ".png",
                            "keranjang_id": id_keranjang,
                            'detail': keranjang
                        },
                        success: function(data) {
                            console.log(data);
                            var img = document.createElement('img');
                            img.className  = "img-fluid";
                            img.src = data;
                            img.width = 400;
                            img.height = 400;
                            document.getElementById('here').appendChild(img);
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
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>