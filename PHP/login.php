<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once("connection.php");

if (isset($_POST['login'])) {
    $Email = $_POST['Email'];
    $Password = $_POST['Password'];

    // Prepare and execute the SQL query to select user data based on email
    $stmt = $conn->prepare("SELECT * FROM User_EEA WHERE Email = ?");
    $stmt->bind_param("s", $Email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user with provided email exists
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        // Verify password
        if (password_verify($Password, $row['Password'])) {
            // Start session and store user ID
            $_SESSION['user_id'] = $row['UserID'];
            // Redirect to welcome page
            header('Location: ../Welcome.html');
            exit;
        }
    }

    // If email or password does not match, redirect to login page
    echo "<script>alert('Incorrect email or password');</script>";
    header('Location: ../login.html');
    exit;
}
?>
