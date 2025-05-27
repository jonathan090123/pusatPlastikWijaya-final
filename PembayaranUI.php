<?php
session_start();
if (!$_SESSION) {
    echo '<script type="text/javascript"> window.location.href = "login.php"; </script>';
}
if(!empty($_POST)){
    $target_dir = "uploads/bukti_bayar/";
        $filename = $target_dir . uniqid() . basename($_FILES["bukti_bayar"]["name"]);
            echo '
                <script>
                    confirm("Success insert");
                    window.location.href = "PembayaranUI.php";
                </script>
            ';
            die();
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
    <style>
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .content {
            max-width: 800px;
            width: 100%;
            margin-top: 60px;
        }

        #fileStatus {
            margin-top: 10px;
            margin-left: 10px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-dark" data-bs-theme="dark" style="width: 100%; position: fixed; top: 0; z-index: 1000;">
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

    <div class="content">
        <div class="row">
            <div class="text-center">
                <h1>Pembayaran</h1>
            </div>
            <form id="paymentForm">
                <div class="form-group">
                    <label for="exampleFormControlTextarea1">Tulis Alamat </label>
                    <textarea id="alamat" class="form-control" id="exampleFormControlTextarea1" rows="3" required></textarea>
                </div>

                <div class="form-group">
                    <label for="paymentMethodSelect">Metode Pembayaran</label>
                    <select class="form-control" id="paymentMethodSelect" required>
                        <option value=""> - </option>
                        <option value="BCA">BCA</option>
                        <option value="Mandiri">Mandiri</option>
                        <option value="BRI">BRI</option>
                    </select>
                </div>

                <div class="form-group">
                <label for="exampleFormControlSelect1">Ekspedisi</label>
                <select class="form-control" id="exampleFormControlSelect1" required>
                        <option value=""> - </option>
                        <option value="toko">Toko</option>
                    </select>
                </div>

                <div>
                <label for="tanggal" style="margin-top: 10px">Tanggal Pengiriman</label>
                <input type="date" id="tanggal" required>
                <input type="hidden" id="id_customer" value="<?php echo $_SESSION['id']; ?>">
                <input type="hidden" id="keranjangData" data-keranjang='<?php echo htmlspecialchars(json_encode($keranjangData)); ?>'>                
                </div>

                <table class="table table-hover">
                    <tbody>
                        <script>

                            $(document).ready(function() {
                                function createPembayaran() {
                                    const tanggal_pengiriman = $('#tanggal').val();
                                    console.log(tanggal_pengiriman);
                                    const id_customer = $('#id_customer').val();
                                    const total = '0';
                                    const id_keranjang = sessionStorage.getItem('id_keranjang');
                                    const alamat_kirim = $('#alamat').val();
                                    console.log(alamat_kirim);
                                    $.post('Pembayaran.php', {action: 'createPembayaran', tanggal_pengiriman: tanggal_pengiriman, id_customer: id_customer, total: total, id_keranjang :id_keranjang, alamat_kirim :alamat_kirim}, function(data) {
                                        try {
                                        const response = JSON.parse(data);
                                            if (response.error) {
                                                console.error('Server error:', response.error);
                                            } else if (response.status) {
                                            }
                                        } catch (e) {
                                            console.error('Error parsing JSON:', e, data);
                                        }
                                    });
                                }
                                // function deleteFromKeranjang() {
                                //     const id_keranjang = sessionStorage.getItem('id_keranjang');
                                //     const keranjang = sessionStorage.getItem('keranjang');
                                //     console.log(id_keranjang);
                                //     $.post('Pembayaran.php', {action: 'deleteFromKeranjang', id_keranjang: id_keranjang}, function(data) {
                                //         try {
                                //         const response = JSON.parse(data);
                                //             if (response.error) {
                                //                 console.error('Server error:', response.error);
                                //             } else if (response.status) {
                                //             }
                                //         } catch (e) {
                                //             console.error('Error parsing JSON:', e, data);
                                //         }
                                //     });
                                // }      
                                function insertDetailTransakasi() {
                                    const id_keranjang = sessionStorage.getItem('id_keranjang');
                                    const keranjang = sessionStorage.getItem('keranjang');
                                    console.log(id_keranjang);
                                    $.post('Pembayaran.php', {action: 'insertDetailTransakasi', id_keranjang: id_keranjang}, function(data) {
                                        try {
                                        const response = JSON.parse(data);
                                            if (response.error) {
                                                console.error('Server error:', response.error);
                                            } else if (response.status) {
                                            }
                                        } catch (e) {
                                            console.error('Error parsing JSON:', e, data);
                                        }
                                    });
                                }                                    
                                $('#buktiPembayaran').click(function() {
                                    console.log("tes");
                                    createPembayaran();
                                    // deleteFromKeranjang();
                                    insertDetailTransakasi();
                                });



                                $('#paymentForm').on('submit', function (e) {
                                    e.preventDefault();
                                    if (this.checkValidity() === false) {
                                        e.stopPropagation();
                                    } else {
                                        $('#exampleModal').modal('show');
                                    }
                                    this.classList.add('was-validated');
                                });
                            
                                $('#paymentMethodSelect').on('change', function () {
                                    var paymentMethod = $(this).val();
                                    var modalPaymentInfoDiv = $('#modalPaymentInfo');
                                    var paymentNumber;
                                
                                    switch (paymentMethod) {
                                        case 'BCA':
                                            paymentNumber = '1234567890';
                                            break;
                                        case 'Mandiri':
                                            paymentNumber = '0987654321';
                                            break;
                                        case 'BRI':
                                            paymentNumber = '1122334455';
                                            break;
                                        default:
                                            paymentNumber = '';
                                    }
                                
                                    modalPaymentInfoDiv.html(paymentNumber ? 'Nomor Rekening: ' + paymentNumber : '');
                                });

                            });
                        </script>
                    </tbody>
                </table>
                <button type="submit" id="coba" class="btn btn-success btn-lg btn-block">
                    Upload bukti bayar <span class="glyphicon glyphicon-chevron-right"></span>
                </button>
            </form>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Upload bukti pembayaran</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="modalPaymentInfo" style="margin-top: 10px; margin-left: 10px"></div>
                <form>
                    <div style="margin-left:10px" class="form-group">
                    <input style="margin-top: 10px" type="file" id="v" name="surat_po" required>
                    </div>
                    <div id="fileStatus"></div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="buktiPembayaran">Kirim</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>


</body>
</html>
