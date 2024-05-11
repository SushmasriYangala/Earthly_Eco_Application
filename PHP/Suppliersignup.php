<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include("connection.php");

// Check if form is submitted
if (isset($_POST['signin'])) {
    // Extract form data
    $Name = $_POST['Name'];
    $Email = $_POST['Email'];
    $Password = $_POST['Password'];
    $ConfirmPassword = $_POST['confirmpassword'];

    // Validate form data
    if ($Password !== $ConfirmPassword) {
        echo "<script>alert('Passwords do not match!');</script>";
        echo "<script>window.location.href='../Suppliersignup.html';</script>";
        exit;
    }

    // Hash the password
    $password_hash = password_hash($Password, PASSWORD_DEFAULT);

    // Retrieve the maximum SupplierID from the database
    $result = mysqli_query($conn, "SELECT MAX(SupplierID) AS maxSupplierID FROM Supplier_EEA");
    $row = mysqli_fetch_assoc($result);
    $maxSupplierID = $row['maxSupplierID'];

    // Generate a new SupplierID by incrementing the maximum SupplierID
    $SupplierID = $maxSupplierID + 1;
    
    // Insert user data into the database
    $sql = "INSERT INTO Supplier_EEA (SupplierID, Name, Email, Password)
            VALUES ($SupplierID, '$Name', '$Email', '$password_hash')";

    if (mysqli_query($conn, $sql)) {
        // Redirect to login page
        header("Location: ../Supplierlogin.html");
        exit;
    } else {
        // Display error message if query fails
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    // Close database connection
    mysqli_close($conn);
}
?>
