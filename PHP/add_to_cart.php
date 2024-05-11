<?php
// Start session

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once("connection.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
  // Redirect to login page
  header('Location: login.php');
  exit();
}

// Get product ID
$productID = $_POST['ProductID'];

// Default quantity is 1
$quantity = 1;

// Check if quantity is provided and greater than 0
if (isset($_POST['Quantity']) && $_POST['Quantity'] > 0) {
  $quantity = $_POST['Quantity'];
}

// Get user ID from session
$userID = $_SESSION['user_id'];

// Retrieve the maximum CartItemID from the database
$result = mysqli_query($conn, "SELECT MAX(CartItemID) AS maxCartItemID FROM CartItem_EEA");
$row = mysqli_fetch_assoc($result);
$maxCartItemID = $row['maxCartItemID'];

// Generate a new CartItemID by incrementing the maximum CartItemID
$CartItemID = $maxCartItemID + 1;

// Check if product is already in cart
$query = "SELECT * FROM CartItem_EEA WHERE UserID = $userID AND ProductID = $productID";
$result = mysqli_query($conn, $query);
if (!$result) {
  // Query error
  die("Error: " . mysqli_error($conn));
}

if (mysqli_num_rows($result) > 0) {
  // Product is already in cart, update quantity
  $row = mysqli_fetch_assoc($result);
  $currentQuantity = $row['Quantity'];
  $newQuantity = $currentQuantity + $quantity;

  $updateQuery = "UPDATE CartItem_EEA SET Quantity = $newQuantity WHERE UserID = $userID AND ProductID = $productID";
  $updateResult = mysqli_query($conn, $updateQuery);
  if ($updateResult) {
    echo 'Product quantity has been updated in your cart.';
  } else {
    echo 'Error updating product quantity in cart.';
  }
} else {
  // Add product to cart
  $insertQuery = "INSERT INTO CartItem_EEA (CartItemID, UserID, ProductID, Quantity) VALUES ($CartItemID, $userID, $productID, $quantity)";
  $insertResult = mysqli_query($conn, $insertQuery);
  if ($insertResult) {
    echo 'Product has been added to your cart.';
  } else {
    echo 'Error adding product to cart.';
  }
}

header('Location: cart.php');
?>
