<?php
include("connection.php");

if (isset($_POST['Email'])) {
    $email = $_POST['Email'];

    // Check if the email exists in the database
    $stmt = $conn->prepare("SELECT * FROM User_EEA WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // If the email exists, redirect to update password page
    if ($result->num_rows > 0) {
        // Redirect to update password page
        header("Location: ../changepassword.html");
        exit;
    } else {
        // Email not found in database
        echo "Email not found";
    }
}

mysqli_close($conn);
?>
