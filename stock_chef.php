<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Ingredients</title>
    <link rel="stylesheet" href="style/manage.css"> 
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    
</head>
<body>
   
    <!-- Bottom Section: Manage Ingredients -->
    <div class="container mt-5">
         <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>Action</th>
                    <th>Instructions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Add Stock</td>
                    <td>
                        To add stock, navigate to the 'Manage Ingredients' section, click on the 'Add Stock' button, enter the ingredient name and quantity in the provided form, and then click 'Submit' to save the new stock.
                    </td>
                </tr>
                <tr>
                    <td>Update Stock</td>
                    <td>
                        To update stock, select the ingredient you wish to modify from the list in the 'Manage Ingredients' section, click on the 'Update' button, adjust the stock quantity in the form that appears, and then click 'Save' to apply the changes.
                    </td>
                </tr>
                <tr>
                    <td>Delete Stock</td>
                    <td>
                        To delete stock, find the ingredient you want to remove in the 'Manage Ingredients' section, click on the 'Delete' button, confirm the action when prompted, and the ingredient will be removed from the stock list.
                    </td>
                </tr>
            </tbody>
        </table>

        <?php
        include 'db_connect.php'; // Include your database connection

        // Handle form submissions for add, edit, and delete
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $action = isset($_POST['action']) ? $_POST['action'] : '';
            $id = isset($_POST['ingredient_id']) ? $conn->real_escape_string($_POST['ingredient_id']) : '';
            $name = isset($_POST['name']) ? $conn->real_escape_string($_POST['name']) : '';
            $stock = isset($_POST['stock']) ? $conn->real_escape_string($_POST['stock']) : '';

            if ($action == 'add') {
                $sql = "INSERT INTO ingredients (name, stock) VALUES ('$name', '$stock')";
                if ($conn->query($sql) === TRUE) {
                    header("Location: sidebar_chef.php?q=stock");
                    exit();
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }

            } elseif ($action == 'edit' && $id != '') {
                $sql = "UPDATE ingredients SET name='$name', stock='$stock' WHERE id='$id'";
                if ($conn->query($sql) === TRUE) {
                    header("Location: sidebar_chef.php?q=stock");
                    exit();
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }

            } elseif ($action == 'delete' && $id != '') {
                $sql = "DELETE FROM ingredients WHERE id='$id'";
                if ($conn->query($sql) === TRUE) {
                    header("Location: sidebar_chef.php?q=stock");
                    exit();
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            }
        }

        // Fetch existing ingredients
        $sql = "SELECT id, name, stock FROM ingredients";
        $result = $conn->query($sql);
        ?>

        <!-- Notification for successful updates -->
        <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
            <div class="alert alert-success" role="alert">
                Action performed successfully
            </div>
        <?php endif; ?>

        <!-- Form for Add/Edit Ingredient -->
        <form id="ingredientForm" action="stock_chef.php" method="post">
            <input type="hidden" name="ingredient_id" id="ingredient_id">
            <div class="form-group">
                <label for="name">Ingredient Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="stock">Stock</label>
                <input type="number" class="form-control" id="stock" name="stock" required>
            </div>
            <table>
                <tr>
                    <td>
                        <button type="submit" name="action" value="add" class="btn btn-warning" style="width: 200px;">Add Ingredient</button>
                    </td>
                </tr>
                <tr>
                    <td>
                        <button type="submit" name="action" value="edit" class="btn btn-warning" style="width: 200px;">Update Ingredient</button>
                    </td>
                </tr>
                <tr>
                    <td>
                        <button type="submit" name="action" value="delete" class="btn btn-warning" style="width: 200px;">Delete Ingredient</button>
                    </td>
                </tr>
            </table>
            </form>
    </div>
    <div class="container mt-5">
        <!-- Display Existing Ingredients -->
        <h3>Existing Ingredients</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['stock']) . "</td>";
                    echo "<td>
                            <button type='button' onclick=\"editIngredient('" . htmlspecialchars($row['id']) . "', '" . htmlspecialchars($row['name']) . "', '" . htmlspecialchars($row['stock']) . "')\">Select</button>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No ingredients found</td></tr>";
            }
            ?>
        </table>
    </div>

    <script>
        function editIngredient(id, name, stock) {
            document.getElementById('ingredient_id').value = id;
            document.getElementById('name').value = name;
            document.getElementById('stock').value = stock;
            document.querySelector("button[name='action'][value='add']").style.display = 'none';
            document.querySelector("button[name='action'][value='edit']").style.display = 'inline';
            document.querySelector("button[name='action'][value='delete']").style.display = 'inline';
        }
    </script>
</body>
</html>
