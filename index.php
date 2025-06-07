<?php
session_start();
require 'db_connect.php';

// Fetch all menu items from database
$result = $conn->query("SELECT id, name, price, image, description FROM menu_items");
$menu_items = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maseno University Cafeteria</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f8f9fa;
            line-height: 1.6;
        }

        .header {
            background: linear-gradient(90deg, #ff6f00, #ff8c00);
            color: white;
            padding: 15px 60px;
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

        .hero {
            position: relative;
            height: 500px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            background: url('images/pilau.jpeg') no-repeat center center/cover;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
        }

        .hero h2 {
            position: relative;
            font-size: 48px;
            font-weight: 800;
            color: white;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.6);
            max-width: 800px;
            padding: 0 20px;
        }

        .intro {
            text-align: center;
            padding: 40px 20px;
            max-width: 800px;
            margin: 0 auto;
        }

        .intro h2 {
            font-size: 36px;
            color: #333;
            margin-bottom: 20px;
        }

        .intro p {
            font-size: 18px;
            color: #555;
            line-height: 1.8;
        }

        /* Products Section (Bootstrap + Animation) */
        .menu-card {
            transition: transform 0.3s ease, opacity 0.5s ease;
            opacity: 0;
            display: none;
        }
        .menu-card.active {
            opacity: 1;
            display: block;
        }
        .menu-card:hover {
            transform: translateY(-10px);
        }
        .menu-card img {
            height: 200px;
            object-fit: cover;
        }
        .menu-card.beat {
            animation: beat 1.5s ease-in-out infinite;
        }
        @keyframes beat {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        .footer {
            background: #1a1a1a;
            color: white;
            padding: 40px 60px;
        }

        .footer-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
            padding-bottom: 20px;
        }

        .footer-section h3 {
            font-size: 20px;
            margin-bottom: 15px;
            color: #ff8c00;
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section ul li {
            margin: 10px 0;
        }

        .footer-section ul li a {
            color: #ddd;
            text-decoration: none;
            font-size: 16px;
            transition: color 0.3s ease;
        }

        .footer-section ul li a i {
            margin-right: 10px;
        }

        .footer-section ul li a:hover {
            color: #ff8c00;
        }

        .footer-section p {
            font-size: 16px;
            color: #ddd;
            margin: 10px 0;
        }

        .footer-section p i {
            margin-right: 10px;
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 15px;
            }

            .navbar nav {
                gap: 15px;
            }

            .hero h2 {
                font-size: 32px;
            }
        }

        @media (max-width: 480px) {
            .header {
                padding: 10px 20px;
            }

            .navbar h1 {
                font-size: 24px;
            }

            .hero {
                height: 300px;
            }

            .hero h2 {
                font-size: 24px;
            }

            .intro h2 {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="navbar">
            <h1>Maseno University Cafeteria</h1>
            <nav>
                <a href="login.php">Login</a>
                <a href="signup.php">Sign Up</a>
            </nav>
        </div>
    </header>
    
    <section class="hero">
        <h2>Your favorite cafeteria food delivered for free</h2>
    </section>
    
    <section class="intro">
        <h2>Welcome to Maseno University Cafeteria</h2>
        <p>Discover a variety of delicious meals at affordable prices with fast delivery and exceptional service. Order now and enjoy your food delivered right to your door.</p>
    </section>
    
    <section class="container my-5">
        <h2 class="text-center mb-4">Our Menu</h2>
        <div class="row row-cols-1 row-cols-md-3 g-4" id="menu-items">
            <?php foreach ($menu_items as $index => $item): ?>
                <div class="col">
                    <div class="card menu-card h-100" data-index="<?php echo $index; ?>">
                        <img src="<?php echo htmlspecialchars($item['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($item['name']); ?>">
                        <div class="card-body text-center">
                            <h3 class="card-title"><?php echo htmlspecialchars($item['name']); ?></h3>
                            <p class="card-text"><?php echo htmlspecialchars($item['description']); ?></p>
                            <p class="card-text text-success fw-bold">KES <?php echo number_format($item['price'], 2); ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-section">
                <h3>Get to Know Us</h3>
                <ul>
                    <li><a href="#"><i class="fa-solid fa-building"></i> About Us</a></li>
                    <li><a href="#"><i class="fa-solid fa-concierge-bell"></i> Our Services</a></li>
                    <li><a href="#"><i class="fa-solid fa-envelope"></i> Contact Us</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Connect with Us</h3>
                <ul>
                    <li><a href="#"><i class="fa-brands fa-facebook"></i> Facebook</a></li>
                    <li><a href="#"><i class="fa-brands fa-instagram"></i> Instagram</a></li>
                    <li><a href="#"><i class="fa-brands fa-twitter"></i> Twitter</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contact Info</h3>
                <p><i class="fa-solid fa-location-dot"></i> Maseno University College Campus</p>
                <p><i class="fa-solid fa-envelope"></i> masenocafe5@gmail.com</p>
                <p><i class="fa-solid fa-phone"></i> 0780421789 | 0757675130</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        // Carousel-like cycling of 3 items
        const menuItems = document.querySelectorAll('.menu-card');
        const totalItems = menuItems.length;
        let currentGroup = 0;

        function showGroup(groupIndex) {
            // Hide all cards
            menuItems.forEach(card => {
                card.classList.remove('active');
                card.classList.remove('beat');
            });

            // Calculate start and end indices for the current group of 3
            const start = groupIndex * 3;
            const end = Math.min(start + 3, totalItems);

            // Show and animate the current group
            for (let i = start; i < end; i++) {
                menuItems[i].classList.add('active');
                menuItems[i].classList.add('beat');
            }
        }

        // Initial display
        if (totalItems > 0) {
            showGroup(currentGroup);
        }

        // Cycle every 5 seconds
        setInterval(() => {
            currentGroup = (currentGroup + 1) % Math.ceil(totalItems / 3);
            showGroup(currentGroup);
        }, 5000);

        // Intersection Observer for beat animation (reinforces visibility)
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && entry.target.classList.contains('active')) {
                    entry.target.classList.add('beat');
                } else {
                    entry.target.classList.remove('beat');
                }
            });
        }, { threshold: 0.2 });

        menuItems.forEach(card => observer.observe(card));
    </script>
</body>
</html>