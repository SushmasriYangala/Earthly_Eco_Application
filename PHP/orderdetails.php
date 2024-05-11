<?php
session_start();
require_once("connection.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userID = $_SESSION['user_id'];

// Retrieve completed orders with user, product, and payment details
$query = "SELECT u.*, p.*, o.OrderDate, o.TotalAmount, o.Status, pm.*
        FROM Orders_EEA o
        JOIN User_EEA u ON o.UserID = u.UserID
        JOIN PaymentMethod_EEA pm ON o.UserID = pm.UserID
        JOIN OrderItem_EEA oi ON o.OrderID = oi.OrderID
        JOIN Product_EEA p ON oi.ProductID = p.ProductID
        WHERE o.Status = 'completed' AND o.UserID = $userID ";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="../CSS/back-home.css">
    <link rel="stylesheet" type="text/css" href="../CSS/order.css">
</head>
<body>
    <?php require_once('home-back.php'); ?>
    <h1>Order Details</h1>
    <h2>Completed Orders</h2>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="product">
            <div class="product-details">
                <h3>Product Details</h3>
                <img src="<?php echo $row['Image']; ?>" alt="<?php echo $row['Name']; ?>">
                <p>Name: <?php echo $row['Name']; ?></p>
                <p>Price: $<?php echo $row['Price']; ?></p>
            </div>
            <div class="user-details">
                <p>Name: <?php echo $row['Username']; ?></p>
            </div>
            <div class="payment-details">
                <h3>Payment Details</h3>
                <p>Payment Type: <?php echo $row['Type']; ?></p>
                <p>Payment Details: <?php echo $row['Details']; ?></p>
            </div>
        </div>
    <?php endwhile; ?>

</body>
</html>
