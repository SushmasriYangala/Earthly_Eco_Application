<!DOCTYPE html>
<html>
<head>
    <title>Categories</title>
    <link rel="stylesheet" href="../CSS/category.css">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="../CSS/back-home.css">
</head>
<body>
    <?php
    require_once('home-back.php');
    ?>
    <?php
    session_start();
    require_once('connection.php');
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../login.html');
        exit();
    }
    ?>
    <form action="category_list.php" method="POST">
        <div class="eco-card">
            <img src="../Images/curtain.jpeg" alt="curtain">
            <input type="hidden" name="ProductID" value="">
            <button type="submit" name="Category" value="Clothing">Clothing</button>
        </div>
        <div class="eco-card">
            <img src="../Images/shampoo.jpeg" alt="shampoo">
            <input type="hidden" name="ProductID" value="">
            <button type="submit" name="Category" value="cosmetic">Cosmetic</button>
        </div>
        <div class="eco-card">
            <img src="../Images/Candle.jpeg" alt="candle">
            <input type="hidden" name="ProductID" value="">
            <button type="submit" name="Category" value="candle">Candle</button>
        </div>
        <div class="eco-card">
            <img src="../Images/Home goods.jpeg" alt="homegoods">
            <input type="hidden" name="ProductID" value="">
            <button type="submit" name="Category" value="Homegoods">Homegoods</button>
        </div>
        <div class="eco-card">
            <img src="../Images/veg.jpeg" alt="food">
            <input type="hidden" name="ProductID" value="">
            <button type="submit" name="Category" value="food">Food</button>
        </div>
    </form>
</body>
</html>
