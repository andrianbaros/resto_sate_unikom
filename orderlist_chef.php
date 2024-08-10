<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "resto_sate";

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil semua pesanan yang belum dibayar
$sql_orders = "SELECT orders.id AS order_id, tables.table_number 
               FROM orders 
               JOIN tables ON orders.table_id = tables.id
               WHERE orders.status != 'paid'";

$result_orders = $conn->query($sql_orders);

// Tambahkan pengecekan jika query gagal
if (!$result_orders) {
    die("Query gagal: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order List - CAFE DEV</title>
        <link rel="stylesheet" href="style/manage.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
 <style>
        .container {
            max-width: 600px;
        }
        .table th, .table td {
            padding: 0.5rem;
            font-size: 0.9rem;
        }
        h2 {
            font-size: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Order List</h2>

        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No Meja</th>
                    <th>Menu Pesanan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_orders->num_rows > 0) {
                    $no = 1;
                    while($order = $result_orders->fetch_assoc()) {
                        $order_id = $order['order_id'];

                        // Ambil detail pesanan
                        $sql_order_items = "SELECT recipes.name 
                                            FROM order_items 
                                            JOIN recipes ON order_items.recipe_id = recipes.id 
                                            WHERE order_items.order_id = '$order_id'";
                        $result_order_items = $conn->query($sql_order_items);

                        // Tambahkan pengecekan jika query gagal
                        if (!$result_order_items) {
                            die("Query gagal: " . $conn->error);
                        }

                        echo "<tr>";
                        echo "<td>" . $no++ . "</td>";
                        echo "<td>Meja " . $order['table_number'] . "</td>";
                        echo "<td>";
                        if ($result_order_items->num_rows > 0) {
                            while($item = $result_order_items->fetch_assoc()) {
                                echo $item['name'] . "<br>";
                            }
                        } else {
                            echo "Tidak ada item pesanan.";
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3' class='text-center'>Tidak ada pesanan.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-q2oaV8js5X6Dr3crBtNiwcs2Vnr6S6qvD1zjOis5EqA6ih7fv5E+zN9sFM/BRRI+"></script>
</body>
</html>

<?php $conn->close(); ?>
                