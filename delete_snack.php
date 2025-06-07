<?php
include 'db_connect.php';

// Handle deletion
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);

    // Get image file before deleting from DB
    $getImage = mysqli_query($conn, "SELECT image FROM menu_items WHERE id = $id");
    $row = mysqli_fetch_assoc($getImage);
    $image = $row['image'];

    // Delete image file from uploads folder if it exists
    if (!empty($image) && file_exists("images/$image")) {
        unlink("images/$image");
    }

    // Delete from DB
    $deleteQuery = "DELETE FROM menu_items WHERE id = $id";
    if (mysqli_query($conn, $deleteQuery)) {
        $message = "Snack deleted successfully!";
    } else {
        $message = "Error deleting snack.";
    }
}

// Fetch only "snack" category items
$result = mysqli_query($conn, "SELECT * FROM menu_items WHERE category = 'snack'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Snack</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
        }

        h2 {
            text-align: center;
        }

        .message {
            text-align: center;
            font-weight: bold;
            color: green;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        table img {
            max-width: 80px;
            max-height: 60px;
        }

        .btn-delete {
            background-color: red;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-delete:hover {
            background-color: darkred;
        }

        .btn-back {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 20px;
            float: right; /* Align the button to the right */
        }

        .btn-back:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

    <h2>Delete Snack</h2>

    <?php if (!empty($message)) : ?>
        <p class="message"><?= $message ?></p>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Snack Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($item = mysqli_fetch_assoc($result)) : ?>
            <tr>
                <td><?= $item['id'] ?></td>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td><?= htmlspecialchars($item['description']) ?></td>
                <td>Ksh <?= number_format($item['price'], 2) ?></td>
                <td>
                    <?php if ($item['image']) : ?>
                        <img src="images/<?= $item['image'] ?>" alt="Image">
                    <?php else : ?>
                        No image
                    <?php endif; ?>
                </td>
                <td>
                    <a href="delete_snack.php?delete_id=<?= $item['id'] ?>" 
                       class="btn-delete" 
                       onclick="return confirm('Are you sure you want to delete this snack?');">
                       Delete
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Back Button -->
    <button class="btn-back" onclick="window.history.back();">Go Back</button>

</body>
</html>
