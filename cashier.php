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

// Handle pembayaran
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['bayar'])) {
    $order_id = $_POST['order_id'];
    $uang_dibayar = $_POST['uang_dibayar'];
    $total_harga = $_POST['total_harga'];
    $kembalian = $uang_dibayar - $total_harga;

    if ($uang_dibayar >= $total_harga) {
        // Update status order menjadi 'paid'
        $sql_update_order = "UPDATE orders SET status='paid' WHERE id='$order_id'";
        if ($conn->query($sql_update_order) === TRUE) {
            // Update status meja menjadi 'ready'
            $sql_update_table = "UPDATE tables SET status='ready' WHERE id=(SELECT table_id FROM orders WHERE id='$order_id')";
            $conn->query($sql_update_table);
            
            echo "<script>alert('Pembayaran berhasil! Kembalian: Rp. " . number_format($kembalian, 0, ',', '.') . "');</script>";
        } else {
            echo "<script>alert('Gagal melakukan pembayaran.');</script>";
        }
    } else {
        echo "<script>alert('Uang yang dibayarkan kurang.');</script>";
    }
}

// Ambil semua pesanan yang belum dibayar
$sql_orders = "SELECT orders.id AS order_id, orders.total_price, orders.status, tables.table_number 
               FROM orders 
               JOIN tables ON orders.table_id = tables.id 
               WHERE orders.status = 'unpaid'";

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
    <title>Kasir - CAFE DEV</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Kasir - List Pesanan</h2>

        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No Meja</th>
                    <th>Total Harga</th>
                    <th>Detail Pesanan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_orders->num_rows > 0) {
                    $no = 1;
                    while($order = $result_orders->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $no++ . "</td>";
                        echo "<td>Meja " . $order['table_number'] . "</td>";
                        echo "<td>Rp. " . number_format($order['total_price'], 0, ',', '.') . "</td>";
                        echo "<td><button class='btn btn-info' data-bs-toggle='modal' data-bs-target='#detailModal{$order['order_id']}'>Lihat Detail</button></td>";
                        echo "<td>
                                <form method='POST' action=''>
                                    <input type='hidden' name='order_id' value='{$order['order_id']}'>
                                    <input type='hidden' name='total_harga' value='{$order['total_price']}'>
                                    <div class='input-group'>
                                        <input type='number' class='form-control' name='uang_dibayar' placeholder='Masukkan Uang' required>
                                        <button type='submit' name='bayar' class='btn btn-success'>Bayar</button>
                                    </div>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>Tidak ada pesanan yang belum dibayar.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal for Order Details -->
    <?php
    // Ambil ulang pesanan untuk modal
    if ($result_orders->num_rows > 0) {
        $result_orders->data_seek(0); // Reset pointer result set
        while($order = $result_orders->fetch_assoc()) {
            $order_id = $order['order_id'];

            // Ambil detail pesanan
            $sql_order_items = "SELECT recipes.name, order_items.quantity, order_items.price 
                                FROM order_items 
                                JOIN recipes ON order_items.recipe_id = recipes.id 
                                WHERE order_items.order_id = '$order_id'";
            $result_order_items = $conn->query($sql_order_items);
            
            // Tambahkan pengecekan jika query gagal
            if (!$result_order_items) {
                die("Query gagal: " . $conn->error);
            }
            ?>

            <div class="modal fade" id="detailModal<?php echo $order_id; ?>" tabindex="-1" aria-labelledby="detailModalLabel<?php echo $order_id; ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="detailModalLabel<?php echo $order_id; ?>">Detail Pesanan - Meja <?php echo $order['table_number']; ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <ul class="list-group">
                                <?php
                                if ($result_order_items->num_rows > 0) {
                                    while($item = $result_order_items->fetch_assoc()) {
                                        echo "<li class='list-group-item d-flex justify-content-between align-items-center'>";
                                        echo $item['name'] . " (x" . $item['quantity'] . ")";
                                        echo "<span class='badge bg-primary rounded-pill'>Rp. " . number_format($item['price'], 0, ',', '.') . "</span>";
                                        echo "</li>";
                                    }
                                } else {
                                    echo "<li class='list-group-item'>Tidak ada item pesanan.</li>";
                                }
                                ?>
                            </ul>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>

            <?php
        }
    }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-q2oaV8js5X6Dr3crBtNiwcs2Vnr6S6qvD1zjOis5EqA6ih7fv5E+zN9sFM/BRRI+"></script>
</body>
</html>

<?php $conn->close(); ?>
