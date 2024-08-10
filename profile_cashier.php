<?php

include 'db_connect.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
} // Ensure session is started

// Check if the user is a manager
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'cashier') {
    header("Location: index.php");
    exit();
}

// Check if user ID is set in session
if (!isset($_SESSION['user_id'])) {
    die("User ID not set in session.");
}

$userId = $_SESSION['user_id']; // get user id from session

// Fetch user data
$sql = "SELECT id, username, role, picture, password FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $username = $row['username'];
    $picture = $row['picture'];
} else {
    echo "User not found.";
    exit();
}

$stmt->close();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username = isset($_POST['username']) ? $conn->real_escape_string($_POST['username']) : $username;
    $new_password = isset($_POST['password']) ? $_POST['password'] : '';

    $target_dir = "image/";
    $picture = $row['picture']; // Keep existing picture by default

    if (isset($_FILES["picture"]) && $_FILES["picture"]["tmp_name"]) {
        $target_file = $target_dir . basename($_FILES["picture"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if file is an image
        $check = getimagesize($_FILES["picture"]["tmp_name"]);
        if ($check === false) {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["picture"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Try to upload file if validation is successful
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file)) {
                $picture = $target_file; // Update picture path if file is uploaded
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }

    // Update user data in database
    if (!empty($new_password)) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET username=?, password=?, picture=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $new_username, $hashed_password, $picture, $userId);
    } else {
        $sql = "UPDATE users SET username=?, picture=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $new_username, $picture, $userId);
    }

    if ($stmt->execute()) {
        header("Location: sidebar_cashier.php?q=profile"); // Refresh page to show updated data
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier Profile</title>
    <link rel="stylesheet" href="style/manage.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        .password-container {
            position: relative;
        }
        .password-container input[type="password"] {
            width: calc(100% - 40px);
            padding-right: 40px;
        }
        .password-container .toggle-btn {
            position: absolute;
            right: 50px;
            top: 75%;
            transform: translateY(-50%);
            border: none;
            background: none;
            cursor: pointer;
            color: #007bff;
            font-size: 1rem;
        }
        .password-container .toggle-btn:focus {
            outline: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="profile-container">
            <h2>Profile Information</h2>
            <form action="profile_cashier.php" method="post" enctype="multipart/form-data">
                <div class="profile-picture">
                    <img src="<?php echo htmlspecialchars($picture); ?>" alt="Profile Picture" width="150">
                    <input type="file" name="picture" class="form-control mt-2">
                </div>
                <div class="profile-details">
                    <div class="form-group">
                        <label for="username"><strong>Username:</strong></label>
                        <input type="text" name="username" id="username" class="form-control" value="<?php echo htmlspecialchars($username); ?>" required>
                    </div>
                    <div class="form-group password-container">
                        <label for="password"><strong>Password:</strong></label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Leave blank to keep unchanged">
                        <button type="button" class="toggle-btn" id="togglePassword">Show</button>
                    </div>
                    <button type="submit" class="btn btn-warning mt-3">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordField = document.getElementById('password');
            const button = document.getElementById('togglePassword');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                button.textContent = 'Hide';
            } else {
                passwordField.type = 'password';
                button.textContent = 'Show';
            }
        });
    </script>
</body>
</html>
