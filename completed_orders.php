<?php
include 'db_connect.php';

// Fetch completed orders
$query = mysqli_query($conn, "SELECT * FROM orders WHERE status = 'completed'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Completed Orders - Maseno Cafeteria</title>
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
        table {
            background-color: #fff;
        }
    </style>
</head>
<body>
<!-- Sidebar -->
<div class="sidebar">
    <h4>Maseno Cafeteria</h4>
    <a href="admin_dashboard.php">🏠 Home</a>

    <!-- Users Dropdown -->
    <a href="#usersSubmenu" data-toggle="collapse" class="dropdown-toggle" aria-expanded="false">👥 Users</a>
    <ul class="collapse list-unstyled" id="usersSubmenu">
        <li><a href="users.php" class="pl-4">📋 All Users</a></li>
        <li><a href="add_user.php" class="pl-4">➕ Add User</a></li>
        <li><a href="edit_user.php" class="pl-4">✏️ Edit User</a></li>
        <li><a href="delete_user.php" class="pl-4">🗑️ Remove User</a></li>
    </ul>

    <!-- Menu Dropdown -->
    <a href="#menuSubmenu" data-toggle="collapse" class="dropdown-toggle" aria-expanded="false">🍽️ Menu</a>
    <ul class="collapse list-unstyled" id="menuSubmenu">
        <li><a href="menu.php" class="pl-4">📋 All Menu Items</a></li>
        <li><a href="add_food.php" class="pl-4">➕ Add Food</a></li>
        <li><a href="edit_food.php" class="pl-4">✏️ Edit Food</a></li>
        <li><a href="delete_food.php" class="pl-4">🗑️ Delete Food</a></li>
    </ul>

    <!-- Orders Dropdown -->
    <a href="#ordersSubmenu" data-toggle="collapse" class="dropdown-toggle" aria-expanded="false">🧾 Orders</a>
    <ul class="collapse list-unstyled" id="ordersSubmenu">
        <li><a href="orders.php" class="pl-4">📋 All Orders</a></li>
        <li><a href="pending_orders.php" class="pl-4">⏳ Pending Orders</a></li>
        <li><a href="completed_orders.php" class="pl-4">✅ Completed Orders</a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="main-content">
    <h3>Completed Orders</h3>
    <table class="table table-bordered table-striped mt-3">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>User ID</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Order Date</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $count = 1;
            while($row = mysqli_fetch_assoc($query)) { ?>
                <tr>
                    <td><?= $count++ ?></td>
                    <td><?= htmlspecialchars($row['user_id']) ?></td>
                    <td>KES <?= number_format($row['total'], 2) ?></td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                    <td><?= htmlspecialchars($row['order_date']) ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
