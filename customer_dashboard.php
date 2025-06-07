<?php
session_start();
require 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    $_SESSION['message'] = "Please log in to access your dashboard.";
    $_SESSION['message_type'] = "danger";
    header("Location: login.php");
    exit;
}

// Fetch user details
$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT id, full_name, email, phone FROM users WHERE username = ? AND role = 'customer'");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Fetch order history
$stmt = $conn->prepare(
    "SELECT o.id, o.order_date, o.status, oi.quantity, oi.subtotal, m.name
     FROM orders o
     JOIN order_items oi ON o.id = oi.order_id
     JOIN menu_items m ON oi.menu_item_id = m.id
     WHERE o.user_id = ?"
);
$stmt->bind_param("i", $user['id']);
$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Fetch menu items for quick order
$result = $conn->query("SELECT id, name, price FROM menu_items");
$menu_items = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Maseno University Cafeteria</title>
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

        .navbar nav {
            display: flex;
            gap: 25px;
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
            min-height: calc(100vh - 70px);
            margin-top: 20px;
        }

        /* Left Sidebar (Profile + Edit) */
        .sidebar-left {
            width: 25%;
            padding: 20px;
        }

        .profile-card, .edit-profile-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            transition: transform 0.3s ease;
            margin-bottom: 20px;
        }

        .profile-card:hover, .edit-profile-card:hover {
            transform: translateY(-5px);
        }

        .profile-img {
            width: 120px;
            height: 120px;
            border: 4px solid #ff8c00;
            margin-bottom: 15px;
        }

        .profile-card h3 {
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
        }

        .profile-card p {
            font-size: 16px;
            color: #555;
            margin-bottom: 8px;
        }

        .profile-nav {
            margin-top: 20px;
        }

        .profile-nav a {
            display: block;
            color: #ff8c00;
            text-decoration: none;
            font-size: 16px;
            padding: 10px 0;
            transition: color 0.3s ease;
        }

        .profile-nav a:hover {
            color: #e07b00;
        }

        .edit-profile-card h4 {
            font-size: 20px;
            color: #ff8c00;
            margin-bottom: 15px;
        }

        /* Center Content */
        .main-content {
            width: 50%;
            padding: 20px;
        }

        .main-content .card {
            border-radius: 15px;
            margin-bottom: 30px;
            transition: transform 0.3s ease;
        }

        .main-content .card:hover {
            transform: translateY(-5px);
        }

        .main-content .card-header {
            background: #ff8c00;
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 15px;
        }

        .main-content .card-body {
            padding: 20px;
        }

        .carousel-img {
            height: 300px;
            object-fit: cover;
            border-radius: 10px;
        }

        /* Right Sidebar (Fixed) */
        .sidebar-right {
            width: 25%;
            position: fixed;
            right: 0;
            top: 70px;
            height: calc(100vh - 70px);
            background: white;
            box-shadow: -2px 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            overflow-y: auto;
        }

        .sidebar-right h4 {
            font-size: 18px;
            color: #ff8c00;
            margin-bottom: 15px;
        }

        .sidebar-right a {
            display: block;
            color: #333;
            text-decoration: none;
            font-size: 16px;
            margin-bottom: 10px;
            transition: color 0.3s ease;
        }

        .sidebar-right a:hover {
            color: #ff8c00;
        }

        .ad-banner {
            margin-bottom: 20px;
        }

        .ad-banner img {
            width: 100%;
            border-radius: 10px;
        }

        /* Bootstrap Customizations */
        .btn-primary {
            background-color: #ff8c00;
            border-color: #ff8c00;
        }

        .btn-primary:hover {
            background-color: #e07b00;
            border-color: #e07b00;
        }

        .table thead {
            background-color: #ff8c00;
            color: white;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .dashboard-container {
                flex-direction: column;
            }

            .sidebar-left, .main-content, .sidebar-right {
                width: 100%;
                position: static;
                height: auto;
            }

            .sidebar-right {
                padding: 20px;
                margin-top: 20px;
            }
        }

        @media (max-width: 768px) {
            .header {
                padding: 10px 20px;
            }

            .navbar {
                flex-direction: column;
                gap: 15px;
            }

            .navbar nav {
                gap: 15px;
            }

            .profile-img {
                width: 100px;
                height: 100px;
            }

            .carousel-img {
                height: 200px;
            }
        }

        @media (max-width: 480px) {
            .navbar h1 {
                font-size: 24px;
            }

            .profile-card h3, .edit-profile-card h4 {
                font-size: 20px;
            }

            .main-content .card-header h3 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="navbar">
            <h1>Maseno University Cafeteria</h1>
            <nav>
                <a href="customer_dashboard.php">Dashboard</a>
                <a href="shop.php">Menu</a>
                <a href="cart.php">Cart</a>
                <a href="logout.php">Logout</a>
            </nav>
        </div>
    </header>

    <div class="dashboard-container">
        <!-- Left Sidebar (Profile + Edit) -->
        <div class="sidebar-left">
            <div class="profile-card">
                <img src="images/avatar.jpg" alt="User Profile" class="rounded-circle profile-img">
                <h3><?php echo htmlspecialchars($user['full_name']); ?></h3>
                <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
                <p>Phone: <?php echo htmlspecialchars($user['phone'] ?: 'Not provided'); ?></p>
                <div class="profile-nav">
                    <a href="customer_dashboard.php"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a>
                    <a href="shop.php"><i class="fas fa-utensils me-2"></i>Menu</a>
                    <a href="cart.php"><i class="fas fa-shopping-cart me-2"></i>Cart</a>
                    <a href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
                </div>
            </div>

            <!-- Profile Edit Card -->
            <div class="edit-profile-card">
                <h4>Edit Profile</h4>
                <form action="update_profile.php" method="POST">
                    <div class="mb-3">
                        <label for="full_name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?: ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">New Password (leave blank to keep current)</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="New Password">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Update Profile</button>
                </form>
            </div>
        </div>

        <!-- Center Content -->
        <div class="main-content">
            <!-- Messages -->
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-<?php echo $_SESSION['message_type'] === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($_SESSION['message']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
            <?php endif; ?>

            <!-- Advertisement Carousel -->
            <div class="card mb-4">
                <div class="card-body p-0">
                    <div id="adCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="images/fish.jpg" class="d-block w-100 carousel-img" alt="Ad 1">
                            </div>
                            <div class="carousel-item">
                                <img src="images/samosa.jpg" class="d-block w-100 carousel-img" alt="Ad 2">
                            </div>
                            <div class="carousel-item">
                                <img src="images/kiwifruitjuice.jpg" class="d-block w-100 carousel-img" alt="Ad 3">
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#adCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#adCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Order History -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="mb-0">Order History</h3>
                </div>
                <div class="card-body">
                    <?php if (empty($orders)): ?>
                        <p>No orders yet.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Item</th>
                                        <th>Quantity</th>
                                        <th>Subtotal</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $order): ?>
                                        <tr>
                                            <td>#<?php echo htmlspecialchars($order['id']); ?></td>
                                            <td><?php echo htmlspecialchars($order['name']); ?></td>
                                            <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                                            <td>KES <?php echo number_format($order['subtotal'], 2); ?></td>
                                            <td><?php echo date('Y-m-d', strtotime($order['order_date'])); ?></td>
                                            <td><?php echo htmlspecialchars($order['status']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Quick Order -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="mb-0">Quick Order</h3>
                </div>
                <div class="card-body">
                    <form action="order_process.php" method="POST">
                        <div class="mb-3">
                            <label for="menu_item_id" class="form-label">Select Item</label>
                            <select class="form-control" id="menu_item_id" name="menu_item_id" required>
                                <option value="" disabled selected>Select Item</option>
                                <?php foreach ($menu_items as $item): ?>
                                    <option value="<?php echo $item['id']; ?>">
                                        <?php echo htmlspecialchars($item['name']); ?> - KES <?php echo number_format($item['price'], 2); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="delivery_address" class="form-label">Delivery Address</label>
                            <input type="text" class="form-control" id="delivery_address" name="delivery_address" placeholder="Delivery Address" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Place Order</button>
                        
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Sidebar (Links/Adverts) -->
        <div class="sidebar-right">
            <h4>Quick Links</h4>
            <a href="about.php"><i class="fas fa-info-circle me-2"></i>About Us</a>
            <a href="contact.php"><i class="fas fa-envelope me-2"></i>Contact Us</a>
            <a href="shop.php"><i class="fas fa-utensils me-2"></i>Our Menu</a>
            <a href="reviews.php"><i class="fas fa-star me-2"></i>Reviews</a>

            <h4>Follow Us</h4>
            <a href="#"><i class="fab fa-facebook me-2"></i>Facebook</a>
            <a href="#"><i class="fab fa-instagram me-2"></i>Instagram</a>
            <a href="#"><i class="fab fa-twitter me-2"></i>Twitter</a>

            <h4>Promotions</h4>
            <div class="ad-banner">
                <img src="images/kharbujajuice.jpg" alt="Ad Banner 1">
            </div>
            <div class="ad-banner">
                <img src="images/pancakes.jpg" alt="Ad Banner 2">
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>