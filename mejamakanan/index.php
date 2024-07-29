<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Table Layout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .table-layout {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .table {
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 20px;
            width: 150px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .table-number {
            font-size: 1.2em;
            font-weight: bold;
        }
        .table-capacity {
            color: #555;
        }
        .table-status {
            margin-top: 10px;
            padding: 5px;
            border-radius: 5px;
        }
        .ready {
            background-color: #d4edda;
            color: #155724;
        }
        .not-ready {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="table-layout">
        <?php
        // Connect to the database
        $conn = new mysqli('localhost', 'root', '', 'resto_sate');

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch table data from the database
        $sql = "SELECT id, table_number, capacity, status FROM tables";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                $statusClass = $row['status'];
                $statusText = ucfirst(str_replace('-', ' ', $row['status']));
                echo "<div class='table' data-id='{$row['id']}' data-number='{$row['table_number']}' data-capacity='{$row['capacity']}'>
                        <span class='table-number'>Table {$row['table_number']}</span>
                        <span class='table-capacity'>Capacity: {$row['capacity']}</span>
                        <span class='table-status $statusClass'>$statusText</span>
                    </div>";
            }
        } else {
            echo "0 results";
        }
        $conn->close();
        ?>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tables = document.querySelectorAll('.table');

            tables.forEach(table => {
                table.addEventListener('click', () => {
                    const tableId = table.getAttribute('data-id');
                    const statusElement = table.querySelector('.table-status');
                    let newStatus;

                    if (statusElement.classList.contains('ready')) {
                        statusElement.textContent = 'Not Ready';
                        statusElement.classList.remove('ready');
                        statusElement.classList.add('not-ready');
                        newStatus = 'not-ready';
                    } else {
                        statusElement.textContent = 'Ready';
                        statusElement.classList.remove('not-ready');
                        statusElement.classList.add('ready');
                        newStatus = 'ready';
                    }

                    // Send AJAX request to update the status in the database
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', 'update_status.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.send(`id=${tableId}&status=${newStatus}`);
                });
            });
        });
    </script>
</body>
</html>
