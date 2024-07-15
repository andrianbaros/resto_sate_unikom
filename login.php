<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];


    $stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND role=?");
    $stmt->bind_param("ss", $username, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id']; // nyimpen id(WAJIB)
            $_SESSION['role'] = $role;
            switch ($role) {
                case 'chef':
                    header("Location: chef_dashboard.php");
                    break;
                case 'waiter':
                    header("Location: waiters_dashboard.php");
                    break;
                case 'cashier':
                    header("Location: cashier_dashboard.php");
                    break;
                case 'manager':
                    header("Location:sidebar_manajer.php");
                    break;
            }
            exit();
        } else {
            echo "Incorrect password!";
        }
    } else {
        echo "Incorrect username or role!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style/style.css" />
    <title>Login</title>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form id="loginForm" action="login.php" method="post">
            <input type="text" placeholder="Username" name="username" required />
            <input type="password" placeholder="Password" name="password" required />
            <div style="display: flex; align-items: center; margin-top: 5px">
                <label for="role">Select Role:</label>
                <input type="radio" id="chef" name="role" value="chef" required />
                <label for="chef">Chef</label>
                <input type="radio" id="waiter" name="role" value="waiter" required />
                <label for="waiter">Waiter</label>
                <input type="radio" id="cashier" name="role" value="cashier" required />
                <label for="cashier">Cashier</label>
                <input type="radio" id="manager" name="role" value="manager" required />
                <label for="manager">Manager</label>
            </div>
            <button type="submit">Let's Work!</button>
        </form>
    </div>
</body>
</html>
