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
        if (isset($_GET['selectedProducts']) && !empty($_GET['selectedProducts'])) {
            // Retrieve selected products from URL parameter
            $selectedProducts = $_GET['selectedProducts'];
        } else {
            // Handle case when selectedProducts is not set or empty
            // You may redirect the user to a previous page or display an error message
            echo "No products selected.";
            exit();
        }

        // Counter for PaymentMethodID
        $PaymentMethod_counter_result = mysqli_query($conn, "SELECT MAX(PaymentMethodID) AS maxPaymentMethodID FROM PaymentMethod_EEA");
        $PaymentMethod_counter_row = mysqli_fetch_assoc($PaymentMethod_counter_result);
        $PaymentMethodID = $PaymentMethod_counter_row['maxPaymentMethodID'] + 1;
        
        // Insert payment details into PaymentMethod_EEA table
        $insertPaymentQuery = "INSERT INTO PaymentMethod_EEA (PaymentMethodID, UserID, Type, Details)
                               VALUES ($PaymentMethodID, $userID, '$paymentType', '$paymentDetails')";
        $paymentResult = mysqli_query($conn, $insertPaymentQuery);
        
        if (!$paymentResult) {
            // Error inserting payment details
            die("Error inserting payment details: " . mysqli_error($conn));
        }
        
        // Set session variable to indicate payment details were successfully added
        $_SESSION['payment_added'] = true;
        
        // Redirect to checkout confirmation page with selectedProducts parameter
        header('Location: checkout_confirmation.php?selectedProducts=' . urlencode($selectedProducts));
        exit();
    } else {
        // Payment details not set, redirect back to PaymentMethod confirmation page
        header('Location: PaymentMethod_confirmation.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Method</title>
</head>
<body>
    <h1>Payment Method</h1>
    <?php
    // Check if selectedProducts is set and not empty
    if (isset($_GET['selectedProducts']) && !empty($_GET['selectedProducts'])) {
        // Retrieve selected products from URL parameter
        $selectedProducts = $_GET['selectedProducts'];
        // Include selectedProducts parameter in the form action URL
        $form_action = "add_Payment.php?selectedProducts=" . urlencode($selectedProducts);
    } else {
        // If selectedProducts is not set or empty, set form action without it
        $form_action = "add_Payment.php";
    }
    ?>
    <form method="post" action="<?php echo $form_action; ?>">
        <label for="payment_type">Payment Type:</label><br>
        <input type="text" id="payment_type" name="payment_type"><br>
        <label for="payment_details">Payment Details:</label><br>
        <textarea id="payment_details" name="payment_details"></textarea><br><br>
        
        <!-- Hidden input fields for selected products -->
        <?php if(isset($_POST['selectedProducts'])): ?>
            <?php foreach ($_POST['selectedProducts'] as $product) : ?>
                <input type="hidden" name="selectedProducts[]" value="<?php echo $product; ?>">
            <?php endforeach; ?>
        <?php endif; ?>
        
        <input type="submit" value="Submit">
    </form>
</body>
</html>
