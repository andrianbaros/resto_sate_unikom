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
        case 'manager':
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

    // hashing password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);


    $target_dir = "image/";
    $target_file = $target_dir . basename($_FILES["picture"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // memeriksa apakah berupa gambar asli atau palsu
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["picture"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    // memeriksa apakah file sudah ada atau belum
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Memeriksa ukuran file
    if ($_FILES["picture"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // memasukkan sesuai format
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file)) {
            // File is uploaded, now insert user data into the database
            $sql = "INSERT INTO users (id, username, password, role, picture) VALUES ('$id', '$username', '$hashed_password', '$role', '$target_file')";

            if ($conn->query($sql) === TRUE) {
                header("Location: login.php");
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
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
        <form id="registerForm" action="register.php" method="post" enctype="multipart/form-data">
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
            <input type="file" name="picture" id="picture" required />
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
