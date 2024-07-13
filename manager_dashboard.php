<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'manager') {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['user_id'])) {
    die("User ID not set in session.");
}

$userId = $_SESSION['user_id']; // mengambil id


$username = "default"; 

$stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
$stmt->bind_param("s", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $username = $row["username"];
    }
} else {
    echo "0 results";
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
                <span>Resto Sate</span>
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
            <table border="0">
                <tr>
                    <td rowspan="2"><h1>
                    <img src="image/user-img.jpeg" alt="me" class="user-img">
                    </td>
                    <td><h1>Hello <?php echo htmlspecialchars($username); ?><hr>
                    </td>
                </tr>
                <tr>
                    <td align="right">
                    <h3>Manager</h3>
                    </td>
                </tr>
            </table>
            </h1>
            <h1>Right Side</h1>

        </div>



        <div>
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
