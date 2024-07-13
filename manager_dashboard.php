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
    <title>Manajer Dashboard</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style/sidebar-manajer.css">
</head>
<body>
    <div class="sidebar">
        <div class="top">
            <div class="logo">
                <i class="bx bxl-codepen"></i>
                <span>Resto Sate</span>
            </div>
            <i class="bx bx-menu" id="btn"></i>
        </div>
        <div class="user">
            <img src="image/user-img.jpeg" alt="me" class="user-img">
            <div>
                <p class="bold">Messi</p>
                <p>Manajer</p>
            </div>  
        </div>
        <ul>
            <li>
                <a href="#">
                    <i class="bx bx-user"></i>
                    <span class="nav-item">Profile</span>
                </a>
                <span class="tooltip">Profile</span>
            </li>
            <li>
                <a href="#">
                    <i class="bx bx-line-chart"></i>
                    <span class="nav-item">Dashboard</span>
                </a>
                <span class="tooltip">Dashboard</span>
            </li>
            <li>
                <a href="#">
                    <i class="bx bx-food-menu"></i>
                    <span class="nav-item">Menu</span>
                </a>
                <span class="tooltip">Menu</span>
            </li>
            <li>
                <a href="#">
                    <i class="bx bx-fridge"></i>
                    <span class="nav-item">Stock</span>
                </a>
                <span class="tooltip">Stock</span>
            </li>
            <li>
                <a href="#">
                    <i class="bx bxs-group"></i>
                    <span class="nav-item">Manage Employees</span>
                </a>
                <span class="tooltip">Manage Employees</span>
            </li>
        </ul>
    </div>

    <div class="main-content">
        <div class="container">
            <h1>Manajer Page</h1>
            <h1>Right Side</h1>
        </div>

    </div>
</body>
<script>
    let btn = document.querySelector('#btn');
    let sidebar = document.querySelector('.sidebar');

    btn.onclick = function() {
        sidebar.classList.toggle('active');
    };
</script>
</html>
