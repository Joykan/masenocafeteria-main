<?php
include 'db_connect.php';

// Fetch menu items
$query = mysqli_query($conn, "SELECT * FROM menu_items");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Menu Items - Maseno Cafeteria</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            min-height: 100vh;
            padding-top: 20px;
            position: fixed;
        }
        .sidebar h4 {
            color: #ecf0f1;
            text-align: center;
            margin-bottom: 30px;
        }
        .sidebar a {
            color: #ecf0f1;
            padding: 15px;
            display: block;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #34495e;
        }
        .main-content {
            margin-left: 260px;
            padding: 20px;
        }
        .dropdown-toggle::after {
            float: right;
            margin-top: 6px;
        }
        .list-unstyled {
            padding-left: 20px;
        }
        .table thead th {
            background-color: #2c3e50;
            color: #fff;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4>Maseno Cafeteria</h4>
    <a href="admin_dashboard.php">🏠 Home</a>

    <!-- Users Dropdown -->
    <a href="#usersSubmenu" data-toggle="collapse" class="dropdown-toggle">👥 Users</a>
    <ul class="collapse list-unstyled" id="usersSubmenu">
        <li><a href="users.php" class="pl-4">📋 All Users</a></li>
        <li><a href="add_user.php" class="pl-4">➕ Add User</a></li>
        <li><a href="edit_user.php" class="pl-4">✏️ Edit User</a></li>
        <li><a href="delete_user.php" class="pl-4">🗑️ Remove User</a></li>
    </ul>

    <!-- Menu Dropdown -->
    <a href="#menuSubmenu" data-toggle="collapse" class="dropdown-toggle">🍽️ Menu</a>
    <ul class="collapse show list-unstyled" id="menuSubmenu">
        <li><a href="menu.php" class="pl-4">📋 All Menu Items</a></li>
        <li><a href="add_food.php" class="pl-4">➕ Add Food</a></li>
        <li><a href="edit_food.php" class="pl-4">✏️ Edit Food</a></li>
        <li><a href="delete_food.php" class="pl-4">🗑️ Delete Food</a></li>
    </ul>

    <!-- Drinks Dropdown -->
    <a href="#drinksSubmenu" data-toggle="collapse" class="dropdown-toggle">🥤 Drinks</a>
    <ul class="collapse list-unstyled" id="drinksSubmenu">
        <li><a href="drinks.php" class="pl-4">📋 All Drinks</a></li>
        <li><a href="add_drink.php" class="pl-4">➕ Add Drink</a></li>
        <li><a href="edit_drink.php" class="pl-4">✏️ Edit Drink</a></li>
        <li><a href="delete_drink.php" class="pl-4">🗑️ Delete Drink</a></li>
    </ul>

    <!-- Snacks Dropdown -->
    <a href="#snacksSubmenu" data-toggle="collapse" class="dropdown-toggle">🍟 Snacks</a>
    <ul class="collapse list-unstyled" id="snacksSubmenu">
        <li><a href="snacks.php" class="pl-4">📋 All Snacks</a></li>
        <li><a href="add_snack.php" class="pl-4">➕ Add Snack</a></li>
        <li><a href="edit_snack.php" class="pl-4">✏️ Edit Snack</a></li>
        <li><a href="delete_snack.php" class="pl-4">🗑️ Delete Snack</a></li>
    </ul>

    <!-- Orders Dropdown -->
    <a href="#ordersSubmenu" data-toggle="collapse" class="dropdown-toggle">🧾 Orders</a>
    <ul class="collapse list-unstyled" id="ordersSubmenu">
        <li><a href="orders.php" class="pl-4">📋 All Orders</a></li>
        <li><a href="pending_orders.php" class="pl-4">⏳ Pending Orders</a></li>
        <li><a href="completed_orders.php" class="pl-4">✅ Completed Orders</a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="main-content">
    <h3>📋 All Menu Items</h3>

    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Item Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Image</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $count = 1;
            while($row = mysqli_fetch_assoc($query)) { ?>
                <tr>
                    <td><?= $count++ ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['category']) ?></td>
                    <td>KES <?= number_format($row['price']) ?></td>
                    <td>
                        <?php if (!empty($row['image'])): ?>
                            <img src="<?= htmlspecialchars($row['image']) ?>" width="50" height="50" alt="Food Image">
                        <?php else: ?>
                            No image
                        <?php endif; ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $('.sidebar .collapse').on('show.bs.collapse', function () {
        $('.sidebar .collapse').not(this).collapse('hide');
    });
</script>

</body>
</html>
