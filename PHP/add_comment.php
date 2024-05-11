<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once('connection.php');
require_once('home-back.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.html');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['ProductID'], $_POST['Comment'])) {
        // Sanitize and validate input
        $productID =  $_POST['ProductID'];
        $Comment = $_POST['Comment'];
        $Rating = intval($_POST['Rating']);

        // Map the rating from right to left
        $mappedRating = 6 - $rating;

        // Insert the new comment into the database
        $userID = $_SESSION['user_id'];

        // Retrieve the maximum ReviewID from the database
        $result = mysqli_query($conn, "SELECT MAX(ReviewID) AS maxReviewID FROM Review_EEA");
        $row = mysqli_fetch_assoc($result);
        $maxReviewID = $row['maxReviewID'];

        // Generate a new ReviewID by incrementing the maximum ReviewID
        $reviewID = $maxReviewID + 1;

        // Now you can use $reviewID in your INSERT statement
        $sql = "INSERT INTO Review_EEA (ReviewID, UserID, ProductID, Comment, Rating) VALUES ($reviewID, $userID, $productID, '$Comment', $Rating)";


        $result = mysqli_query($conn, $sql);

        if ($result) {
            // Comment and rating successfully added, redirect back to the product page
            header("Location: product_detail.php?ProductID=$productID");
            exit();
        } else {
            // Error occurred while inserting the comment and rating
            echo 'Error: ' . mysqli_error($conn);
            exit(); // Ensure script stops execution after encountering an error
        }
    } else {
        // Required parameters not set
        echo 'Error: ProductID, comment_text, and rating are required.';
        exit(); // Ensure script stops execution after encountering an error
    }
} else {
    // Method other than POST used to access the page
    echo 'Error: Invalid request method.';
    exit(); // Ensure script stops execution after encountering an error
}
?>