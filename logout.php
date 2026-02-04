<?php
session_start();
$role = $_SESSION['role'] ?? 'admin';
session_destroy();
if ($role == 'customer') {
    header('Location: customer_login.php');
} else {
    header('Location: login.php');
}
exit;
?>