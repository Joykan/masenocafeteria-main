<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us - Maseno University Cafeteria</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: #f4f4f4;
            color: #333;
            line-height: 1.6;
        }

        header {
            background: #ff6f00;
            color: white;
            padding: 20px 60px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            font-size: 24px;
        }

        nav a {
            color: white;
            margin-left: 20px;
            text-decoration: none;
            font-weight: 500;
        }

        nav a:hover {
            text-decoration: underline;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        h2 {
            color: #ff6f00;
            margin-bottom: 20px;
        }

        p {
            margin-bottom: 20px;
            font-size: 16px;
        }

        footer {
            background: #222;
            color: #ccc;
            padding: 20px;
            text-align: center;
            margin-top: 60px;
        }

        @media (max-width: 768px) {
            header {
                flex-direction: column;
                align-items: flex-start;
            }

            nav {
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Maseno University Cafeteria</h1>
        <nav>
            <a href="customer_dashboard.php">Home</a>
            <a href="shop.php">menu</a>
            <a href="logout.php">logout</a>
        </nav>
    </header>

    <div class="container">
        <h2>About Us</h2>
        <p>
            The Maseno University Cafeteria System is an innovative platform built to streamline the food ordering experience for students and staff at Maseno University.
        </p>
        <p>
            Our system enables easy access to daily menus, efficient ordering, and accurate tracking of cafeteria services. We aim to reduce waiting times, ensure food availability, and enhance customer satisfaction.
        </p>
        <p>
            Managed and maintained by the university’s IT department, this system ensures transparency, reliability, and efficiency for all stakeholders.
        </p>
        <p>
            Whether you're a student looking for a quick meal or an admin managing food operations, our platform offers a modern and user-friendly interface tailored for your needs.
        </p>
    </div>

    <footer>
        &copy; <?php echo date("Y"); ?> Maseno University Cafeteria System. All rights reserved.
    </footer>
</body>
</html>
