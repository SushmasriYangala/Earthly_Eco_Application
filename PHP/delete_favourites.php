<?php
// Start session
session_start();
require_once('connection.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
  header('Location: ../login.html');
  exit();
}

// Get user id from session
$user_id = $_SESSION['user_id'];

// Check if ProductID is set in the GET request
if (isset($_GET['ProductID'])) {
  // Get ProductID from GET
  $product_id = $_GET['ProductID'];

  // Delete the product from the Wishlist
  $deleteQuery = "DELETE FROM Wishlist_EEA WHERE UserID = $user_id AND ProductID = $product_id";
  $deleteResult = mysqli_query($conn, $deleteQuery);

  if ($deleteResult) {
    // Product removed successfully
    header('Location: wishlist.php');
    exit();
  } else {
    // Error removing product
    $error_message = "Error removing product: " . mysqli_error($conn);
  }
} else {
  // Redirect to the wishlist page if ProductID is not set
  header('Location: wishlist.php');
  exit();
}
?>