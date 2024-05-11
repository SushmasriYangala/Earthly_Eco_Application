<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once("connection.php");

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Check if the cart item ID and quantity are set
if (!isset($_POST['cartItemID']) || !isset($_POST['quantity'])) {
    echo "CartItemID or quantity is not set.";
    exit();
}

$CartItemID = $_POST['cartItemID'];
$newQuantity = $_POST['quantity'];

// Debugging: Print received data
echo "Received cartItemID: " . $CartItemID . "<br>";
echo "Received new quantity: " . $newQuantity . "<br>";

if (is_numeric($newQuantity) && $newQuantity >= 0) {
    $query = "UPDATE CartItem_EEA SET Quantity = ? WHERE CartItemID = ?";
    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) {
        echo "Error preparing statement: " . mysqli_error($conn);
        exit();
    }

    mysqli_stmt_bind_param($stmt, 'ii', $newQuantity, $CartItemID);

    if (!mysqli_stmt_execute($stmt)) {
        echo "Error executing statement: " . mysqli_stmt_error($stmt);
        exit();
    }

    // If you want to redirect after successful update, you can do it here
    header('Location: cart.php');
    exit();
} else {
    echo "Error: Quantity must be a non-negative number.";
    exit();
}
?>
