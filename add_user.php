<?php
include 'db_connect.php';

// Initialize message variable
$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password

    // Check if the username, email, or phone already exists
    $checkQuery = "SELECT * FROM users WHERE username = '$username' OR phone = '$phone' OR email = '$email'";
    $result = mysqli_query($conn, $checkQuery);
    if (mysqli_num_rows($result) > 0) {
        $message = "Username, email, or phone number is already registered.";
    } else {
        // Insert the user into the database
        $insertQuery = "INSERT INTO users (username, full_name, email, phone, password, role) 
                        VALUES ('$username', '$full_name', '$email', '$phone', '$hashedPassword', '$role')";
        if (mysqli_query($conn, $insertQuery)) {
            $message = "User added successfully!";
        } else {
            $message = "Error adding user: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
        }

        h2 {
            text-align: center;
        }

        .message {
            text-align: center;
            font-weight: bold;
            color: green;
        }

        form {
            width: 300px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="phone"],
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .btn-submit {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        .btn-submit:hover {
            background-color: #45a049;
        }

        .btn-back {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
        }

        .btn-back:hover {
            background-color: #e53935;
        }
    </style>
</head>
<body>

    <h2>Add New User</h2>

    <?php if (!empty($message)) : ?>
        <p class="message"><?= $message ?></p>
    <?php endif; ?>

    <form action="add_user.php" method="POST">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>

        <label for="full_name">Full Name</label>
        <input type="text" id="full_name" name="full_name" required>

        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" required>

        <label for="phone">Phone Number</label>
        <input type="text" id="phone" name="phone" required>

        <label for="role">Role</label>
        <select id="role" name="role" required>
            <option value="admin">Admin</option>
            <option value="customer">Customer</option>
        </select>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <button type="submit" class="btn-submit">Add User</button>
    </form>

    <!-- Back Button -->
    <button class="btn-back" onclick="window.history.back();">Go Back</button>

</body>
</html>
