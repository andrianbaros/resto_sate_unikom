<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Ingredients</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <!-- Top Section: Profile Information -->
    <div class="container mt-5">
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

    <!-- Bottom Section: Update Ingredients -->
    <div class="container mt-5">
        <h1>Update Ingredients</h1>

        <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
            <div class="alert alert-success" role="alert">
                Ingredient updated successfully
            </div>
        <?php endif; ?>

        <form action="update_ingredients.php" method="POST">
            <div class="form-group">
                <label for="ingredient">Select Ingredient</label>
                <select class="form-control" id="ingredient" name="ingredient_id" required>
                    <option value="">Choose an ingredient</option>
                    <?php
                    // Database connection parameters
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "resto_sate";

                    // Create connection
                    $conn = new mysqli($servername, $username, $password, $dbname);

                    // Check connection
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    $sql = "SELECT id, name FROM ingredients";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo '<option value="'.$row["id"].'">'.$row["name"].'</option>';
                        }
                    } else {
                        echo '<option value="">No ingredients available</option>';
                    }

                    $conn->close();
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="text" class="form-control" id="quantity" name="quantity" required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</body>
</html>
