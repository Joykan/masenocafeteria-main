<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

require 'db_connect.php';

// Fetch categories
$result = $conn->query("SELECT DISTINCT category FROM menu_items");
$categories = $result->fetch_all(MYSQLI_ASSOC);

// Fetch menu items (filter by category if selected)
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
$query = "SELECT id, name, description, price, image, category FROM menu_items";
if ($category_filter) {
    $stmt = $conn->prepare($query . " WHERE category = ?");
    $stmt->bind_param("s", $category_filter);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($query);
}
$menu_items = $result->fetch_all(MYSQLI_ASSOC);
if (isset($stmt)) $stmt->close();

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shop - Maseno University Cafeteria</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .header {
            background: linear-gradient(90deg, #ff6f00, #ff8c00);
            color: white;
            padding: 15px 60px;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-weight: bold;
        }

        .navbar nav a:hover {
            color: #ffe082;
        }

        .card:hover {
            transform: scale(1.02);
            transition: 0.3s;
        }

        .category-btn.active {
            background-color: #e07b00;
            border-color: #e07b00;
        }
    </style>
</head>
<body>

<header class="header">
    <div class="navbar container">
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
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?php echo $_SESSION['message_type'] === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['message']; unset($_SESSION['message'], $_SESSION['message_type']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Category Filter -->
    <div class="d-flex flex-wrap mb-4">
        <a href="shop.php" class="btn btn-primary me-2 category-btn <?php echo !$category_filter ? 'active' : ''; ?>">All</a>
        <?php foreach ($categories as $category): ?>
            <a href="shop.php?category=<?php echo urlencode($category['category']); ?>" 
               class="btn btn-primary me-2 category-btn <?php echo $category_filter === $category['category'] ? 'active' : ''; ?>">
                <?php echo htmlspecialchars($category['category']); ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Menu Items -->
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
        <?php if (empty($menu_items)): ?>
            <div class="col"><p>No menu items found.</p></div>
        <?php else: ?>
            <?php foreach ($menu_items as $item): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <img src="images/<?php echo htmlspecialchars($item['image']); ?>" 
                             class="card-img-top" 
                             alt="<?php echo htmlspecialchars($item['name']); ?>" 
                             style="height: 200px; object-fit: cover;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?php echo htmlspecialchars($item['name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($item['description']); ?></p>
                            <h6 class="text-warning">KES <?php echo number_format($item['price'], 2); ?></h6>
                            <form action="add_to_cart.php" method="POST" class="mt-auto">
                                <input type="hidden" name="menu_item_id" value="<?php echo $item['id']; ?>">
                                <button type="submit" class="btn btn-primary w-100">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php if (!empty($menu_items)): ?>
        <p class="text-center mt-4">Total items: <?php echo count($menu_items); ?></p>
    <?php endif; ?>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
