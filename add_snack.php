<?php
// add_snack.php
include 'db_connect.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = floatval($_POST['price']);
    $category = 'Snacks'; // fixed to match your category convention

    // Image upload
    $image = $_FILES['image']['name'];
    $target = "images/" . basename($image);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $sql = "INSERT INTO menu_items (name, description, price, category, image)
                VALUES ('$name', '$description', $price, '$category', '$image')";

        if (mysqli_query($conn, $sql)) {
            $message = "✅ Snack added successfully!";
        } else {
            $message = "❌ Error inserting snack: " . mysqli_error($conn);
        }
    } else {
        $message = "❌ Error uploading image.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Snack - Maseno Cafeteria</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .message { margin-top: 15px; font-weight: bold; }
    </style>
</head>
<body class="container mt-5">

    <h3 class="mb-4">➕ Add New Snack</h3>

    <?php if ($message): ?>
        <div class="alert alert-info message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Snack Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" required></textarea>
        </div>
        <div class="form-group">
            <label>Price (Ksh)</label>
            <input type="number" name="price" class="form-control" step="0.01" required>
        </div>
        <div class="form-group">
            <label>Upload Image</label>
            <input type="file" name="image" class="form-control-file" accept="image/*" required>
        </div>
        <button type="submit" class="btn btn-success">Add Snack</button>
        <a href="snacks.php" class="btn btn-secondary">Back</a>
    </form>

</body>
</html>
