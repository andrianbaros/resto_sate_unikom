<?php
include 'db_connect.php';

function generate_id($role) {
    global $conn;
    $prefix = '';
    switch ($role) {
        case 'chef':
            $prefix = 'CH';
            break;
        case 'waiter':
            $prefix = 'WA';
            break;
        case 'cashier':
            $prefix = 'CA';
            break;
        case 'admin':
            $prefix = 'AD';
            break;
        case 'manager': // Add manager role with prefix MA
            $prefix = 'MA';
            break;
    }
    $sql = "SELECT MAX(CAST(SUBSTR(id, 3) AS UNSIGNED)) AS max_id FROM users WHERE role='$role'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $max_id = isset($row['max_id']) ? $row['max_id'] + 1 : 1;
    return sprintf('%s%05d', $prefix, $max_id);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = isset($_POST['role']) ? $_POST['role'] : 'admin';
    $id = generate_id($role);

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (id, username, password, role) VALUES ('$id', '$username', '$hashed_password', '$role')";

    if ($conn->query($sql) === TRUE) {
        header("Location: login.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style/style.css" />
    <title>Register</title>
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <form id="registerForm" action="register.php" method="post">
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
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
