<?php
session_start();
include('db_connect.php'); // Include database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Determine which form was submitted
    $is_admin_form = isset($_POST['admin_username']) && isset($_POST['admin_password']);
    $is_customer_form = isset($_POST['customer_username']) && isset($_POST['customer_password']);

    // Set username and password based on the form submitted
    if ($is_admin_form) {
        $username = $_POST['admin_username'];
        $password = $_POST['admin_password'];
        $expected_role = 'admin';
    } elseif ($is_customer_form) {
        $username = $_POST['customer_username'];
        $password = $_POST['customer_password'];
        $expected_role = 'customer';
    } else {
        $_SESSION['error'] = 'Invalid form submission.';
        header('Location: login.php');
        exit();
    }

    // Prepare and bind SQL query to fetch user data
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Check if the user's role matches the expected role for the form
            if ($user['role'] === $expected_role) {
                // Set session variables
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Redirect based on role
                if ($user['role'] === 'customer') {
                    header('Location: customer_dashboard.php');
                    exit();
                } elseif ($user['role'] === 'admin') {
                    header('Location: admin_panel.php');
                    exit();
                }
            } else {
                // Wrong form used for the user's role
                $_SESSION['error'] = $user['role'] === 'admin' 
                    ? 'Admins must use the Admin Login form.' 
                    : 'Customers must use the Customer Login form.';
                header('Location: login.php');
                exit();
            }
        } else {
            $_SESSION['error'] = 'Invalid credentials. Please try again.';
            header('Location: login.php');
            exit();
        }
    } else {
        $_SESSION['error'] = 'User not found.';
        header('Location: login.php');
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Maseno University Cafeteria</title>
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

        .form-selector {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 30px 0;
        }

        .form-selector button {
            background: #ff8c00;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        .form-selector button.active {
            background: #e07b00;
            transform: scale(1.1);
        }

        .form-selector button:hover {
            background: #e07b00;
        }

        .form-container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
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
            display: none;
            animation: fadeIn 0.5s ease forwards;
        }

        .form-box.active {
            display: block;
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

        .extra-options {
            margin-top: 15px;
            font-size: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .remember-me {
            display: flex;
            align-items: center;
            font-size: 16px;
            color: #555;
        }

        .remember-me input {
            transform: scale(1.5);
            margin-right: 8px;
            accent-color: #ff8c00;
        }

        .create-account {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-size: 16px;
            font-weight: 600;
            color: #ff8c00;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .create-account:hover {
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
                padding: 10px;
            }

            .form-box {
                padding: 30px;
            }
        }

        @media (max-width: 480px) {
            .navbar h1 {
                font-size: 24px;
            }

            .form-selector button {
                padding: 10px 20px;
                font-size: 14px;
            }

            .form-box h2 {
                font-size: 24px;
            }

            .btn {
                font-size: 16px;
            }
        }

        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #ff4d4f;
            color: white;
            padding: 15px 25px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            opacity: 0;
            pointer-events: none;
            transform: translateY(-20px);
            transition: all 0.4s ease;
            z-index: 9999;
            font-size: 16px;
            font-weight: 500;
        }

        .toast.show {
            opacity: 1;
            pointer-events: auto;
            transform: translateY(0);
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

    <div id="toast" class="toast"></div>

    <div class="form-selector">
        <button id="customerBtn" class="active">Customer Login</button>
        <button id="adminBtn">Admin Login</button>
    </div>

    <section class="form-container">
        <div id="customerForm" class="form-box active">
            <h2>Customer Login</h2>
            <form action="" method="POST">
                <input type="text" name="customer_username" placeholder="Username" required>
                <input type="password" name="customer_password" placeholder="Password" required>
                <button type="submit" class="btn">Log in</button>
                <div class="extra-options">
                    <label class="remember-me">
                        <input type="checkbox" name="remember"> Remember me
                    </label>
                </div>
                <a href="signup.php" class="create-account">Create an Account</a>
            </form>
        </div>

        <div id="adminForm" class="form-box">
            <h2>Admin Login</h2>
            <form action="" method="POST">
                <input type="text" name="admin_username" placeholder="Username" required>
                <input type="password" name="admin_password" placeholder="Password" required>
                <button type="submit" class="btn">Log in</button>
                <div class="extra-options">
                    <label class="remember-me">
                        <input type="checkbox" name="remember"> Remember me
                    </label>
                </div>
            </form>
        </div>
    </section>

    <script>
        const customerBtn = document.getElementById('customerBtn');
        const adminBtn = document.getElementById('adminBtn');
        const customerForm = document.getElementById('customerForm');
        const adminForm = document.getElementById('adminForm');

        customerBtn.addEventListener('click', () => {
            customerBtn.classList.add('active');
            adminBtn.classList.remove('active');
            customerForm.classList.add('active');
            adminForm.classList.remove('active');
        });

        adminBtn.addEventListener('click', () => {
            adminBtn.classList.add('active');
            customerBtn.classList.remove('active');
            adminForm.classList.add('active');
            customerForm.classList.remove('active');
        });

        // Display the toast if message is available
        <?php
        if (isset($_SESSION['error'])) {
            $error = $_SESSION['error'];
            echo "showToast('$error');";
            unset($_SESSION['error']);
        }
        ?>

        function showToast(message) {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
            }, 4000); // Hide after 4 seconds
        }
    </script>
</body>
</html>