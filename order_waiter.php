<?php
// Koneksi ke database
$servername = "localhost";
$username = "wheramye_unikom";
$password = "YYRJ%5.]QD#Z";
$dbname = "wheramye_resto_sate";

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pesan'])) {
    $meja_id = $_POST['meja'];
    $total_harga = 0;
    $pesanan = [];

    if (isset($_POST['makanan'])) {
        foreach ($_POST['makanan'] as $id_makanan) {
            $porsi = $_POST['porsiMakanan'][$id_makanan];
            $sql_makanan = "SELECT price FROM recipes WHERE id='$id_makanan'";
            $result_makanan = $conn->query($sql_makanan);
            if ($result_makanan->num_rows > 0) {
                $row = $result_makanan->fetch_assoc();
                $harga = $row['price'] * $porsi;
                $total_harga += $harga;
                $pesanan[] = ['id' => $id_makanan, 'porsi' => $porsi, 'harga' => $harga];
            }
        }
    }

    if (isset($_POST['minuman'])) {
        foreach ($_POST['minuman'] as $id_minuman) {
            $porsi = $_POST['porsiMinuman'][$id_minuman];
            $sql_minuman = "SELECT price FROM recipes WHERE id='$id_minuman'";
            $result_minuman = $conn->query($sql_minuman);
            if ($result_minuman->num_rows > 0) {
                $row = $result_minuman->fetch_assoc();
                $harga = $row['price'] * $porsi;
                $total_harga += $harga;
                $pesanan[] = ['id' => $id_minuman, 'porsi' => $porsi, 'harga' => $harga];
            }
        }
    }

    $total_harga += $total_harga * 0.11; // Tambahkan PPN 11%
    if ($meja_id) {
        // Masukkan pesanan ke tabel orders dengan status unpaid
        $sql_order = "INSERT INTO orders (table_id, total_price, status) VALUES ('$meja_id', '$total_harga', 'unpaid')";
        if ($conn->query($sql_order) === TRUE) {
            $order_id = $conn->insert_id;

            // Masukkan setiap item pesanan ke tabel order_items
            foreach ($pesanan as $item) {
                $sql_order_item = "INSERT INTO order_items (order_id, recipe_id, quantity, price) VALUES ('$order_id', '{$item['id']}', '{$item['porsi']}', '{$item['harga']}')";
                $conn->query($sql_order_item);
            }

            // Update status meja menjadi occupied
            $sql_update_table = "UPDATE tables SET status='occupied' WHERE id='$meja_id'";
            $conn->query($sql_update_table);

            echo "<script>alert('Pesanan berhasil! Pembayaran akan dilakukan nanti.');</script>";
        } else {
            echo "<script>alert('Gagal menyimpan pesanan.');</script>";
        }
    } else {
        echo "<script>alert('Meja belum dipilih.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CAFE DEV</title>
    
    <link rel="stylesheet" href="style/manage.css"> 
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        .container {
            max-width: 720px;
        }
        .mb-2 {
            margin-bottom: 0.75rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .border-warning {
            border-color: #ffc107 !important;
        }
        .bg-warning {
            background-color: #ffc107 !important;
        }
    </style>
</head>
<body>
    <div class="container bg-body-tertiary my-5 d-flex justify-content-center align-items-center">
        <form class="border border-warning rounded mt-5 p-4 my-5 " method="POST" action="">
            <div class="text-center bg-warning p-2 mb-4">
                <h2>MENU</h2>
            </div>

            <?php
            // Ambil data meja yang tersedia
            $sql_tables = "SELECT id, table_number, capacity FROM tables WHERE status='ready'";
            $result_tables = $conn->query($sql_tables);

            // Ambil data makanan dan minuman dari database
            $sql_makanan = "SELECT id, name, price FROM recipes WHERE category='makanan'";
            $result_makanan = $conn->query($sql_makanan);

            $sql_minuman = "SELECT id, name, price FROM recipes WHERE category='minuman'";
            $result_minuman = $conn->query($sql_minuman);
            ?>

            <div class="form-group">
                <label for="meja" class="form-label">Pilih Meja</label>
                <select class="form-select" id="meja" name="meja">
                    <option value="" selected>Pilih Meja</option>
                    <?php
                    if ($result_tables->num_rows > 0) {
                        while($row = $result_tables->fetch_assoc()) {
                            echo "<option value='{$row['id']}'>Meja {$row['table_number']} - Kapasitas {$row['capacity']}</option>";
                        }
                    } else {
                        echo "<option value='' disabled>Tidak ada meja yang tersedia</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="namaMakanan" class="form-label">Nama Makanan</label>
                <div id="makananContainer">
                    <?php
                    if ($result_makanan->num_rows > 0) {
                        while($row = $result_makanan->fetch_assoc()) {
                            echo "
                                <div class='row mb-2'>
                                    <div class='col-8'>
                                        <input type='checkbox' id='makanan{$row['id']}' name='makanan[]' value='{$row['id']}' data-harga='{$row['price']}'>
                                        <label for='makanan{$row['id']}'>{$row['name']}</label>
                                    </div>
                                    <div class='col-4'>
                                        <input type='number' class='form-control' id='porsiMakanan{$row['id']}' name='porsiMakanan[{$row['id']}]' min='1' value='1' disabled>
                                    </div>
                                </div>";
                        }
                    }
                    ?>
                </div>
            </div>

            <div class="form-group">
                <label for="namaMinuman" class="form-label">Nama Minuman</label>
                <div id="minumanContainer">
                    <?php
                    if ($result_minuman->num_rows > 0) {
                        while($row = $result_minuman->fetch_assoc()) {
                            echo "
                                <div class='row mb-2'>
                                    <div class='col-8'>
                                        <input type='checkbox' id='minuman{$row['id']}' name='minuman[]' value='{$row['id']}' data-harga='{$row['price']}'>
                                        <label for='minuman{$row['id']}'>{$row['name']}</label>
                                    </div>
                                    <div class='col-4'>
                                        <input type='number' class='form-control' id='porsiMinuman{$row['id']}' name='porsiMinuman[{$row['id']}]' min='1' value='1' disabled>
                                    </div>
                                </div>";
                        }
                    }
                    ?>
                </div>
            </div>

            <div class="form-group text-center">
                <button type="submit" class="btn btn-warning w-50" name="pesan">Pesan</button>
            </div>

            <div class="text-center mt-4">
                <span id="totalBelanja" class="font-weight-bold">Total Belanja: Rp. 0</span>
            </div>

        </form>
    </div>

    <script>
        function updateTotalBelanja() {
            let total = 0;
            document.querySelectorAll('input[type="checkbox"]:checked').forEach(function(checkbox) {
                const porsiInput = document.getElementById(checkbox.id.replace('makanan', 'porsiMakanan').replace('minuman', 'porsiMinuman'));
                const harga = parseInt(checkbox.getAttribute('data-harga')) * parseInt(porsiInput.value);
                total += harga;
            });
            document.getElementById('totalBelanja').textContent = 'Total Belanja: Rp. ' + total;
        }

        document.querySelectorAll('input[type="checkbox"]').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const porsiInput = document.getElementById(this.id.replace('makanan', 'porsiMakanan').replace('minuman', 'porsiMinuman'));
                porsiInput.disabled = !this.checked;
                if (!this.checked) porsiInput.value = 1;
                updateTotalBelanja();
            });
        });

        document.querySelectorAll('input[type="number"]').forEach(function(input) {
            input.addEventListener('change', updateTotalBelanja);
        });
    </script>
</body>
</html>

<?php $conn->close(); ?>
