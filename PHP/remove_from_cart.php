<?php
session_start();
require_once("connection.php");

if (isset($_POST['cartItemID'])) {
    $cartItemID = $_POST['cartItemID'];

    // Remove the item from the cart
    $query = "DELETE FROM CartItem_EEA WHERE CartItemID = $cartItemID";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        // Query error
        die("Error: " . mysqli_error($conn));
    }

    // Redirect back to the cart page
    header('Location: cart.php');
    exit();
} else {
    // Redirect if cart item ID is not set
    header('Location: cart.php');
    exit();
}
?>
