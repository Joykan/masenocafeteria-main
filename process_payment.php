<?php
session_start();
include('config.php'); // Include your database connection file

if (isset($_POST['pay_now'])) {
    $total_price = $_POST['total_price'];
    $user_id = $_SESSION['user_id']; // Assuming the user's ID is stored in the session

    // Step 1: Save the order details to the database
    $order_query = "INSERT INTO orders (user_id, total_price, order_date) VALUES ('$user_id', '$total_price', NOW())";
    if (mysqli_query($conn, $order_query)) {
        $order_id = mysqli_insert_id($conn); // Get the ID of the newly inserted order

        // Step 2: Save the items in the order_items table
        $cart_items = $_SESSION['cart_items'];
        foreach ($cart_items as $item) {
            $menu_item_id = $item['id'];
            $quantity = $item['quantity'];
            $price = $item['price'];
            $order_item_query = "INSERT INTO order_items (order_id, menu_item_id, quantity, price) 
                                 VALUES ('$order_id', '$menu_item_id', '$quantity', '$price')";
            mysqli_query($conn, $order_item_query);
        }

        // Step 3: Clear the cart after successful payment
        unset($_SESSION['cart_items']);

        // Step 4: Redirect to a confirmation page
        header("Location: order_confirmation.php?order_id=" . $order_id);
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
