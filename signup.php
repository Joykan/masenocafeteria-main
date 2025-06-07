<?php
session_start();
include('db_connect.php'); // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        $_SESSION['message'] = "Signup successful!";
        $_SESSION['message_type'] = "success";
        header("Location: customer_dashboard.php");
        exit();
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
        $_SESSION['message_type'] = "error";
        // Redirect back to signup.php but not as a POST
        header("Location: signup.php");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    // Show the signup form normally (do nothing here)
    // Do NOT redirect back immediately
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - Maseno University Cafeteria</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: url('images/auth-img.png') no-repeat center center/cover;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

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
            transition: color 0.3s ease;
        }

        .navbar nav a:hover {
            color: #ffe082;
        }

        .form-container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
            width: 100%;
            max-width: 500px;
        }

        .form-box {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            width: 100%;
            text-align: center;
            animation: fadeIn 0.5s ease forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-box h2 {
            font-size: 28px;
            color: #333;
            margin-bottom: 20px;
        }

        input {
            width: 100%;
            padding: 15px;
            margin: 12px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        input:focus {
            border-color: #ff8c00;
            outline: none;
        }

        .btn {
            background: #ff8c00;
            color: white;
            padding: 15px;
            border: none;
            border-radius: 25px;
            font-size: 18px;
            cursor: pointer;
            width: 100%;
            transition: background 0.3s ease;
        }

        .btn:hover {
            background: #e07b00;
        }

        .login-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-size: 16px;
            font-weight: 600;
            color: #ff8c00;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .login-link:hover {
            color: #e07b00;
            text-decoration: underline;
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

            .form-container {
                padding: 20px 10px;
            }

            .form-box {
                padding: 30px;
            }
        }

        @media (max-width: 480px) {
            .navbar h1 {
                font-size: 24px;
            }

            .form-box h2 {
                font-size: 24px;
            }

            .btn {
                font-size: 16px;
            }

            input {
                padding: 12px;
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
                <a href="index.php">Home</a>
                <a href="login.php">Login</a>
                <a href="signup.php">Sign up</a>
            </nav>
        </div>
    </header>

    <section class="form-container">
        <div class="form-box">
            <h2>Sign Up</h2>
            <form action="" method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="text" name="first_name" placeholder="First Name" required>
                <input type="text" name="last_name" placeholder="Last Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="text" name="phone" placeholder="Phone Number">
                <button type="submit" class="btn">Sign Up</button>
                <a href="login.php" class="login-link">Already have an account? Login</a>
            </form>
        </div>
    </section>
</body>
</html>