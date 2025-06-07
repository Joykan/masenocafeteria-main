<?php
include 'db_connect.php';

// Fetch all pending orders and join with users
$query = mysqli_query($conn, "
    SELECT o.*, u.username 
    FROM orders o 
    JOIN users u ON o.user_id = u.id
    WHERE o.status = 'Pending'
    ORDER BY o.order_date DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pending Orders - Maseno Cafeteria</title>
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
    <a href="users.php">👥 Users</a>
    <a href="menu.php">🍽️ Menu</a>
    <a href="drinks.php">🥤 Drinks</a>
    <a href="snacks.php">🍟 Snacks</a>
    <a href="orders.php">🧾 Orders</a>
    <a href="pending_orders.php">⏳ Pending Orders</a>
    <a href="completed_orders.php">✅ Completed Orders</a>
</div>

<!-- Main Content -->
<div class="main-content">
    <h3>⏳ Pending Orders</h3>

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
                    <a href="update_order_status.php?id=<?= $row['id'] ?>&status=Completed" class="btn btn-success btn-sm">✔ Complete</a>
                    <a href="update_order_status.php?id=<?= $row['id'] ?>&status=Cancelled" class="btn btn-danger btn-sm">✖ Cancel</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
