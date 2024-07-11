<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'cashier') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cashier Dashboard</title>
</head>
<body>
    <h1>Welcome to Cashier Dashboard</h1>
    <p>Only accessible by Cashier.</p>
</body>
</html>
