<?php
include 'db_connect.php';

// Handle update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'])) {
    $id = $_POST['update_id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = floatval($_POST['price']);

    // Handle optional image upload
    if ($_FILES['image']['name']) {
        $image = $_FILES['image']['name'];
        $imageTmp = $_FILES['image']['tmp_name'];
        move_uploaded_file($imageTmp, "images/" . $image);
    } else {
        $imageRes = mysqli_query($conn, "SELECT image FROM menu_items WHERE id=$id");
        $imageData = mysqli_fetch_assoc($imageRes);
        $image = $imageData['image'];
    }

    // Update drink
    $sql = "UPDATE menu_items 
            SET name='$name', description='$description', price=$price, image='$image' 
            WHERE id=$id AND category='Drink'";
    mysqli_query($conn, $sql);
    header("Location: edit_drink.php?success=1");
    exit;
}

// Fetch all drinks
$items = mysqli_query($conn, "SELECT * FROM menu_items WHERE category='Drink'");

// If editing a specific drink
$editItem = null;
if (isset($_GET['edit_id'])) {
    $id = $_GET['edit_id'];
    $res = mysqli_query($conn, "SELECT * FROM menu_items WHERE id=$id AND category='Drink'");
    $editItem = mysqli_fetch_assoc($res);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Drink Items</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background-color: #f7f7f7; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; background: white; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        th { background-color: #007bff; color: white; }

        .form-container {
            background-color: white;
            padding: 20px;
            max-width: 500px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        input, textarea {
            width: 100%;
            padding: 8px;
            margin-top: 8px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        img {
            max-width: 100px;
        }

        .btn-edit {
            padding: 6px 12px;
            background-color: #28a745;
            color: white;
            border: none;
            text-decoration: none;
            border-radius: 4px;
        }

        .btn-delete {
            padding: 6px 12px;
            background-color: #dc3545;
            color: white;
            border: none;
            text-decoration: none;
            border-radius: 4px;
        }

        button {
            padding: 10px;
            background: green;
            color: white;
            border: none;
            border-radius: 4px;
        }

        .success {
            color: green;
            font-weight: bold;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<h2>All Drinks</h2>

<?php if (isset($_GET['success'])): ?>
    <p class="success">Drink updated successfully!</p>
<?php endif; ?>

<table>
    <tr>
        <th>ID</th>
        <th>Drink Name</th>
        <th>Description</th>
        <th>Price</th>
        <th>Image</th>
        <th>Action</th>
    </tr>
    <?php while ($item = mysqli_fetch_assoc($items)): ?>
        <tr>
            <td><?= $item['id'] ?></td>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td><?= htmlspecialchars($item['description']) ?></td>
            <td>KES <?= number_format($item['price'], 2) ?></td>
            <td><img src="images/<?= htmlspecialchars($item['image']) ?>" alt="Image"></td>
            <td>
                <a class="btn-edit" href="edit_drink.php?edit_id=<?= $item['id'] ?>">Edit</a>
                <a class="btn-delete" href="delete_drink.php?id=<?= $item['id'] ?>" onclick="return confirm('Are you sure you want to delete this drink?');">Delete</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<?php if ($editItem): ?>
    <div class="form-container">
        <h3>Edit Drink</h3>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="update_id" value="<?= $editItem['id'] ?>">

            <label>Drink Name:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($editItem['name']) ?>" required>

            <label>Description:</label>
            <textarea name="description" required><?= htmlspecialchars($editItem['description']) ?></textarea>

            <label>Price (KES):</label>
            <input type="number" name="price" step="0.01" value="<?= $editItem['price'] ?>" required>

            <label>Current Image:</label><br>
            <img src="images/<?= htmlspecialchars($editItem['image']) ?>" alt="Current Image"><br><br>

            <label>Upload New Image (optional):</label>
            <input type="file" name="image" accept="image/*">

            <button type="submit">Update</button>
        </form>
    </div>
<?php endif; ?>

</body>
</html>
