<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>About Us - Earthly Eco</title>
    <link rel="stylesheet" href="../CSS/aboutus.css" />
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" type="text/css" href="../CSS/back-home.css">
    </head>
  <body>
  <?php
session_start();
require_once("connection.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.html');
    exit();
}
require_once('home-back.php');
?>
    <header class="header">
      <h1>About Us</h1>
    </header>

    <section class="about-section">
  <div class="container">
    <p>
    At EarthlyEcoMarket, we're passionate about providing a platform where conscious consumers can discover and purchase high-quality organic products that align with their values. Our mission is to promote sustainability, environmental awareness, and healthy living through our curated selection of organic goods. Founded with the belief that every purchase can make a positive impact on our planet, EarthlyEcoMarket strives to support eco-friendly practices and ethical sourcing. We partner with trusted suppliers and artisans who share our commitment to sustainability and transparency. Whether you're looking for organic food, skincare, home essentials, or wellness products, you can trust EarthlyEcoMarket to deliver premium quality items that are good for you and the planet. Join us on our journey towards a greener, healthier future. Thank you for choosing EarthlyEcoMarket as your destination for all things organic and sustainable.
</p>
  </div>
</section>
<footer class="footer">
  <div class="container">
    <p>&copy; 2023 Earthly Eco</p>
  </div>
</footer>
</body>
</html>
