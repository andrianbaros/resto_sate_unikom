<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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

// Handle form submissions for add, edit, and delete
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    $id = isset($_POST['id']) ? $conn->real_escape_string($_POST['id']) : '';
    $username = isset($_POST['username']) ? $conn->real_escape_string($_POST['username']) : '';
    $role = isset($_POST['role']) ? $conn->real_escape_string($_POST['role']) : 'admin';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    $target_dir = "image/";
    $target_file = $target_dir . basename($_FILES["picture"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Memeriksa apakah gambar asli atau palsu
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["picture"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Memeriksa apakah file sudah ada atau belum
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Memeriksa ukuran file
    if ($_FILES["picture"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Memastikan format file sesuai
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    if ($action == 'add') {
        $id = generate_id($role);

        // Hashing password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file)) {
                // File berhasil diunggah, sekarang masukkan data pengguna ke dalam database
                $sql = "INSERT INTO users (id, username, password, role, picture) VALUES ('$id', '$username', '$hashed_password', '$role', '$target_file')";

                if ($conn->query($sql) === TRUE) {
                    header("Location: sidebar_manajer.php?q=manage");
                    exit();
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }

    } elseif ($action == 'edit' && $id != '') {
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            if ($uploadOk == 1) {
                if (move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file)) {
                    $sql = "UPDATE users SET username='$username', password='$hashed_password', role='$role', picture='$target_file' WHERE id='$id'";
                }
            } else {
                $sql = "UPDATE users SET username='$username', password='$hashed_password', role='$role' WHERE id='$id'";
            }
        } else {
            if ($uploadOk == 1) {
                if (move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file)) {
                    $sql = "UPDATE users SET username='$username', role='$role', picture='$target_file' WHERE id='$id'";
                }
            } else {
                $sql = "UPDATE users SET username='$username', role='$role' WHERE id='$id'";
            }
        }

        if ($conn->query($sql) === TRUE) {
            header("Location: sidebar_manajer.php?q=manage");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

    } elseif ($action == 'delete' && $id != '') {
        $sql = "DELETE FROM users WHERE id='$id'";

        if ($conn->query($sql) === TRUE) {
            header("Location: sidebar_manajer.php?q=manage");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Fetch existing employees
$sql = "SELECT id, username, role, picture FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Employees</title>
    <link rel="stylesheet" href="style/manage.css">
</head>
<body>
    <div class="container">
        <h2>Manage Employees</h2>
        <form id="employeeForm" action="manage_employees.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" id="id">
            <input type="text" placeholder="Username" name="username" id="username" required>
            <input type="password" placeholder="Password (leave blank to keep unchanged)" name="password" id="password">
            <div>
                <label for="role">Role:</label>
                <input type="radio" id="chef" name="role" value="chef" required>
                <label for="chef">Chef</label>
                <input type="radio" id="waiter" name="role" value="waiter" required>
                <label for="waiter">Waiter</label>
                <input type="radio" id="cashier" name="role" value="cashier" required>
                <label for="cashier">Cashier</label>
                <input type="radio" id="manager" name="role" value="manager" required>
                <label for="manager">Manager</label>
            </div>
            <input type="file" name="picture" id="picture">
            <button type="submit" name="action" value="add">Add Employee</button>
            <button type="submit" name="action" value="edit">Edit Employee</button>
            <button type="submit" name="action" value="delete">Delete Employee</button>
        </form>
    </div>
    <div class="container" style="background-color: rgba(255, 255, 255, 0.1);"></div>
    <div class="container">
        <h3>Existing Employees</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Picture</th>
                <th>Actions</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['role']) . "</td>";
                    echo "<td><img src='" . htmlspecialchars($row['picture']) . "' alt='Profile Picture' width='50'></td>";
                    echo "<td>
                            <button type='button' onclick=\"editEmployee('" . htmlspecialchars($row['id']) . "', '" . htmlspecialchars($row['username']) . "', '" . htmlspecialchars($row['role']) . "', '" . htmlspecialchars($row['picture']) . "')\">Select</button>
                            
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No employees found</td></tr>";
            }
            ?>
        </table>
    </div>

    <script>
        function editEmployee(id, username, role, picture) {
            document.getElementById('id').value = id;
            document.getElementById('username').value = username;
            document.getElementById('password').value = ''; // Password should be left empty for editing
            document.querySelector(`input[name='role'][value='${role}']`).checked = true;
            document.getElementById('picture').value = ''; // This doesn't actually change the file input value due to security reasons
            document.querySelector("button[name='action'][value='add']").style.display = 'none';
            document.querySelector("button[name='action'][value='edit']").style.display = 'inline';
            document.querySelector("button[name='action'][value='delete']").style.display = 'inline';
        }

        function deleteEmployee(id) {
            if (confirm('Are you sure you want to delete this employee?')) {
                document.getElementById('id').value = id;
                document.querySelector("button[name='action'][value='delete']").click();
            }
        }
    </script>
</body>
</html>
