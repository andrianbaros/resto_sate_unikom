<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'waiter') {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Waiters Dashboard</title>
</head>
<body>
    <h1>Welcome to Waiters Dashboard</h1>
    <p>Only accessible by Waiters.</p>
</body>
</html>
