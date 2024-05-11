<?php
session_start();
require_once("connection.php");

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userID = $_SESSION['user_id'];

// Retrieve selected product IDs and total amount from URL parameter
if (isset($_GET['selectedProducts']) && isset($_GET['totalAmount'])) {
    $selectedProducts = $_GET['selectedProducts'];
    $totalAmount = $_GET['totalAmount'];
} else {
    echo "Invalid request.";
    exit();
}

// Retrieve order details
$order_details_query = "SELECT p.Name AS ProductName, p.Image AS ProductImage, p.Price, ci.Quantity, pm.Type AS PaymentType, pm.Details AS PaymentDetails
                        FROM CartItem_EEA ci
                        JOIN Product_EEA p ON ci.ProductID = p.ProductID
                        LEFT JOIN PaymentMethod_EEA pm ON pm.UserID = $userID
                        WHERE ci.CartItemID IN ($selectedProducts) AND ci.UserID = $userID";
$result_order_details = mysqli_query($conn, $order_details_query);

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
    <h2>Products</h2>
    <?php while ($row = mysqli_fetch_assoc($result_order_details)): ?>
    <div class="product">
        <img src="<?php echo $row['ProductImage']; ?>" alt="<?php echo $row['ProductName']; ?>">
        <p>Name: <?php echo $row['ProductName']; ?></p>
        <p>Price: $<?php echo $row['Price']; ?></p>
        <p>Quantity: <?php echo $row['Quantity']; ?></p>
        <!-- Display payment details for each product -->
        <h2>Payment Details</h2>
        <p>Payment Type: <?php echo $row['PaymentType']; ?></p>
        <p>Payment Details: <?php echo $row['PaymentDetails']; ?></p>
    </div>
    <?php endwhile; ?>
</body>
</html>
