<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us - Maseno University Cafeteria</title>
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
            max-width: 800px;
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

        form label {
            display: block;
            margin: 15px 0 5px;
            font-weight: bold;
        }

        form input, form textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
        }

        form button {
            margin-top: 20px;
            background: #ff6f00;
            color: white;
            padding: 10px 25px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        form button:hover { background: #e65c00; }

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
        <h2>Contact Us</h2>
        <p>Have questions, suggestions, or feedback? Get in touch with us using the form below.</p>

        <form action="#" method="post">
            <label for="name">Full Name</label>
            <input type="text" name="name" id="name" required>

            <label for="email">Email Address</label>
            <input type="email" name="email" id="email" required>

            <label for="message">Your Message</label>
            <textarea name="message" id="message" rows="6" required></textarea>

            <button type="submit">Send Message</button>
        </form>
    </div>

    <footer>
        &copy; <?php echo date("Y"); ?> Maseno University Cafeteria System. All rights reserved.
    </footer>
</body>
</html>
