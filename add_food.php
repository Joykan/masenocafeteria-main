<?php
// insert_menu_item.php
include 'db_connect.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = floatval($_POST['price']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);

    // Handle image upload
    $image = $_FILES['image']['name'];
    $target = "images/" . basename($image);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $sql = "INSERT INTO menu_items (name, description, price, category, image)
                VALUES ('$name', '$description', $price, '$category', '$image')";

        if (mysqli_query($conn, $sql)) {
            $message = "Menu item added successfully!";
        } else {
            $message = "Error inserting item: " . mysqli_error($conn);
        }
    } else {
        $message = "Error uploading image.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Insert Menu Item</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 50px; }
        form { max-width: 500px; }
        input, textarea, select { width: 100%; padding: 10px; margin: 8px 0; }
        .message { color: green; font-weight: bold; }
    </style>
</head>
<body>
    <h2>Add New Menu Item</h2>
    <?php if ($message): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
    <form method="POST" action="" enctype="multipart/form-data">
        <label>Name:</label>
        <input type="text" name="name" required>

        <label>Description:</label>
        <textarea name="description" required></textarea>

        <label>Price:</label>
        <input type="number" step="0.01" name="price" required>

        <label>Category:</label>
        <select name="category" required>
            <option value="Breakfast">Breakfast</option>
            <option value="Lunch">Lunch</option>
            <option value="Snacks">Snacks</option>
            <option value="Drinks">Drinks</option>
        </select>

        <label>Food Image:</label>
        <input type="file" name="image" accept="image/*" required>

        <input type="submit" value="Add Item">
    </form>
</body>
</html>
