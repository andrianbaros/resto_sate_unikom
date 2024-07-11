<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Administrator Dashboard</title>
</head>
<body>
    <h1>Welcome to Administrator Dashboard</h1>
    <p>Only accessible by Administrator.</p>
</body>
</html>
