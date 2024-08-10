<?php
// Connect to the database
$conn = new mysqli('localhost', 'wheramye_unikom', 'YYRJ%5.]QD#Z', 'wheramye_resto_sate');


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get POST data
$orderId = intval($_POST['order_id']);
$newStatus = $_POST['status']; // Expected values: 'pending', 'in-progress', 'completed'

// Update order status
$sql = "UPDATE orders SET status = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $newStatus, $orderId);
$stmt->execute();

$stmt->close();
$conn->close();

// Return success response
echo json_encode(['status' => 'success']);
?>
