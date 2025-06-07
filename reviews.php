<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reviews - Maseno University Cafeteria</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { box-sizing: border-box; padding: 0; margin: 0; font-family: Arial, sans-serif; }

        body { background: #f4f4f4; color: #333; }

        header {
            background-color: #ff6f00;
            padding: 20px 60px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-weight: bold;
        }

        nav a:hover { text-decoration: underline; }

        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 { color: #ff6f00; margin-bottom: 20px; }

        .review {
            background: #fafafa;
            border-left: 4px solid #ff6f00;
            padding: 15px 20px;
            margin-bottom: 20px;
            border-radius: 6px;
        }

        .review h4 {
            margin-bottom: 5px;
            font-size: 16px;
            color: #444;
        }

        .review p { font-size: 14px; }

        footer {
            background: #222;
            color: #ccc;
            text-align: center;
            padding: 20px;
            margin-top: 60px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Maseno University Cafeteria</h1>
        <nav>
            <a href="customer_dashboard.php">Home</a>
            <a href="about.php">About</a>
            <a href="reviews.php">Reviews</a>
            <a href="contact.php">Contact</a>
        </nav>
    </header>

    <div class="container">
        <h2>What Students Say</h2>

        <div class="review">
            <h4>Jane Mwende - 3rd Year IT Student</h4>
            <p>"The cafeteria system is super convenient! I can now order food without leaving class and it's ready when I arrive."</p>
        </div>

        <div class="review">
            <h4>Kevin Otieno - Staff Member</h4>
            <p>"Managing orders has become so easy. The system is user-friendly and saves us a lot of time."</p>
        </div>

        <div class="review">
            <h4>Linda Achieng' - 1st Year Medicine</h4>
            <p>"I love how fast the service has become. No more long queues!"</p>
        </div>
    </div>

    <footer>
        &copy; <?php echo date("Y"); ?> Maseno University Cafeteria System. All rights reserved.
    </footer>
</body>
</html>
