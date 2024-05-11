<?php
include("connection.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST['Password'];
    $confirmPassword = $_POST['confirmpassword'];

    // Validate password and confirm password
    if ($password != $confirmPassword) {
        echo "Error: Passwords do not match.";
        exit;
    }

    // Retrieve the user ID from the session or any other method you are using to identify the user
    $userId = $_SESSION['user_id'];

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Update the user's password in the database
    $sql = "UPDATE User_EEA SET Password = '$hashedPassword' WHERE UserID = $userId";
    if (mysqli_query($conn, $sql)) {
        // Redirect to login page after successfully updating password
        header('Location: ../login.html');
        exit;
    } else {
        // If update fails, redirect back to the change password page
        header('Location: ../changepassword.html');
        exit;
    }
}
?>
