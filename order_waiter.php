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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <style>
        .container {
            width: 720px;
        }
        .row>* {
            padding-right: 0;
        }
    </style>
</head>
<body>
    <div class="container bg-body-tertiary">
        <form class="row g-3 needs-validation border border-warning rounded mt-5 p-3" method="POST" action="">
            <div class="text-center bg-warning">
                <h2>CAFE MENU</h2>
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

            <div class="row mt-4">
                <div class="col">
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
            </div>

            <div class="row mt-4">
                <div class="col">
                    <label for="namaMakanan" class="form-label">Nama Makanan</label>
                    <div id="makananContainer">
                        <?php
                        if ($result_makanan->num_rows > 0) {
                            while($row = $result_makanan->fetch_assoc()) {
                                echo "
                                    <div class='row mb-2'>
                                        <div class='col'>
                                            <input type='checkbox' id='makanan{$row['id']}' name='makanan[]' value='{$row['id']}' data-harga='{$row['price']}'>
                                            <label for='makanan{$row['id']}'>{$row['name']}</label>
                                        </div>
                                        <div class='col'>
                                            <input type='number' class='form-control' id='porsiMakanan{$row['id']}' name='porsiMakanan[{$row['id']}]' min='1' value='1' disabled>
                                        </div>
                                    </div>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col">
                    <label for="namaMinuman" class="form-label">Nama Minuman</label>
                    <div id="minumanContainer">
                        <?php
                        if ($result_minuman->num_rows > 0) {
                            while($row = $result_minuman->fetch_assoc()) {
                                echo "
                                    <div class='row mb-2'>
                                        <div class='col'>
                                            <input type='checkbox' id='minuman{$row['id']}' name='minuman[]' value='{$row['id']}' data-harga='{$row['price']}'>
                                            <label for='minuman{$row['id']}'>{$row['name']}</label>
                                        </div>
                                        <div class='col'>
                                            <input type='number' class='form-control' id='porsiMinuman{$row['id']}' name='porsiMinuman[{$row['id']}]' min='1' value='1' disabled>
                                        </div>
                                    </div>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col text-center">
                    <button type="submit" class="btn btn-warning w-25" name="pesan">Pesan</button>
                </div>
            </div>

            <div class="row mt-4">
                <div class="row">
                    <span class="text-center" id="totalBelanja">Total Belanja: Rp. 0</span>
                </div>
            </div>

        </form>
    </div>

    <script>
        function updateTotalBelanja() {
            let total = 0;
            document.querySelectorAll('input[type="checkbox"]:checked').forEach(function(checkbox) {
                const porsiInput = document.getElementById(checkbox.id.replace('makanan', 'porsiMakanan').replace('minuman', 'porsiMinuman'));
                const harga = parseInt(checkbox.getAttribute('data-harga'));
                const porsi = parseInt(porsiInput.value);
                total += harga * porsi;
            });
            total += total * 0.11; // Tambahkan PPN 11%
            document.getElementById('totalBelanja').textContent = `Total Belanja: Rp. ${total.toLocaleString()}`;
        }

        document.querySelectorAll('input[type="checkbox"], input[type="number"]').forEach(function(input) {
            input.addEventListener('change', updateTotalBelanja);
            input.addEventListener('input', updateTotalBelanja);
        });

        document.querySelectorAll('input[type="checkbox"]').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const inputId = this.id.replace('makanan', 'porsiMakanan').replace('minuman', 'porsiMinuman');
                const inputElement = document.getElementById(inputId);
                if (this.checked) {
                    inputElement.removeAttribute('disabled');
                } else {
                    inputElement.setAttribute('disabled', 'disabled');
                }
            });
        });
    </script>
</body>
</html>

<?php $conn->close(); ?>
