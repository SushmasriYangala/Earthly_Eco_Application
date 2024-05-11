<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include("connection.php");

// Check if form is submitted
if (isset($_POST['signin'])) {
    // Extract form data
    $Username = $_POST['Username'];
    $Email = $_POST['Email'];
    $Password = $_POST['Password'];
    $ConfirmPassword = $_POST['confirmpassword'];

    // Validate form data
    if ($Password !== $ConfirmPassword) {
        echo "<script>alert('Passwords do not match!');</script>";
        echo "<script>window.location.href='../Adminsignup.html';</script>";
        exit;
    }

    // Hash the password
    $password_hash = password_hash($Password, PASSWORD_DEFAULT);

    // Retrieve the maximum AdminID from the database
    $result = mysqli_query($conn, "SELECT MAX(AdminID) AS maxAdminID FROM Admin_EEA");
    $row = mysqli_fetch_assoc($result);
    $maxAdminID = $row['maxAdminID'];

    // Generate a new AdminID by incrementing the maximum AdminID
    $AdminID = $maxAdminID + 1;
    
    // Insert user data into the database
    $sql = "INSERT INTO Admin_EEA (AdminID, Username, Email, Password)
            VALUES ($AdminID, '$Username', '$Email', '$password_hash')";

    if (mysqli_query($conn, $sql)) {
        // Redirect to login page
        header("Location: ../login.html");
        exit;
    } else {
        // Display error message if query fails
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    // Close database connection
    mysqli_close($conn);
}
?>
