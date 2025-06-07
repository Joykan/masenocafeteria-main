<?php
// all_snacks.php
include 'db_connect.php';

$sql = "SELECT * FROM menu_items WHERE category = 'Snacks'";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Snacks - Maseno University Cafeteria</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        .header {
            background-color: #ff6600;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .menu {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 30px;
        }
        .menu-item {
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 260px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .menu-item img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }
        .menu-item h3 {
            margin: 10px 0 5px;
            font-size: 18px;
        }
        .menu-item p {
            margin: 5px 10px;
            color: #444;
        }
        .menu-item strong {
            color: #28a745;
        }
        .menu-item button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px;
            margin: 15px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Snack Menu</h1>
        <p>Enjoy our selection of affordable and tasty snacks!</p>
    </div>

    <section class="menu">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="menu-item">
                    <img src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                    <h3><?= htmlspecialchars($row['name']) ?></h3>
                    <p><?= htmlspecialchars($row['description']) ?></p>
                    <p><strong>Ksh <?= number_format($row['price'], 2) ?></strong></p>
                    <button>Order Now</button>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No snack items available at the moment.</p>
        <?php endif; ?>
    </section>
</body>
</html>
