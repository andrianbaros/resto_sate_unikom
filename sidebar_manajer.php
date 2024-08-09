<?php
session_start();
include 'db_connect.php';

// Check if the user is a manager
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'manager') {
    header("Location: login.php");
    exit();
}

// Check if user ID is set in session
if (!isset($_SESSION['user_id'])) {
    die("User ID not set in session.");
}

$userId = $_SESSION['user_id']; // mengambil id

// Prepare and execute query to get user data
$sql = "SELECT username, role, picture FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $username = $row['username'];
    $role = ucfirst($row['role']); // Capitalize the first letter of the role
    $picture = $row['picture'];
} else {
    echo "User not found.";
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manajer Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style/sidebar-manajer.css">
</head>
<body>
    <div class="sidebar">
        <div class="top">
            <div class="logo">
                <i class="bx bxl-codepen"></i>
                <span class="word-wrap" style= "word-wrap: break-word;">Resto Sate</span>
                
            </div>
            <i class="bx bx-menu" id="btn"></i>
        </div>
        <div class="user">
            <div>
                <p class="bold"><?php echo htmlspecialchars($username); ?></p>
                <p>as Manajer</p>
            </div>  
        </div>
        <ul>
            <li>
                <a href="?q=profile">
                    <i class="bx bx-user"></i>
                    <span class="nav-item">Profile</span>
                </a>
                <span class="tooltip">Profile</span>
            </li>
            <li>
                <a href="?q=dashboard">
                    <i class="bx bx-line-chart"></i>
                    <span class="nav-item">Dashboard</span>
                </a>
                <span class="tooltip">Dashboard</span>
            </li>
            <li>
                <a href="?q=menu">
                    <i class="bx bx-food-menu"></i>
                    <span class="nav-item">Menu</span>
                </a>
                <span class="tooltip">Menu</span>
            </li>
            <li>
                <a href="?q=stock">
                    <i class="bx bx-fridge"></i>
                    <span class="nav-item">Stock</span>
                </a>
                <span class="tooltip">Stock</span>
            </li>
            <li>
                <a href="?q=manage">
                    <i class="bx bxs-group"></i>
                    <span class="nav-item">Manage</span>
                </a>
                <span class="tooltip">Manage Employees</span>
            </li>
             <li>
                <a href="logout.php">
                    <i class="bx bx-log-out"></i>
                    <span class="nav-item">Logout</span>
                </a>
                <span class="tooltip">Manage Employees</span>
            </li>
        </ul>
    </div>

    <div class="main-content">
        <?php
        $q = isset($_GET['q']) ? $_GET['q'] : '';
            switch($q) {
                case "":
                    include "dashboard_manajer.php";
                    break;
                case "profile":
                    include "profile_manajer.php";
                    break;
                case "menu":
                    include "menu_manajer.php";
                    break;
                case "dashboard":
                    include "dashboard_manajer.php";
                    break;
                case "stock":
                    include "stock_manajer.php";
                    break;
                case "manage":
                    include "manage_employees.php";
                    break;
                // Anda dapat menambahkan case lain di sini jika diperlukan
                default:
                    // Opsi default jika 'q' tidak cocok dengan case manapun
                    echo "Halaman tidak ditemukan.";
                    break;
            }
        ?>


        
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
