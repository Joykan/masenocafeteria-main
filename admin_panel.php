<?php
session_start();
require 'db_connect.php';

// Check if user is logged in and has admin role
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['message'] = "Please log in as an admin to access the dashboard.";
    $_SESSION['message_type'] = "danger";
    header("Location: login.php");
    exit;
}

// Handle order status updates
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_id']) && isset($_POST['action'])) {
    $order_id = intval($_POST['order_id']);
    $action = $_POST['action'];

    // Validate action
    $new_status = '';
    if ($action === 'complete') {
        $new_status = 'completed';
    } elseif ($action === 'cancel') {
        $new_status = 'cancelled';
    }

    if ($new_status) {
        // Update order status
        $sql = "UPDATE orders SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $new_status, $order_id);
        if ($stmt->execute()) {
            $_SESSION['message'] = "Order #$order_id marked as $new_status.";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Failed to update order #$order_id.";
            $_SESSION['message_type'] = "danger";
        }
        $stmt->close();
    }
    // Redirect to refresh the page
    header("Location: admin_panel.php#orders");
    exit;
}

// Fetch orders
$orders_result = $conn->query("SELECT id, user_id, total, status, order_date FROM orders");
$orders = $orders_result->fetch_all(MYSQLI_ASSOC);
$total_orders = count($orders);

// Calculate total revenue from orders
$total_revenue_result = $conn->query("SELECT SUM(total) as total_revenue FROM orders");
$total_revenue = $total_revenue_result->fetch_assoc()['total_revenue'] ?? 0;

// Fetch users
$users_result = $conn->query("SELECT id, full_name, email, phone, role FROM users");
$users = $users_result->fetch_all(MYSQLI_ASSOC);
$total_users = count($users);

// Fetch menu items by category
$menu_result = $conn->query("SELECT id, name, price, category FROM menu_items WHERE category = 'Main Dish'");
$menu_items = $menu_result->fetch_all(MYSQLI_ASSOC);
$total_menu_items = count($menu_items);

$drinks_result = $conn->query("SELECT id, name, price, category FROM menu_items WHERE category = 'Drink'");
$drinks = $drinks_result->fetch_all(MYSQLI_ASSOC);
$total_drinks = count($drinks);

$snacks_result = $conn->query("SELECT id, name, price, category FROM menu_items WHERE category = 'Snack'");
$snacks = $snacks_result->fetch_all(MYSQLI_ASSOC);
$total_snacks = count($snacks);

