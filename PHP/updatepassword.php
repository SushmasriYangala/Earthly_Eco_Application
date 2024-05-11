<?php
session_start();
require_once('connection.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
  header('Location: ../login.html');
  exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the old password from the form
    $oldPassword = $_POST['password'];

    // Retrieve the user ID from the session or any other method you are using to identify the user
    $userId = $_SESSION['user_id'];

    // Retrieve the current password from the database
    $sql = "SELECT password_hash FROM users WHERE user_id = $userId";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    // Verify if the old password matches the current password
    if (password_verify($oldPassword, $row['password_hash'])) {
        // Passwords match, proceed to change password
        header("Location: ../changepassword.html");
        exit();
    } else {
        echo "Old password does not match.";
        // You can add further code here if the passwords don't match
        header("Location: ../updatepassword.html");
        exit();
    }
}
?>
