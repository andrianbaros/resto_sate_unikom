<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'manager') {
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
    <h1>Welcome to Manager Dashboard</h1>
    <p>Only accessible by Manager.</p>
</body>
</html>
