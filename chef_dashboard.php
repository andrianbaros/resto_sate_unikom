<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'chef') {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Chef Dashboard</title>
</head>
<body>
    <h1>Welcome to Chef Dashboard</h1>
    <p>Only accessible by chefs.</p>
</body>
</html>
