<?php
session_start();
require_once("connection.php");

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userID = $_SESSION['user_id'];

// Retrieve selected product IDs from URL parameter
if (isset($_GET['selectedProducts'])) {
    $selectedProducts = $_GET['selectedProducts'];
} else {
    echo "No products selected for checkout.";
    exit();
}

// Update order status to completed in Orders_EEA table
$update_order_query = "UPDATE Orders_EEA SET Status = 'completed' WHERE UserID = $userID";
mysqli_query($conn, $update_order_query);

// Delete products from the CartItem_EEA table
$delete_cart_query = "DELETE FROM CartItem_EEA WHERE CartItemID IN ($selectedProducts) AND UserID = $userID";
mysqli_query($conn, $delete_cart_query);

// Retrieve total amount for each product from Orders_EEA table
$totalAmount = 0;
$get_total_amount_query = "SELECT TotalAmount FROM Orders_EEA WHERE UserID = $userID";
$result_total_amount = mysqli_query($conn, $get_total_amount_query);
while ($row = mysqli_fetch_assoc($result_total_amount)) {
    $totalAmount = $row['TotalAmount'];
}

// Redirect to Orders.php and pass order details as URL parameters
header("Location: orderdetails.php");
// header("Location: Orders.php?selectedProducts=$selectedProducts&totalAmount=$totalAmount");
exit();
?>
