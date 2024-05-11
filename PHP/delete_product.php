<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Check if the user is logged in
if (!isset($_SESSION['supplier_id'])) {
  header('Location: ../Supplierlogin.html');
  exit();
}

// Check if ProductID is provided in the URL
if (!isset($_GET['ProductID'])) {
  header('Location: ./product_details.php'); // Redirect to an error page if ProductID is not provided
  exit();
}

require_once('connection.php');

// Get the ProductID from the URL
$ProductID = $_GET['ProductID'];

// Delete related records from Inventory_EEA table
$sql_delete_inventory = "DELETE FROM Inventory_EEA WHERE ProductID = $ProductID";
if (mysqli_query($conn, $sql_delete_inventory)) {
  // Now delete the product from Product_EEA table
  $sql = "DELETE FROM Product_EEA WHERE ProductID = $ProductID";
  if (mysqli_query($conn, $sql)) {
    // Product deleted successfully
    header('Location: ./Product_list.php'); // Redirect to the product list page
    exit();
  } else {
    // Error occurred while deleting the product
    echo "Error deleting product: " . mysqli_error($conn);
  }
} else {
  // Error occurred while deleting related records
  echo "Error deleting related records: " . mysqli_error($conn);
}

?>
