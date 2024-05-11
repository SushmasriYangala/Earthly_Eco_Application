<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.html');
    exit();
}

require_once("connection.php");

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate form data
    $feedbackType = $_POST['FeedbackType'];
    $feedbackText = $_POST['FeedbackText'];

    // Retrieve the maximum FeedbackID from the database
    $result = mysqli_query($conn, "SELECT MAX(FeedbackID) AS maxFeedbackID FROM UserFeedback_EEA");
    $row = mysqli_fetch_assoc($result);
    $maxFeedbackID = $row['maxFeedbackID'];
 
    // Generate a new FeedbackID by incrementing the maximum FeedbackID
    $feedbackID = $maxFeedbackID + 1;

    // Get user ID from session
    $userID = $_SESSION['user_id'];

    // Prepare and execute SQL query to insert into UserFeedback_EEA table
    $sql = "INSERT INTO UserFeedback_EEA (FeedbackID, UserID, FeedbackType, FeedbackText, Timestamp) VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiss", $feedbackID, $userID, $feedbackType, $feedbackText);

    if ($stmt->execute()) {
        // Feedback inserted successfully
        echo "Feedback submitted successfully.";
    } else {
        // Error handling
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
