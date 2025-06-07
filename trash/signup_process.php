<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "cafe");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get and sanitize form data
$username = trim($_POST['username']);
$first_name = trim($_POST['first_name']);
$last_name = trim($_POST['last_name']);
$full_name = $first_name . " " . $last_name;
$email = trim($_POST['email']);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // hashed
$phone = trim($_POST['phone']);
$role = 'customer'; // default

// Prepare SQL statement
$stmt = $conn->prepare("INSERT INTO users (username, full_name, email, password, phone, role) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $username, $full_name, $email, $password, $phone, $role);

// Execute and check
if ($stmt->execute()) {
    echo "Signup successful!";
    header("Location: login.php");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
