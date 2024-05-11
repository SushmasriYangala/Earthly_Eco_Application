<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/fontawesome.css">
  <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" type="text/css" href="../CSS/eco_list.css">
  <link rel="stylesheet" type="text/css" href="../CSS/Rating.css">
  <link rel="stylesheet" type="text/css" href="../CSS/back-home.css">
  <title>Products</title>
</head>
<body>

<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once('connection.php');
require_once('Supplierhhome-back.php');

// Check if user is logged in
if (!isset($_SESSION['supplier_id'])) {
    header('Location: ../Supplierlogin.html');
    exit();
}

// Retrieve product details based on ProductID
if (!isset($_GET['ProductID'])) {
    // Redirect or handle error if ProductID is not provided
    header('Location: ./Product_list.php');
    exit();
} 

$ProductID = $_GET['ProductID'];

// SQL query to retrieve product details
$sql_product = "SELECT * FROM Product_EEA WHERE ProductID = $ProductID";
$result_product = mysqli_query($conn, $sql_product);

if ($result_product && mysqli_num_rows($result_product) > 0) {
    $row = mysqli_fetch_assoc($result_product);
    $name = $row['Name'];
    $description = $row['Description'];
    $price = $row['Price'];
    $category = $row['Category'];
    $image_path = $row['Image'];

    // SQL query to retrieve quantity from Inventory_EEA table
    $sql_inventory = "SELECT Inventory_EEA.Quantity FROM Product_EEA JOIN Inventory_EEA ON Product_EEA.ProductID = Inventory_EEA.ProductID WHERE Product_EEA.ProductID = $ProductID";
    $result_inventory = mysqli_query($conn, $sql_inventory);
    $quantity = 0; // Default quantity if not found

    if ($result_inventory && mysqli_num_rows($result_inventory) > 0) {
        $row_inventory = mysqli_fetch_assoc($result_inventory);
        $quantity = $row_inventory['Quantity'];
    }

    echo '<div id="card-container">';
    echo '<div id="card-title">' . $name . '</div>';
    echo '<div id="eco-image"><img src="' . $image_path . '" alt="' . $name . '"></div>';
    echo '<div id="card-title-time">Category: <span class="detail-value-time">' . $category . '</div>';
    echo '<div id="details">Description: <span class="detail-value">' . $description . '</span></div>';
    echo '<div id="details">Price: <span class="detail-value">$' . $price . '</span></div>';
    echo '<div id="details">Quantity: <span class="detail-value">' . $quantity . '</span></div>';
    // Display edit and delete options
    echo '<div id="edit-delete-container">';
    echo '<a class="edit" href="edit_product.php?ProductID=' . $ProductID . '">Edit the Product</a><br>';
    echo '<a class="delete" href="delete_product.php?ProductID=' . $ProductID . '">Delete the Product</a>';
    echo '</div>';
} else {
    // Handle case where product with provided ProductID is not found
    echo "Product not found";
}
?>

</body>
</html>