<?php
include 'db_connect.php';

// Check if the order ID and status are passed
if (isset($_GET['id']) && isset($_GET['status'])) {
    $order_id = $_GET['id'];
    $status = $_GET['status'];

    // Validate the status (it should either be "Completed" or "Cancelled")
    if ($status == 'Completed' || $status == 'Cancelled') {
        // Update the order status in the database
        $update_query = "UPDATE orders SET status = '$status' WHERE id = $order_id";
        $result = mysqli_query($conn, $update_query);

        if ($result) {
            // Redirect to the pending orders page with a success message
            header("Location: pending_orders.php?msg=Order status updated to $status");
            exit();
        } else {
            // Redirect with an error message if something went wrong
            header("Location: pending_orders.php?msg=Error updating order status");
            exit();
        }
    } else {
        // Invalid status, redirect with an error message
        header("Location: pending_orders.php?msg=Invalid status");
        exit();
    }
} else {
    // If ID or status is not set, redirect back with an error message
    header("Location: pending_orders.php?msg=Order ID or status not provided");
    exit();
}
?>
