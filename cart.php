<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle updating quantity
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'update' && isset($_POST['menu_item_id'], $_POST['quantity'])) {
        $menu_item_id = $_POST['menu_item_id'];
        $quantity = (int)$_POST['quantity'];

        if ($quantity > 0) {
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['id'] == $menu_item_id) {
                    $item['quantity'] = $quantity;
                    break;
                }
            }
            unset($item); // Break reference
        }
    }

    // Handle removing item
    if (isset($_POST['action']) && $_POST['action'] === 'remove' && isset($_POST['menu_item_id'])) {
        $menu_item_id = $_POST['menu_item_id'];

        foreach ($_SESSION['cart'] as $index => $item) {
            if ($item['id'] == $menu_item_id) {
                unset($_SESSION['cart'][$index]);
                break;
            }
        }

        // Re-index the array
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}

// Calculate total price
$total_price = 0;
foreach ($_SESSION['cart'] as $item) {
    $total_price += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart - Maseno University Cafeteria</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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

<div class="container my-5">
    <h2 class="mb-4">My Cart</h2>

    <?php if (empty($_SESSION['cart'])): ?>
        <div class="alert alert-info">Your cart is empty.</div>
        <a href="shop.php" class="btn btn-primary">Go to Menu</a>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-warning">
                    <tr>
                        <th>Item</th>
                        <th>Price (KES)</th>
                        <th>Quantity</th>
                        <th>Subtotal (KES)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['cart'] as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td><?php echo number_format($item['price'], 2); ?></td>
                            <td>
                                <form method="POST" class="d-flex">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="menu_item_id" value="<?php echo $item['id']; ?>">
                                    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" class="form-control me-2" style="width: 80px;">
                                    <button type="submit" class="btn btn-sm btn-success">Update</button>
                                </form>
                            </td>
                            <td><?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="action" value="remove">
                                    <input type="hidden" name="menu_item_id" value="<?php echo $item['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="table-light">
                        <td colspan="3" class="text-end fw-bold">Total:</td>
                        <td colspan="2" class="fw-bold">KES <?php echo number_format($total_price, 2); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="d-flex justify-content-end mt-4">
            <a href="checkout.php" class="btn btn-primary btn-lg">Proceed to Checkout</a>
        </div>
    <?php endif; ?>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
