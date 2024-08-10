<?php
include 'db_connect.php';

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

// Siapkan data untuk pie chart
$table_data = [];
$date_data = [];
$month_year_data = [];
while($order = $result_history->fetch_assoc()) {
    $table_number = $order['table_number'];
    $order_date = date('d-m-Y', strtotime($order['created_at']));
    $order_month_year = date('M Y', strtotime($order['created_at']));
    
    // Untuk data berdasarkan table number
    if(isset($table_data[$table_number])) {
        $table_data[$table_number] += $order['total_price'];
    } else {
        $table_data[$table_number] = $order['total_price'];
    }
    
    // Untuk data berdasarkan tanggal
    if(isset($date_data[$order_date])) {
        $date_data[$order_date] += $order['total_price'];
    } else {
        $date_data[$order_date] = $order['total_price'];
    }

    // Untuk data berdasarkan bulan dan tahun
    if(isset($month_year_data[$order_month_year])) {
        $month_year_data[$order_month_year] += $order['total_price'];
    } else {
        $month_year_data[$order_month_year] = $order['total_price'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        .container {
            max-width: 600px;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body style="align-content: start;">
     <!-- Top Section: Profile Information -->
    <div>
        <div>
            <table border="0">
                <tr>
                    <td rowspan="2">
                        <h1>
                            <img src="<?php echo htmlspecialchars($picture); ?>" alt="Profile Picture" class="user-img">
                        </h1>
                    </td>
                    <td>
                        <h1>Hello <?php echo htmlspecialchars($username); ?>
                            <hr>
                        </h1>
                    </td>
                </tr>
                <tr>
                    <td align="right">
                        <h3><?php echo htmlspecialchars($role); ?></h3>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="container mt-5">
    <h1> Dashboard </h1>
    </div>
    <div class="container mt-5">
        <!-- Dropdown untuk memilih filter -->
        <label for="filter">View by:</label>
        <select id="filter" class="form-control">
            <option value="table">Table</option>
            <option value="date">Date</option>
            <option value="month_year">Month/Year</option>
        </select>
    </div>
    <div class="container mt-5">
        <canvas id="paymentPieChart" style="width: 400px; height: 400px;"></canvas>
        <script>
            const ctx = document.getElementById('paymentPieChart').getContext('2d');
            const tableData = <?php echo json_encode(array_values($table_data)); ?>;
            const dateData = <?php echo json_encode(array_values($date_data)); ?>;
            const monthYearData = <?php echo json_encode(array_values($month_year_data)); ?>;
            const tableLabels = <?php echo json_encode(array_keys($table_data)); ?>;
            const dateLabels = <?php echo json_encode(array_keys($date_data)); ?>;
            const monthYearLabels = <?php echo json_encode(array_keys($month_year_data)); ?>;

            let paymentData = {
                labels: tableLabels,
                datasets: [{
                    data: tableData,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.3)',
                        'rgba(112, 102, 255, 0.3)',
                        'rgba(255, 159, 64, 0.3)',
                        'rgba(255, 99, 71, 0.4)',
                        'rgba(147, 112, 219, 0.4)',
                        'rgba(72, 61, 139, 0.5)',
                        'rgba(34, 139, 34, 0.5)',
                        'rgba(255, 215, 0, 0.5)',
                        'rgba(0, 255, 255, 0.6)',
                        'rgba(255, 69, 0, 0.6)',
                        'rgba(75, 0, 130, 0.6)',
                        'rgba(255, 20, 147, 0.6)',
                        'rgba(0, 191, 255, 0.7)',
                        'rgba(144, 238, 144, 0.5)',
                        'rgba(255, 182, 193, 0.76)',
                        'rgba(60, 179, 113, 0.7)',
                        'rgba(123, 104, 238, 0.8)',
                        'rgba(106, 90, 205, 0.9)',
                        'rgba(0, 0, 255, 0.7)',
                        'rgba(0, 255, 0, 0.7)',
                        'rgba(128, 0, 128, 0.6)',
                        'rgba(0, 128, 128, 0.7)',
                        'rgba(165, 42, 42, 0.8)',
                        'rgba(255, 99, 71, 0.5)',
                        'rgba(220, 20, 60, 0.6)',
                        'rgba(199, 21, 133, 0.8)',
                        'rgba(0, 206, 209, 0.6)',
                        'rgba(138, 43, 226, 0.3)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255, 99, 71, 1)',
                        'rgba(147, 112, 219, 1)',
                        'rgba(72, 61, 139, 1)',
                        'rgba(34, 139, 34, 1)',
                        'rgba(255, 215, 0, 1)',
                        'rgba(0, 255, 255, 1)',
                        'rgba(255, 69, 0, 1)',
                        'rgba(75, 0, 130, 1)',
                        'rgba(255, 20, 147, 1)',
                        'rgba(0, 191, 255, 1)',
                        'rgba(144, 238, 144, 1)',
                        'rgba(255, 182, 193, 1)',
                        'rgba(60, 179, 113, 1)',
                        'rgba(123, 104, 238, 1)',
                        'rgba(106, 90, 205, 1)',
                        'rgba(0, 0, 255, 1)',
                        'rgba(0, 255, 0, 1)',
                        'rgba(128, 0, 128, 1)',
                        'rgba(0, 128, 128, 1)',
                        'rgba(165, 42, 42, 1)',
                        'rgba(255, 99, 71, 1)',
                        'rgba(220, 20, 60, 1)',
                        'rgba(199, 21, 133, 1)',
                        'rgba(0, 206, 209, 1)',
                        'rgba(138, 43, 226, 1)'
                    ],
                    borderWidth: 1
                }]
            };

            const paymentPieChart = new Chart(ctx, {
                type: 'pie',
                data: paymentData,
            });

document.getElementById('filter').addEventListener('change', function() {
    const filter = this.value;
    if (filter === 'table') {
        paymentPieChart.data.labels = tableLabels;
        paymentPieChart.data.datasets[0].data = tableData;
    } else if (filter === 'date') {
        paymentPieChart.data.labels = dateLabels;
        paymentPieChart.data.datasets[0].data = dateData;
    } else if (filter === 'month_year') {
        paymentPieChart.data.labels = monthYearLabels;
        paymentPieChart.data.datasets[0].data = monthYearData;
    }
    paymentPieChart.update();
});

        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-q2oaV8js5X6Dr3crBtNiwcs2Vnr6S6qvD1zjOis5EqA6ih7fv5E+zN9sFM/BRRI+"></script>
    </div>
</body>
</html>

<?php $conn->close(); ?>