// Total menu items (all categories)
$total_items_result = $conn->query("SELECT COUNT(*) as total_items FROM menu_items");
$total_items = $total_items_result->fetch_assoc()['total_items'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Maseno University Cafeteria</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Custom Header */
        .header {
            background: linear-gradient(90deg, #ff6f00, #ff8c00);
            color: white;
            padding: 15px 60px;
            width: 100%;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 1200px;
            margin: 0 auto;
        }

        .navbar h1 {
            font-size: 28px;
            font-weight: 700;
        }

        .navbar nav a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .navbar nav a:hover {
            color: #ffe082;
        }

        /* Layout */
        .dashboard-container {
            display: flex;
            flex: 1;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background: #2c3e50;
            color: white;
            height: calc(100vh - 70px);
            position: fixed;
            top: 70px;
            left: 0;
            padding: 20px;
            transition: transform 0.3s ease;
            z-index: 999;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .sidebar h2 {
            font-size: 24px;
            margin-bottom: 30px;
            color: #ff8c00;
        }

        .sidebar .nav-links {
            flex-grow: 1;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            color: white;
            text-decoration: none;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            transition: background 0.3s ease;
        }

        .sidebar a:hover, .sidebar a.active {
            background: #34495e;
        }

        .sidebar a i {
            margin-right: 10px;
        }

        .sidebar .logout-link {
            margin-top: auto;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            padding-top: 20px;
        }

        .sidebar a.red {
            color: #ff4d4f;
        }

        .sidebar a.red:hover {
            background: #34495e;
            color: #ff7875;
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            padding: 30px;
            width: calc(100% - 250px);
        }

        .content-section {
            display: none;
        }

        .content-section.active {
            display: block;
        }

        .content-section h3 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }

        .table {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .table thead {
            background: #ff8c00;
            color: white;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stats-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
        }

        .stats-card i {
            font-size: 36px;
            color: #ff8c00;
            margin-bottom: 10px;
        }

        .stats-card h4 {
            font-size: 28px;
            color: #333;
            margin-bottom: 5px;
        }

        .stats-card p {
            font-size: 16px;
            color: #555;
            margin: 0;
        }

        /* Action Buttons */
        .action-btn {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .action-btn.complete {
            background: #28a745;
            color: white;
        }

        .action-btn.complete:hover {
            background: #218838;
        }

        .action-btn.cancel {
            background: #dc3545;
            color: white;
            margin-left: 5px;
        }

        .action-btn.cancel:hover {
            background: #c82333;
        }

        .action-btn:disabled {
            background: #6c757d;
            cursor: not-allowed;
        }

        /* Toast Notification */
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 25px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            opacity: 0;
            pointer-events: none;
            transform: translateY(-20px);
            transition: all 0.4s ease;
            z-index: 9999;
            font-size: 16px;
            font-weight: 500;
        }

        .toast.show {
            opacity: 1;
            pointer-events: auto;
            transform: translateY(0);
        }

        .toast.success {
            background-color: #28a745;
            color: white;
        }

        .toast.danger {
            background-color: #ff4d4f;
            color: white;
        }

        /* Hamburger Menu */
        .hamburger {
            display: none;
            font-size: 24px;
            color: white;
            cursor: pointer;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 200px;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                width: 100%;
            }

            .hamburger {
                display: block;
            }

            .header {
                padding: 10px 20px;
            }

            .navbar h1 {
                font-size: 24px;
            }

            .table-responsive {
                font-size: 14px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .action-btn {
                font-size: 12px;
                padding: 4px 8px;
            }
        }

        @media (max-width: 480px) {
            .navbar h1 {
                font-size: 20px;
            }

            .content-section h3 {
                font-size: 20px;
            }

            .stats-card h4 {
                font-size: 24px;
            }

            .stats-card i {
                font-size: 30px;
            }
        }
    </style>
</head>
<body>
<header class="header">
        <div class="navbar">
            <div class="d-flex align-items-center">
                <i class="fas fa-bars hamburger me-3"></i>
                <h1>Maseno University Cafeteria</h1>
            </div>
            <nav>
                <a href="logout.php">Logout</a>
            </nav>
        </div>
    </header>

    <div id="toast" class="toast"></div>

    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div>
                <h2>Admin Dashboard</h2>
                <div class="nav-links">
                    <a href="#home" class="nav-link active"><i class="fas fa-home"></i>Home</a>
                    <a href="#users" class="nav-link"><i class="fas fa-users"></i>Users</a>
                    <a href="#menu" class="nav-link"><i class="fas fa-utensils"></i>Menu</a>
                    <a href="#drinks" class="nav-link"><i class="fas fa-cocktail"></i>Drinks</a>
                    <a href="#snacks" class="nav-link"><i class="fas fa-apple-alt"></i>Snacks</a>
                    <a href="#orders" class="nav-link"><i class="fas fa-shopping-cart"></i>Orders</a>
                </div>
            </div>
            <!-- <div class="logout-link">
                <a href="logout.php" class="nav-link red"><i class="fas fa-sign-out-alt"></i>Logout</a>
            </div> -->
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Home Section -->
            <div id="home" class="content-section active">
                <h3>Dashboard Overview</h3>
                <div class="stats-grid">
                    <!-- Total Users -->
                    <div class="stats-card">
                        <i class="fas fa-users"></i>
                        <h4><?php echo $total_users; ?></h4>
                        <p>Total Users</p>
                    </div>
                    <!-- Total Orders -->
                    <div class="stats-card">
                        <i class="fas fa-shopping-cart"></i>
                        <h4><?php echo $total_orders; ?></h4>
                        <p>Total Orders</p>
                    </div>
                    <!-- Total Menu Items -->
                    <div class="stats-card">
                        <i class="fas fa-utensils"></i>
                        <h4><?php echo $total_items; ?></h4>
                        <p>Total Menu Items</p>
                    </div>
                    <!-- Main Dishes -->
                    <div class="stats-card">
                        <i class="fas fa-hamburger"></i>
                        <h4><?php echo $total_menu_items; ?></h4>
                        <p>Main Dishes</p>
                    </div>
                    <!-- Drinks -->
                    <div class="stats-card">
                        <i class="fas fa-cocktail"></i>
                        <h4><?php echo $total_drinks; ?></h4>
                        <p>Drinks</p>
                    </div>
                    <!-- Snacks -->
                    <div class="stats-card">
                        <i class="fas fa-apple-alt"></i>
                        <h4><?php echo $total_snacks; ?></h4>
                        <p>Snacks</p>
                    </div>
                    <!-- Total Revenue -->
                    <div class="stats-card">
                        <i class="fas fa-money-bill-wave"></i>
                        <h4>KES <?php echo number_format($total_revenue, 2); ?></h4>
                        <p>Total Revenue</p>
                    </div>
                </div>
            </div>

            <!-- Users Section -->
            <div id="users" class="content-section">
                <h3>Users</h3>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                                    <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td><?php echo htmlspecialchars($user['phone'] ?: 'Not provided'); ?></td>
                                    <td><?php echo htmlspecialchars($user['role']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Menu Section -->
            <div id="menu" class="content-section">
                <h3>Menu</h3>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Category</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($menu_items as $item): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['id']); ?></td>
                                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                                    <td>KES <?php echo number_format($item['price'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($item['category']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Drinks Section -->
            <div id="drinks" class="content-section">
                <h3>Drinks</h3>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Category</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($drinks as $item): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['id']); ?></td>
                                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                                    <td>KES <?php echo number_format($item['price'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($item['category']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Snacks Section -->
            <div id="snacks" class="content-section">
                <h3>Snacks</h3>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Category</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($snacks as $item): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['id']); ?></td>
                                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                                    <td>KES <?php echo number_format($item['price'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($item['category']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Orders Section -->
            <div id="orders" class="content-section">
            <h3>Orders Received</h3>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User ID</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Order Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order['id']); ?></td>
                                <td><?php echo htmlspecialchars($order['user_id']); ?></td>
                                <td>KES <?php echo number_format($order['total'], 2); ?></td>
                                <td><?php echo htmlspecialchars($order['status']); ?></td>
                                <td><?php echo date('Y-m-d H:i:s', strtotime($order['order_date'])); ?></td>
                                <td>
                                    <?php if ($order['status'] === 'pending'): ?>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                            <input type="hidden" name="action" value="complete">
                                            <button type="submit" class="action-btn complete">Mark as Completed</button>
                                        </form>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                            <input type="hidden" name="action" value="cancel">
                                            <button type="submit" class="action-btn cancel">Mark as Cancelled</button>
                                        </form>
                                    <?php else: ?>
                                        <button class="action-btn" disabled><?php echo ucfirst($order['status']); ?></button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        // Sidebar toggle for mobile
        const hamburger = document.querySelector('.hamburger');
        const sidebar = document.querySelector('.sidebar');

        hamburger.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });

        // Section toggle
        const navLinks = document.querySelectorAll('.nav-link');
        const sections = document.querySelectorAll('.content-section');

        navLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const targetId = link.getAttribute('href').substring(1);

                // Remove active class from all links and sections
                navLinks.forEach(l => l.classList.remove('active'));
                sections.forEach(s => s.classList.remove('active'));

                // Add active class to clicked link and corresponding section
                link.classList.add('active');
                document.getElementById(targetId).classList.add('active');

                // Close sidebar on mobile after clicking a link
                if (window.innerWidth <= 768) {
                    sidebar.classList.remove('active');
                }
            });
        });

        // Display the toast if message is available
        <?php
        if (isset($_SESSION['message'])) {
            $message = $_SESSION['message'];
            $message_type = $_SESSION['message_type'];
            echo "showToast('$message', '$message_type');";
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
        }
        ?>

        function showToast(message, type) {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.className = `toast ${type} show`;
            setTimeout(() => {
                toast.classList.remove('show');
            }, 4000); // Hide after 4 seconds
        }
    </script>
</body>
</html>