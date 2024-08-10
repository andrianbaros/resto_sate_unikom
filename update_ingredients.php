<?php
// Database connection parameters
$servername = "localhost";
$username = "wheramye_unikom";
$password = "YYRJ%5.]QD#Z";
$dbname = "wheramye_resto_sate";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = htmlspecialchars($_POST['ingredient_id']);
    $quantity = htmlspecialchars($_POST['stock']);

    $sql = "UPDATE ingredients SET stock=? WHERE id=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $quantity, $id);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: stock_manajer.php?status=success");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
