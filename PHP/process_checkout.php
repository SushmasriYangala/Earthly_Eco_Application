<?php
// Start session
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Check if selectedProducts is set
if (!isset($_POST['selectedProducts'])) {
    header('Location: cart.php');
    exit();
}

// Include database connection
require_once("connection.php");

// Get user ID from session
$userID = $_SESSION['user_id'];

// Counter for OrderID
$order_counter_result = mysqli_query($conn, "SELECT MAX(OrderID) AS maxOrderID FROM Orders_EEA");
$order_counter_row = mysqli_fetch_assoc($order_counter_result);
$orderID = $order_counter_row['maxOrderID'] + 1;

// Insert order into Orders_EEA table
$orderDate = date('Y-m-d H:i:s');
$totalAmount = 0; // Initialize total amount
$status = 'Pending'; // Assuming the status is set to 'Pending' by default
$insertOrderQuery = "INSERT INTO Orders_EEA (OrderID, UserID, OrderDate, TotalAmount, Status)
                     VALUES ($orderID, $userID, '$orderDate', 0, '$status')";
$orderResult = mysqli_query($conn, $insertOrderQuery);

if (!$orderResult) {
    // Error inserting order
    header('Location: error_page.php?message=' . urlencode("Error inserting order: " . mysqli_error($conn)));
    exit();
}

// Loop through selected products
foreach ($_POST['selectedProducts'] as $selectedProductID) {
    // Retrieve cart item details
    $cartItemID = (int)$selectedProductID; // Convert to integer
    $query = "SELECT p.ProductID, p.Price, c.Quantity
              FROM CartItem_EEA c
              JOIN Product_EEA p ON c.ProductID = p.ProductID
              WHERE c.CartItemID = $cartItemID
              AND c.UserID = $userID";
    $result = mysqli_query($conn, $query);

    if (!$result || mysqli_num_rows($result) != 1) {
        // Error retrieving cart item or cart item not found
        header('Location: error_page.php?message=' . urlencode("Error retrieving cart item or cart item not found."));
        exit();
    }

    $row = mysqli_fetch_assoc($result);
    $productID = $row['ProductID'];
    $price = $row['Price'];
    $quantity = $row['Quantity'];

    // Calculate total amount for this product
    $productTotal = $quantity * $price;
    $totalAmount += $productTotal;

    // Counter for OrderID
    $OrderItem_counter_result = mysqli_query($conn, "SELECT MAX(OrderItemID) AS maxOrderItemID FROM OrderItem_EEA");
    $OrderItem_counter_row = mysqli_fetch_assoc($OrderItem_counter_result);
    $orderItemID = $OrderItem_counter_row['maxOrderItemID'] + 1;

    $insertOrderItemQuery = "INSERT INTO OrderItem_EEA (OrderItemID, OrderID, ProductID, Quantity, UnitPrice)
                             VALUES ($orderItemID, $orderID, $productID, $quantity, $price)";
    $orderItemResult = mysqli_query($conn, $insertOrderItemQuery);

    if (!$orderItemResult) {
        echo "Error inserting order item: " . mysqli_error($conn);
        // Rollback the order
        mysqli_query($conn, "DELETE FROM Orders_EEA WHERE OrderID = $orderID");
        exit();
    }
}

// Update total amount in Orders_EEA table
$updateTotalAmountQuery = "UPDATE Orders_EEA SET TotalAmount = $totalAmount WHERE OrderID = $orderID";
$updateResult = mysqli_query($conn, $updateTotalAmountQuery);

if (!$updateResult) {
    echo "Error updating total amount: " . mysqli_error($conn);
    // Rollback the order
    mysqli_query($conn, "DELETE FROM Orders_EEA WHERE OrderID = $orderID");
    exit();
}

// Redirect to checkout confirmation page with selectedProducts parameter
$selectedProducts = implode(',', $_POST['selectedProducts']);
header('Location: checkout_confirmation.php?selectedProducts=' . urlencode($selectedProducts));
exit();

?>
