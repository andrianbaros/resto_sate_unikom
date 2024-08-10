<?php
include 'db_connect.php';

// Ambil data history pesanan yang sudah dibayar
$sql_history = "SELECT orders.id, orders.total_price, orders.payment, orders.created_at, tables.table_number 
                FROM orders 
                JOIN tables ON orders.table_id = tables.id 
                WHERE orders.status = 'paid' 
                ORDER BY orders.created_at DESC";

$result_history = $conn->query($sql_history);

// Tambahkan pengecekan jika query gagal
if (!$result_history) {
    die("Query gagal: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order History - CAFE DEV</title>
    <link rel="stylesheet" href="style/manage.css"> 
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        .container {
            max-width: 800px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Order History</h2>
        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Table Number</th>
                    <th>Total Payment</th>
                    <th>Order Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_history->num_rows > 0) {
                    $no = 1;
                    while($order = $result_history->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $no++ . "</td>";
                        echo "<td>Meja " . $order['table_number'] . "</td>";
                        echo "<td>Rp. " . number_format($order['total_price'], 0, ',', '.') . "</td>";
                        echo "<td>" . date('d-m-Y H:i:s', strtotime($order['created_at'])) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' class='text-center'>No order history available.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-q2oaV8js5X6Dr3crBtNiwcs2Vnr6S6qvD1zjOis5EqA6ih7fv5E+zN9sFM/BRRI+"></script>
</body>
</html>

<?php $conn->close(); ?>
