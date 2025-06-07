<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Check if menu_item_id is sent
if (isset($_POST['menu_item_id'])) {
    $menu_item_id = $_POST['menu_item_id'];

    // Initialize cart if not already
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if item already exists in the cart
    if (isset($_SESSION['cart'][$menu_item_id])) {
        // If yes, increase quantity
        $_SESSION['cart'][$menu_item_id]['quantity'] += 1;
    } else {
        // If no, add new item with quantity 1
        require 'db_connect.php';

        $stmt = $conn->prepare("SELECT id, name, price FROM menu_items WHERE id = ?");
        $stmt->bind_param("i", $menu_item_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $item = $result->fetch_assoc();

        if ($item) {
            $_SESSION['cart'][$menu_item_id] = [
                'id' => $item['id'],
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => 1
            ];

            $_SESSION['message'] = "Added {$item['name']} to cart!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Item not found.";
            $_SESSION['message_type'] = "danger";
        }

        $stmt->close();
    }
} else {
    $_SESSION['message'] = "Invalid request.";
    $_SESSION['message_type'] = "danger";
}

// Redirect back to shop page
header("Location: shop.php");
exit;
?>
