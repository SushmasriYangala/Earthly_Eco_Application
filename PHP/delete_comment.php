<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once('connection.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
  header('Location: ../login.html');
  exit();
}

// Get the comment ID and product ID
$ReviewID = $_GET['ReviewID'];
$ProductID = $_GET['ProductID'];

// Check if the logged-in user is the owner of the comment
$sql = "SELECT UserID FROM Review_EEA WHERE ReviewID = '$ReviewID'";
$result = mysqli_query($conn, $sql);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $comment_user_id = $row['UserID'];

    // Check if the logged-in user is the owner of the comment
    if ($_SESSION['user_id'] == $comment_user_id) {
        // Delete the comment
        $sql_delete = "DELETE FROM Review_EEA WHERE ReviewID = '$ReviewID'";
        $result_delete = mysqli_query($conn, $sql_delete);

        if ($result_delete) {
            // Redirect back to the product detail page
            header("Location: product_detail.php?ProductID=$ProductID");
            exit();
        } else {
            // Error occurred while deleting the comment
            echo 'Error: ' . mysqli_error($conn);
            exit();
        }
    } else {
        // User is not authorized to delete this comment
        echo 'You are not authorized to delete this comment.';
        exit();
    }
} else {
    // Error occurred while fetching the comment
    echo 'Error: ' . mysqli_error($conn);
    exit();
}
?>
