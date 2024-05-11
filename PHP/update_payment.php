<?php
// Start session
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page
    header('Location: login.php');
    exit();
}

// Include database connection
require_once("connection.php");

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if payment details are set
    if (isset($_POST['payment_type']) && isset($_POST['payment_details'])) {
        // Get user ID from session
        $userID = $_SESSION['user_id'];
        
        // Get payment details from form
        $paymentType = $_POST['payment_type'];
        $paymentDetails = $_POST['payment_details'];
        
        // Check if selectedProducts is set and not empty
        if (isset($_POST['selectedProducts']) && !empty($_POST['selectedProducts'])) {
            // Retrieve selected products from form
            $selectedProducts = $_POST['selectedProducts'];
        } else {
            // Handle case when selectedProducts is not set or empty
            // You may redirect the user to a previous page or display an error message
            echo "No products selected.";
            exit();
        }

        // Update payment details in PaymentMethod_EEA table
        $updatePaymentQuery = "UPDATE PaymentMethod_EEA 
                               SET Type = '$paymentType', Details = '$paymentDetails' 
                               WHERE UserID = $userID";
        $paymentResult = mysqli_query($conn, $updatePaymentQuery);
        
        if (!$paymentResult) {
            // Error updating payment details
            die("Error updating payment details: " . mysqli_error($conn));
        }
        
        // Set session variable to indicate payment details were successfully updated
        $_SESSION['payment_updated'] = true;
        
        // Redirect to checkout confirmation page with selectedProducts parameter
        header('Location: checkout_confirmation.php?selectedProducts=' . urlencode($selectedProducts));
        exit();
    } else {
        // Payment details not set, redirect back to PaymentMethod confirmation page
        header('Location: PaymentMethod_confirmation.php');
        exit();
    }
}

// Retrieve user's payment details
$userID = $_SESSION['user_id'];
$query_payment = "SELECT * FROM PaymentMethod_EEA WHERE UserID = $userID";
$result_payment = mysqli_query($conn, $query_payment);
$payment_info = mysqli_fetch_assoc($result_payment);

// Retrieve selected products from URL parameter
$selectedProducts = isset($_GET['selectedProducts']) ? $_GET['selectedProducts'] : '';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Payment</title>
</head>
<body>
    <h1>Update Payment</h1>
    <form method="post" action="update_payment.php">
        <label for="payment_type">Payment Type:</label><br>
        <input type="text" id="payment_type" name="payment_type" value="<?php echo $payment_info['Type']; ?>"><br>
        <label for="payment_details">Payment Details:</label><br>
        <textarea id="payment_details" name="payment_details"><?php echo $payment_info['Details']; ?></textarea><br><br>
        
        <!-- Hidden input fields for selected products -->
        <input type="hidden" name="selectedProducts" value="<?php echo $selectedProducts; ?>">
        
        <input type="submit" value="Update">
    </form>
</body>
</html>
