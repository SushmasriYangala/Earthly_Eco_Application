<?php
// Start session
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page
    header('Location: login.php');
    exit();
}

// Include database connection
require_once("connection.php");

// Get user ID from session
$userID = $_SESSION['user_id'];

// Retrieve shipping address from User_EEA table
$query_user = "SELECT * FROM User_EEA WHERE UserID = $userID";
$result_user = mysqli_query($conn, $query_user);
$user_info = mysqli_fetch_assoc($result_user);

// Retrieve payment details from PaymentMethod_EEA table
$query_payment = "SELECT * FROM PaymentMethod_EEA WHERE UserID = $userID";
$result_payment = mysqli_query($conn, $query_payment);
$payment_info = mysqli_fetch_assoc($result_payment);

// Retrieve selected product IDs from URL parameter
if (isset($_GET['selectedProducts'])) {
    $selectedProducts = $_GET['selectedProducts'];
    $query_cart = "SELECT CI.CartItemID, CI.Quantity, p.Name, p.Price
               FROM CartItem_EEA CI
               JOIN Product_EEA p ON CI.ProductID = p.ProductID
               WHERE CI.CartItemID IN ($selectedProducts)
               AND CI.UserID = $userID";
    $result_cart = mysqli_query($conn, $query_cart);
} else {
    // No products selected, display an appropriate message or redirect
    echo "No products selected for checkout.";
    exit();
}

// Check if payment details are available
if (empty($payment_info)) {
    // Payment details not available, display add payment button
    $payment_button_text = "Add Payment";
    // Include selectedProducts parameter in the URL if it's defined
    $payment_button_url = isset($selectedProducts) ? "add_Payment.php?selectedProducts=" . urlencode($selectedProducts) : "add_Payment.php";
} else {
    // Payment details available, display update payment button
    $payment_button_text = "Update Payment";
    // Include selectedProducts parameter in the URL if it's defined
    $payment_button_url = isset($selectedProducts) ? "update_payment.php?selectedProducts=" . urlencode($selectedProducts) : "update_payment.php";
}


// Calculate total amount
$totalAmount = 0;
while ($row = mysqli_fetch_assoc($result_cart)) {
    $totalAmount += $row['Price'] * $row['Quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Confirmation</title>
    <link rel="stylesheet" href="../CSS/search.css" />
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="../CSS/back-home.css">
    <link rel="stylesheet" type="text/css" href="../CSS/Checkout.css">
</head>
<body>
<?php require_once('home-back.php'); ?>
    <div class="container">
        <h1>Checkout Confirmation</h1>
        
        <h2>Shipping Address</h2>
        <p>Name: <?php echo $user_info['Username']; ?></p>
        <p>Email: <?php echo $user_info['Email']; ?></p>
        <p>Address: <?php echo $user_info['Address']; ?></p>
        <!-- Add more shipping address details if needed -->

        <h2>Payment Details</h2>
        <?php if (empty($payment_info)): ?>
            <button type="submit" onclick="location.href='<?php echo $payment_button_url; ?>'"><?php echo $payment_button_text; ?></button>
        <?php else: ?>
            <p>Card Type: <?php echo $payment_info['Type']; ?></p>
            <p>Card Number: <?php echo $payment_info['Details']; ?></p>
            <!-- Add more payment details if needed -->
            <button type="submit" onclick="location.href='<?php echo $payment_button_url; ?>'"><?php echo $payment_button_text; ?></button>
        <?php endif; ?>

        <h2>Selected Order Products</h2>
        <div>
            <?php mysqli_data_seek($result_cart, 0); // Reset pointer to beginning of result set ?>
            <?php while ($row = mysqli_fetch_assoc($result_cart)): ?>
                <p>Product: <?php echo $row['Name']; ?></p>
                <p>Price: $<?php echo $row['Price']; ?></p>
                <p>Quantity: <?php echo $row['Quantity']; ?></p>
                <!-- Add more product details if needed -->
            <?php endwhile; ?>
            <p>Total Amount: $<?php echo $totalAmount; ?></p>
        </div>

        <!-- Add a button for additional actions, e.g., return to cart -->
        <button type="submit" onclick="location.href='cart.php'">Return to Cart</button>
        
        <!-- Adjust the link to include selectedProducts parameter -->
        <button type="submit" onclick="location.href='place_order.php?selectedProducts=<?php echo urlencode($selectedProducts); ?>'">Place Order</button>
    </div>
</body>
</html>
