<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the table ID and new status from the request
    $id = intval($_POST['id']);
    $status = $_POST['status'];

    // Connect to the database
    $conn = new mysqli('localhost', 'root', '', 'resto_sate');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update the table status in the database
    $stmt = $conn->prepare("UPDATE tables SET status = ? WHERE id = ?");
    $stmt->bind_param('si', $status, $id);

    if ($stmt->execute()) {
        echo "Status updated successfully";
    } else {
        echo "Error updating status: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
