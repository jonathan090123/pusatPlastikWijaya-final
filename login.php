<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pusat Plastik Wijaya - Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <style>
    body {
      background-color: #f8f9fa;
      height: 100vh;
    }

    .login-container {
      max-width: 400px;
      margin: auto;
      padding-top: 8%;
    }

    .card {
      border-radius: 1rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .form-control {
      border-radius: 0.5rem;
    }

    .btn-primary {
      border-radius: 0.5rem;
    }
  </style>
</head>

<body>
  <div class="login-container">
    <div class="card p-4">
      <h2 class="text-center mb-4">Login</h2>
      <form>
        <div class="form-floating mb-3">
          <input id="email" type="email" class="form-control" placeholder="name@example.com" required>
          <label for="email">Email address</label>
        </div>
        <div class="form-floating mb-4">
          <input id="passw" type="password" class="form-control" placeholder="Password" required>
          <label for="passw">Password</label>
        </div>
        <button onclick="check()" class="btn btn-primary w-100">Sign in</button>
      </form>
    </div>
  </div>

  <script>
    function check() {
      event.preventDefault();
      let email = $('#email').val();
      let pass = $('#passw').val();
      let domain = email.substring(email.indexOf('@') + 1, email.indexOf("."));
      console.log(email, pass, domain);

      let dataSend = {
        'emailIn': email,
        "passIn": pass
      };

      let url = '';
      let redirect = '';

      if (domain == 'sales') {
        url = 'Sales.php';
        redirect = 'salesAntrianKilatUI.php';
      } else if (domain == 'admin') {
        url = 'Admin.php';
        redirect = 'adminIndex.php';
      } else {
        url = 'Customer.php';
        redirect = 'index.php';
      }

      $.ajax({
        url: url,
        method: "POST",
        dataType: "json",
        data: dataSend,
        success: function (data) {
          if (data) {
            console.log("Data received:", data);
            window.location.href = redirect;
          }
        },
        error: function (xhr, status, error) {
          console.error("Error:", error);
        }
      });
    }
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
