<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Makanan</title>
    <style>
        .available {
            filter: none;
            color: green;
        }
        .unavailable {
            filter: grayscale(100%);
            color: gray;
        }
        .food-image {
            width: 200px;
            height: auto;
        }
    </style>
</head>
<body>
    <center>
    <h1>Daftar Makanan</h1>
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

    // Ambil data dari tabel recipes
    $sql_recipes = "SELECT id, name, image FROM recipes";
    $result_recipes = $conn->query($sql_recipes);

    if ($result_recipes->num_rows > 0) {
        // Output data dari setiap resep
        while($recipe = $result_recipes->fetch_assoc()) {
            $recipe_id = $recipe["id"];
            $recipe_name = $recipe["name"];
            $recipe_image = $recipe["image"];

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
            echo "<div class='$class'>";
            echo "<img src='$recipe_image' alt='$recipe_name' class='food-image'>";
            echo "<h2>$recipe_name</h2>";
            echo "</div>";
        }
    } else {
        echo "0 hasil";
    }

    // Tutup koneksi
    $conn->close();
    ?>
</body>
</html>
