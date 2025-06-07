<?php
session_start();
include('db_connect.php'); // Include database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $admin_username = $_POST['admin_username'];
    $admin_password = $_POST['admin_password'];

    // Prepare and bind SQL query to fetch admin data
    $sql = "SELECT * FROM users WHERE username = ? AND role = 'admin'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $admin_username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if an admin exists and verify password
    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        if (password_verify($admin_password, $admin['password'])) {
            // Store admin information in session
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['role'] = $admin['role'];

            // Redirect to index page after successful login
            header('Location: admin_dashboard.php');
            exit();
        } else {
            echo 'Invalid credentials. Please try again.';
        }
    } else {
        echo 'Admin not found.';
    }

    $stmt->close();
    $conn->close();
}
?>
