<?php
session_start();
require 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    $_SESSION['message'] = "Please log in to proceed with checkout.";
    $_SESSION['message_type'] = "danger";
    header("Location: login.php");
    exit;
}

// Check if cart is empty
if (empty($_SESSION['cart'])) {
    $_SESSION['message'] = "Your cart is empty.";
    $_SESSION['message_type'] = "danger";
    header("Location: cart.php");
    exit;
}

// Fetch user details for pre-filling billing form
$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT full_name, email, phone FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Calculate total price
$total_price = 0;
foreach ($_SESSION['cart'] as $item) {
    $total_price += $item['price'] * $item['quantity'];
}

// Handle order placement
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    // Basic validation
    if (empty($full_name) || empty($email) || empty($phone)) {
        $_SESSION['message'] = "Please fill in all billing details.";
        $_SESSION['message_type'] = "danger";
        header("Location: checkout.php");
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = "Invalid email format.";
        $_SESSION['message_type'] = "danger";
        header("Location: checkout.php");
        exit;
    }

    try {
        // Fetch user_id
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user_data = $result->fetch_assoc();
        $user_id = $user_data['id'];
        $stmt->close();

        // Start transaction
        $conn->begin_transaction();

        // Insert order (no delivery address now)
        $stmt = $conn->prepare("INSERT INTO orders (user_id, total, status, order_date) VALUES (?, ?, 'pending', NOW())");
        $stmt->bind_param("id", $user_id, $total_price);
        $stmt->execute();
        $order_id = $conn->insert_id;
        $stmt->close();

        // Insert order items
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, menu_item_id, quantity, subtotal) VALUES (?, ?, ?, ?)");
        foreach ($_SESSION['cart'] as $item) {
            $subtotal = $item['price'] * $item['quantity'];
            $stmt->bind_param("iiid", $order_id, $item['id'], $item['quantity'], $subtotal);
            $stmt->execute();
        }
        $stmt->close();

        // Commit transaction
        $conn->commit();

        // Update user details (optional, if user updated them)
        $stmt = $conn->prepare("UPDATE users SET full_name = ?, email = ?, phone = ? WHERE username = ?");
        $stmt->bind_param("ssss", $full_name, $email, $phone, $username);
        $stmt->execute();
        $stmt->close();

        // Clear the cart
        $_SESSION['cart'] = [];

        // Redirect to PayHero with dynamic amount
        $payhero_url = "https://app.payhero.co.ke/lipwa/1724?" . http_build_query([
            'amount' => number_format($total_price, 2, '.', ''), // two decimal places
            'channel_id' => '2003',
            'currency' => 'KES',
            'failed_url' => 'https://spacyfiretech.onrender.com',
            'phone' => $phone, // user's entered phone number
            'reference' => 'SPACYFIRE',
            'success_url' => 'https://formjet.onrender.com'
        ]);

        header("Location: $payhero_url");
        exit;

    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['message'] = "Error placing order: " . $e->getMessage();
        $_SESSION['message_type'] = "danger";
        header("Location: checkout.php");
        exit;
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Maseno University Cafeteria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
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
        .btn-primary {
            background-color: #ff8c00;
            border-color: #ff8c00;
        }
        .btn-primary:hover {
            background-color: #e07b00;
            border-color: #e07b00;
        }
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 15px;
            }

            .navbar nav {
                gap: 15px;
            }

            .header {
                padding: 10px 20px;
            }

            .dashboard-container {
                padding: 10px;
            }

            .dashboard-section {
                padding: 20px;
            }

            .profile-overview {
                flex-direction: column;
                text-align: center;
            }

            .order-history table {
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .navbar h1 {
                font-size: 24px;
            }

            .dashboard-section h2 {
                font-size: 20px;
            }

            .profile-overview img {
                width: 80px;
                height: 80px;
            }

            .quick-order button, .account-settings button {
                font-size: 14px;
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

    <!-- Checkout Content -->
    <div class="container my-5">
        <h2 class="mb-4">Checkout</h2>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['message_type'] === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
        <?php endif; ?>

        <div class="row">
            <!-- Billing Details -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Billing Details</h4>
                        <form method="POST">
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
                                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?: ''); ?>" required>
                            </div>
                            <!-- Submit button moved to order details section -->
                        </form>
                    </div>
                </div>
            </div>

            <!-- Order Details -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Order Details</h4>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($_SESSION['cart'] as $item): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                                        <td><?php echo $item['quantity']; ?></td>
                                        <td>KES <?php echo number_format($item['price'], 2); ?></td>
                                        <td>KES <?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Total:</th>
                                    <th>KES <?php echo number_format($total_price, 2); ?></th>
                                </tr>
                            </tfoot>
                        </table>
                        <form method="POST">
                            <input type="hidden" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>">
                            <input type="hidden" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
                            <input type="hidden" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?: ''); ?>">
                            <input type="hidden" name="delivery_address" id="hidden_delivery_address">
                            <button type="submit" class="btn btn-primary btn-lg w-100" onclick="document.getElementById('hidden_delivery_address').value = document.getElementById('delivery_address').value;">Place Order</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>