<?php
session_start();
include('db_connect.php'); // Include database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['customer_username'];
    $password = $_POST['customer_password'];

    // Prepare and bind SQL query to fetch user data
    $sql = "SELECT * FROM users WHERE username = ? AND role = 'customer'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a user exists and verify password
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Store user information in session
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect to index page after successful login
            header('Location: index.php');
            exit();
        } else {
            echo 'Invalid credentials. Please try again.';
        }
    } else {
        echo 'User not found.';
    }

    $stmt->close();
    $conn->close();
}
?>
