<?php
// Database configuration
$host = 'localhost';
$dbname = 'resto_sate'; // Replace with your database name
$username = 'root'; // Replace with your database username
$password = ''; // Replace with your database password

// Connect to the database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// Fetch table numbers
$stmtTables = $pdo->query("SELECT id, table_number FROM tables");
$tables = $stmtTables->fetchAll(PDO::FETCH_ASSOC);

// Fetch ready foods
$stmtFoods = $pdo->query("SELECT id, name FROM menu_items");
$foods = $stmtFoods->fetchAll(PDO::FETCH_ASSOC);


// Capture current date and time
$date = date('Y-m-d');
$time = date('H:i:s');

// Display order list form
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Order</title>
</head>
<body>
    <h1>Create Order</h1>
    <form action="save_order.php" method="post">
        <label for="table">Table Number:</label>
        <select name="table" id="table" required>
            <?php foreach ($tables as $table) : ?>
                <option value="<?php echo $table['id']; ?>"><?php echo $table['table_number']; ?></option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label for="foods">Select Ready Foods:</label>
        <select name="foods[]" id="foods" multiple required>
            <?php foreach ($foods as $food) : ?>
                <option value="<?php echo $food['id']; ?>"><?php echo $food['name']; ?></option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <input type="hidden" name="date" value="<?php echo $date; ?>">
        <input type="hidden" name="time" value="<?php echo $time; ?>">

        <input type="submit" value="Create Order">
    </form>
</body>
</html>
