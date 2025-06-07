<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .profile-section, .sidebar {
            background-color: #fff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        .dashboard-content {
            padding: 20px;
        }
        .carousel img {
            border-radius: 10px;
        }
    </style>
</head>
<body>
<div class="container-fluid mt-4">
    <div class="row">

        <!-- LEFT: Profile Section -->
        <div class="col-md-3">
            <div class="profile-section">
                <h4><i class="fas fa-user-circle me-2"></i>Profile</h4>
                <p><strong>Name:</strong> <?= htmlspecialchars($user['full_name']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone']) ?></p>
                <a href="edit_profile.php" class="btn btn-sm btn-primary"><i class="fas fa-edit me-1"></i>Edit Profile</a>
            </div>
        </div>

        <!-- CENTER: Dashboard -->
        <div class="col-md-6">
            <div class="dashboard-content">

                <!-- Alert Messages -->
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-<?= $_SESSION['message_type'] ?? 'info' ?> alert-dismissible fade show" role="alert">
                        <?= $_SESSION['message'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
                <?php endif; ?>

                <!-- Advertisement Carousel -->
                <div id="adsCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="images/ad1.jpg" class="d-block w-100" alt="Ad 1">
                        </div>
                        <div class="carousel-item">
                            <img src="images/ad2.jpg" class="d-block w-100" alt="Ad 2">
                        </div>
                        <div class="carousel-item">
                            <img src="images/ad3.jpg" class="d-block w-100" alt="Ad 3">
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#adsCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#adsCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>

                <!-- Order History Table -->
                <h5 class="mb-3"><i class="fas fa-clock me-1"></i>Order History</h5>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered table-sm table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Date</th>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($orders)): ?>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($order['order_date']) ?></td>
                                        <td><?= htmlspecialchars($order['name']) ?></td>
                                        <td><?= htmlspecialchars($order['quantity']) ?></td>
                                        <td>KES <?= number_format($order['subtotal'], 2) ?></td>
                                        <td><span class="badge bg-secondary"><?= htmlspecialchars($order['status']) ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="5" class="text-center">No orders found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Quick Order Form -->
                <h5 class="mb-3"><i class="fas fa-bolt me-1"></i>Quick Order</h5>
                <form method="post" action="place_order.php">
                    <div class="row g-2">
                        <div class="col-md-8">
                            <select name="menu_item_id" class="form-select" required>
                                <option value="" disabled selected>Select Item</option>
                                <?php foreach ($menu_items as $item): ?>
                                    <option value="<?= $item['id'] ?>">
                                        <?= htmlspecialchars($item['name']) ?> - KES <?= number_format($item['price'], 2) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="quantity" class="form-control" placeholder="Qty" min="1" required>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-success w-100"><i class="fas fa-shopping-cart"></i></button>
                        </div>
                    </div>
                </form>

            </div>
        </div>

        <!-- RIGHT: Sidebar -->
        <div class="col-md-3">
            <div class="sidebar">
                <h5><i class="fas fa-bars me-2"></i>Menu</h5>
                <ul class="list-group">
                    <li class="list-group-item"><a href="menus.php"><i class="fas fa-utensils me-2"></i>View Menus</a></li>
                    <li class="list-group-item"><a href="cart.php"><i class="fas fa-shopping-cart me-2"></i>My Cart</a></li>
                    <li class="list-group-item"><a href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                </ul>
            </div>
        </div>

    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
