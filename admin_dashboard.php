<?php
// admin_dashboard.php - Admin landing page with dropdown sidebar and summary cards
include 'db_connect.php';

// Count total users
$userCountQuery = mysqli_query($conn, "SELECT COUNT(*) AS total_users FROM users");
$userCount = mysqli_fetch_assoc($userCountQuery)['total_users'];

// Count total menu items
$menuCountQuery = mysqli_query($conn, "SELECT COUNT(*) AS total_menu FROM menu_items");
$menuCount = mysqli_fetch_assoc($menuCountQuery)['total_menu'];

// Count total orders
$orderCountQuery = mysqli_query($conn, "SELECT COUNT(*) AS total_orders FROM orders");
$orderCount = mysqli_fetch_assoc($orderCountQuery)['total_orders'];

// Count users who have made orders
$orderUsersQuery = mysqli_query($conn, "SELECT COUNT(DISTINCT user_id) AS order_users FROM orders");
$orderUsers = mysqli_fetch_assoc($orderUsersQuery)['order_users'];

// Get order trends data (example: orders per day)
$orderTrendsQuery = mysqli_query($conn, "SELECT DATE(order_date) AS order_day, COUNT(*) AS orders FROM orders GROUP BY order_day ORDER BY order_day");
$orderTrendsData = [];
while ($row = mysqli_fetch_assoc($orderTrendsQuery)) {
    $orderTrendsData[] = $row;
}

// Get menu categories breakdown (example: food categories)
$menuCategoriesQuery = mysqli_query($conn, "SELECT category, COUNT(*) AS count FROM menu_items GROUP BY category");
$menuCategoriesData = [];
while ($row = mysqli_fetch_assoc($menuCategoriesQuery)) {
    $menuCategoriesData[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Maseno Cafeteria</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        .dropdown-toggle::after {
            float: right;
            margin-top: 6px;
        }
        .list-unstyled {
            padding-left: 20px;
        }
        .card {
            margin-bottom: 20px;
        }
        .card-title {
            font-size: 1.2rem;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4>Maseno Cafeteria</h4>
    <a href="admin_dashboard.php">🏠 Home</a></class>
    <!-- Sidebar -->


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

    <!-- Drinks Dropdown -->
    <a href="#drinksSubmenu" data-toggle="collapse" class="dropdown-toggle" aria-expanded="false">🥤 Drinks</a>
    <ul class="collapse list-unstyled" id="drinksSubmenu">
        <li><a href="drinks.php" class="pl-4">📋 All Drinks</a></li>
        <li><a href="add_drink.php" class="pl-4">➕ Add Drink</a></li>
        <li><a href="edit_drink.php" class="pl-4">✏️ Edit Drink</a></li>
        <li><a href="delete_drink.php" class="pl-4">🗑️ Delete Drink</a></li>
    </ul>

    <!-- Snacks Dropdown -->
    <a href="#snacksSubmenu" data-toggle="collapse" class="dropdown-toggle" aria-expanded="false">🍟 Snacks</a>
    <ul class="collapse list-unstyled" id="snacksSubmenu">
        <li><a href="snacks.php" class="pl-4">📋 All Snacks</a></li>
        <li><a href="add_snack.php" class="pl-4">➕ Add Snack</a></li>
        <li><a href="edit_snack.php" class="pl-4">✏️ Edit Snack</a></li>
        <li><a href="delete_snack.php" class="pl-4">🗑️ Delete Snack</a></li>
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
    <h3 class="mb-4">👋 Welcome to the Admin Dashboard</h3>

    <div class="row">
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">👥 Total Users</h5>
                    <p class="card-text display-4"><?php echo $userCount; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">🍽️ Menu Items</h5>
                    <p class="card-text display-4"><?php echo $menuCount; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <h5 class="card-title">🧾 Orders</h5>
                    <p class="card-text display-4"><?php echo $orderCount; ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row mt-5">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-center">📊 Users vs Orders</h5>
                    <canvas id="usersOrdersChart" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-center">📊 Food Items vs Users</h5>
                    <canvas id="foodUsersChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Line Graph for Order Trends -->
    <div class="row mt-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-center">📈 Order Trends Over Time</h5>
                    <canvas id="orderTrendsChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Pie Chart for Menu Categories -->
    <div class="row mt-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-center">🥧 Menu Categories Breakdown</h5>
                    <canvas id="menuCategoriesChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const ctx1 = document.getElementById('usersOrdersChart').getContext('2d');
    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: ['Total Users', 'Users Who Ordered'],
            datasets: [{
                label: 'Count',
                data: [<?= $userCount ?>, <?= $orderUsers ?>],
                backgroundColor: ['#3498db', '#2ecc71']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Users vs Orders'
                }
            }
        }
    });

    const ctx2 = document.getElementById('foodUsersChart').getContext('2d');
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: ['Menu Items', 'Users'],
            datasets: [{
                label: 'Count',
                data: [<?= $menuCount ?>, <?= $userCount ?>],
                backgroundColor: ['#f1c40f', '#9b59b6']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Food Items vs Users'
                }
            }
        }
    });

    // Line Chart for Order Trends
    const ctx3 = document.getElementById('orderTrendsChart').getContext('2d');
    new Chart(ctx3, {
        type: 'line',
        data: {
            labels: <?= json_encode(array_column($orderTrendsData, 'order_day')) ?>,
            datasets: [{
                label: 'Orders',
                data: <?= json_encode(array_column($orderTrendsData, 'orders')) ?>,
                borderColor: '#e74c3c',
                fill: false
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Order Trends Over Time'
                }
            }
        }
    });

    // Pie Chart for Menu Categories
    const ctx4 = document.getElementById('menuCategoriesChart').getContext('2d');
    new Chart(ctx4, {
        type: 'pie',
        data: {
            labels: <?= json_encode(array_column($menuCategoriesData, 'category')) ?>,
            datasets: [{
                label: 'Menu Categories',
                data: <?= json_encode(array_column($menuCategoriesData, 'count')) ?>,
                backgroundColor: ['#3498db', '#e67e22', '#2ecc71', '#9b59b6']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Menu Categories Breakdown'
                }
            }
        }
    });
</script>

</body>
</html>
