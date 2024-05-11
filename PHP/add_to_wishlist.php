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

// Get user ID from session
$userID = $_SESSION['user_id'];
// Retrieve the maximum wishlistItemID from the database
$result = mysqli_query($conn, "SELECT MAX(wishlistItemID) AS maxwishlistItemID FROM Wishlist_EEA");
$row = mysqli_fetch_assoc($result);
$maxwishlistItemID = $row['maxwishlistItemID'];

// Generate a new wishlistItemID by incrementing the maximum wishlistItemID
$wishlistItemID = $maxwishlistItemID + 1;

// Check if product is already in wishlist
$query = "SELECT * FROM Wishlist_EEA WHERE UserID = $userID AND ProductID = $productID";
$result = mysqli_query($conn, $query);
if (!$result) {
  // Query error
  die("Error: " . mysqli_error($conn));
}

if (mysqli_num_rows($result) > 0) {
  // Product is already in wishlist
  echo 'Product is already in your wishlist.';
} else {
  // Add product to wishlist
  $insertQuery = "INSERT INTO Wishlist_EEA (wishlistItemID, UserID, ProductID) VALUES ($wishlistItemID, $userID, $productID)";
  $insertResult = mysqli_query($conn, $insertQuery);
  if ($insertResult) {
    echo 'Product has been added to your wishlist.';
  } else {
    echo 'Error adding product to wishlist.';
  }
}

header('Location: wishlist.php');
?>
