<?php
include 'db_connect.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = floatval($_POST['price']);
    $category = 'Drink';

    // Handle image upload
    $image_name = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $image_path = 'images/' . basename($image_name);

    if (move_uploaded_file($image_tmp, $image_path)) {
        $insert = mysqli_query($conn, "INSERT INTO menu_items (name, description, price, category, image) 
                                       VALUES ('$name', '$description', '$price', '$category', '$image_name')");
        if ($insert) {
            $message = "✅ Drink added successfully!";
        } else {
            $message = "❌ Error adding drink: " . mysqli_error($conn);
        }
    } else {
        $message = "❌ Failed to upload image.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Drink - Maseno Cafeteria</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 50px;
            max-width: 600px;
        }
        .card {
            padding: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h3 class="text-center mb-4">➕ Add New Drink</h3>
    <div class="card">
        <?php if ($message): ?>
            <div class="alert alert-info"><?= $message ?></div>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Drink Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label>Price (KES)</label>
                <input type="number" step="0.01" name="price" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Image (JPG/PNG)</label>
                <input type="file" name="image" class="form-control-file" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-success">Add Drink</button>
            <a href="drinks.php" class="btn btn-secondary">Back to Drinks</a>
        </form>
    </div>
</div>

</body>
</html>
