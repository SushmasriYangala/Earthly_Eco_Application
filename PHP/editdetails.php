<!DOCTYPE html>
<html>
<head>
  <title>Profile Page</title>
  
  <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" type="text/css" href="../CSS/back-home.css">
  <link rel="stylesheet" type="text/css" href="../CSS/profile.css">
</head>
<body> 
<?php
session_start();
require_once("connection.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.html');
    exit();
}
require_once('home-back.php');
$user_id = $_SESSION['user_id'];
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the required fields are not empty
    if (!empty($_POST['Username']) && !empty($_POST['Address'])) {
        // Process form data
        $username = $_POST['Username'];
        $address = $_POST['Address'];
        // Prepare SQL update query
        $sql = "UPDATE User_EEA SET username='$username', address='$address' WHERE UserID = $user_id";
        // Execute the query
    if (mysqli_query($conn, $sql)) {
        // Redirect to profile.php after successful update
        header('Location: ./profile.php');
        exit;
    } else {
        // Error handling
        echo "Error: " . mysqli_error($conn);
    }
    } else {
        // If required fields are empty, show an error message
        echo "Please fill all required fields.";
    }
}
?>

</body>
</html>