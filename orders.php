<?php
include 'db_connect.php';

// Fetch all orders and join with users
$query = mysqli_query($conn, "
    SELECT o.*, u.username 
    FROM orders o 
    JOIN users u ON o.user_id = u.id
    ORDER BY o.order_date DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Orders - Maseno Cafeteria</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
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

    <!-- Users -->
    <a href="#usersSubmenu" data-toggle="collapse" class="dropdown-toggle" aria-expanded="false">👥 Users</a>
    <ul class="collapse list-unstyled" id="usersSubmenu">
        <li><a href="users.php" class="pl-4">📋 All Users</a></li>
        <li><a href="add_user.php" class="pl-4">➕ Add User</a></li>
        <li><a href="edit_user.php" class="pl-4">✏️ Edit User</a></li>
        <li><a href="delete_user.php" class="pl-4">🗑️ Remove User</a></li>
    </ul>

    <!-- Menu -->
    <a href="#menuSubmenu" data-toggle="collapse" class="dropdown-toggle" aria-expanded="false">🍽️ Menu</a>
    <ul class="collapse list-unstyled" id="menuSubmenu">
        <li><a href="menu.php" class="pl-4">📋 All Menu Items</a></li>
        <li><a href="add_food.php" class="pl-4">➕ Add Food</a></li>
        <li><a href="edit_food.php" class="pl-4">✏️ Edit Food</a></li>
        <li><a href="delete_food.php" class="pl-4">🗑️ Delete Food</a></li>
    </ul>

    <!-- Drinks -->
    <a href="#drinksSubmenu" data-toggle="collapse" class="dropdown-toggle" aria-expanded="false">🥤 Drinks</a>
    <ul class="collapse list-unstyled" id="drinksSubmenu">
        <li><a href="drinks.php" class="pl-4">📋 All Drinks</a></li>
        <li><a href="add_drink.php" class="pl-4">➕ Add Drink</a></li>
        <li><a href="edit_drink.php" class="pl-4">✏️ Edit Drink</a></li>
        <li><a href="delete_drink.php" class="pl-4">🗑️ Delete Drink</a></li>
    </ul>

    <!-- Snacks -->
    <a href="#snacksSubmenu" data-toggle="collapse" class="dropdown-toggle" aria-expanded="false">🍟 Snacks</a>
    <ul class="collapse list-unstyled" id="snacksSubmenu">
        <li><a href="snacks.php" class="pl-4">📋 All Snacks</a></li>
        <li><a href="add_snack.php" class="pl-4">➕ Add Snack</a></li>
        <li><a href="edit_snack.php" class="pl-4">✏️ Edit Snack</a></li>
        <li><a href="delete_snack.php" class="pl-4">🗑️ Delete Snack</a></li>
    </ul>

    <!-- Orders -->
    <a href="#ordersSubmenu" data-toggle="collapse" class="dropdown-toggle" aria-expanded="true">🧾 Orders</a>
    <ul class="collapse list-unstyled show" id="ordersSubmenu">
        <li><a href="orders.php" class="pl-4 font-weight-bold">📋 All Orders</a></li>
        <li><a href="pending_orders.php" class="pl-4">⏳ Pending Orders</a></li>
        <li><a href="completed_orders.php" class="pl-4">✅ Completed Orders</a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="main-content">
    <h3>🧾 All Orders</h3>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['msg']) ?></div>
    <?php endif; ?>

    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>User</th>
                <th>Total Price (KES)</th>
                <th>Status</th>
                <th>Order Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        $count = 1;
        while ($row = mysqli_fetch_assoc($query)): ?>
            <tr>
                <td><?= $count++ ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= number_format($row['total_price'], 2) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td><?= htmlspecialchars($row['order_date']) ?></td>
                <td>
                    <?php if ($row['status'] === 'Pending'): ?>
                        <a href="update_order_status.php?id=<?= $row['id'] ?>&status=Completed" class="btn btn-success btn-sm">✔ Complete</a>
                        <a href="update_order_status.php?id=<?= $row['id'] ?>&status=Cancelled" class="btn btn-danger btn-sm">✖ Cancel</a>
                    <?php else: ?>
                        <span class="text-muted">No Action</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
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
