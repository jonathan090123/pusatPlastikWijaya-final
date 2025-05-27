<?php
session_start();
if ($_SESSION) {
    session_destroy();
    echo '<script type="text/javascript"> window.location.href = "login.php"; </script>';
}
?>