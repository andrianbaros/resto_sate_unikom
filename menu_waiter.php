<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Makanan</title>
     <link rel="stylesheet" href="style/manage.css"> 
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    
    <style>
        .available img {
            filter: none;
        }
        .unavailable img {
            filter: grayscale(100%);
        }
        .food-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        .food-item {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 10px;
            text-align: center;
            width: 13%;
            box-sizing: border-box;
        }
        .food-image {
            width: 100%;
            height: auto;
        }
        .food-details {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <center>
    <h1>Daftar Makanan</h1>
    <form method="GET" action="">
        <input type="text" name="search" placeholder="Cari makanan...">
        <button type="submit">Cari</button>
    </form>
    <div class="food-container">
    <?php
    // Koneksi ke database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "resto_sate";
    $target_dir = "image/";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Cek koneksi
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Ambil data dari tabel recipes
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $sql_recipes = "SELECT id, name, image, price FROM recipes WHERE name LIKE '%$search%'";
    $result_recipes = $conn->query($sql_recipes);

    if ($result_recipes->num_rows > 0) {
        // Output data dari setiap resep
        while($recipe = $result_recipes->fetch_assoc()) {
            $recipe_id = $recipe["id"];
            $recipe_name = $recipe["name"];
            $recipe_image = $recipe["image"];
            $recipe_price = $recipe["price"];

            // Cek ketersediaan bahan untuk resep ini
            $sql_ingredients = "SELECT i.name, ri.quantity, i.stock
                                FROM recipe_ingredients ri
                                JOIN ingredients i ON ri.ingredient_id = i.id
                                WHERE ri.recipe_id = $recipe_id";
            $result_ingredients = $conn->query($sql_ingredients);

            $available = true;
            while($ingredient = $result_ingredients->fetch_assoc()) {
                if ($ingredient["stock"] < $ingredient["quantity"]) {
                    $available = false;
                    break;
                }
            }

            $class = $available ? "available" : "unavailable";
            $stock_status = $available ? "Available" : "Out of Stock";
            echo "<div class='food-item $class'>";
            echo "<img src='$recipe_image' alt='$recipe_name' class='food-image'>";
            echo "<div class='food-details'>";
            echo "<h2>$recipe_name</h2>";
            echo "<p>Price: Rp. $recipe_price</p>";
            echo "<p>Status: $stock_status</p>";
            echo "</div>";
            echo "</div>";
        }
    } else {
        echo "0 hasil";
    }

    // Tutup koneksi
    $conn->close();
    ?>
    </div>
    </center>
</body>
</html>
