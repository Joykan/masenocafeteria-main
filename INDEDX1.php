<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maseno University Cafeteria</title>
    <link rel="stylesheet" href="styles.css">
</head>

<style>
/* Reset styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

/* Header */
header {
    background: #ff6600;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 50px;
}

header .logo {
    font-size: 24px;
    font-weight: bold;
}

/* Navigation */
nav ul {
    list-style: none;
    display: flex;
}

nav ul li {
    margin: 0 15px;
}

nav ul li a {
    text-decoration: none;
    color: white;
    font-weight: bold;
}

/* Hero Section */
.hero {
    background: url('images/pilau.jpeg') no-repeat center center/cover;
    height: 80vh; /* Increased height */
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
}

.hero-text h1 {
    font-size: 60px; /* Increased font size */
    color: white;
    font-weight: bold;
    text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5);
}

.hero-text span {
    color: #ff6600;
}

/* Introduction */
.intro {
    text-align: center;
    padding: 50px;
}

.intro h2 {
    font-size: 36px; /* Increased font size */
    font-weight: bold;
}

.intro p {
    font-size: 22px; /* Increased font size */
    color: gray;
}
</style>

<body>

    <!-- Navigation Bar -->
    <header>
        <div class="logo">Maseno University Cafeteria</div>
        <nav>
            <ul>
                <li><a href="#">Menus</a></li>
                <li><a href="#">My Cart</a></li>
                <li><a href="#">Logout</a></li>
            </ul>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-text">
            <h1>Get <br> <span>Started</span></h1>
        </div>
    </section>

    <!-- Introduction -->
    <section class="intro">
        <h2>Find All Your Favourite</h2>
        <p>Taste Here.</p>
    </section>

</body>
</html>
