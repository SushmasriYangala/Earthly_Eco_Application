<!DOCTYPE html>
<html lang="en">

<head>
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="../CSS/search.css" />
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="../CSS/back-home.css">
    <link rel="stylesheet" type="text/css" href="../CSS/cart.css">
</head>

<body>
    <?php require_once('home-back.php'); ?>
    <div class="container">
        <h1>Shopping Cart</h1>
        <form action="process_checkout.php" method="post">
            <div class="cart-items">
                <?php
                // Start session
                error_reporting(E_ALL);
                ini_set('display_errors', 1);
                session_start();
                require_once("connection.php");

                // Check if user is logged in
                if (!isset($_SESSION['user_id'])) {
                    // Redirect to login page
                    header('Location: login.php');
                    exit();
                }

                // Get user ID from session
                $userID = $_SESSION['user_id'];

                // Retrieve cart items for the current user
                $query = "SELECT c.CartItemID, p.Name, p.Price, p.Image, c.Quantity
                        FROM CartItem_EEA c
                        JOIN Product_EEA p ON c.ProductID = p.ProductID
                        WHERE c.UserID = $userID";
                $result = mysqli_query($conn, $query);

                if (!$result) {
                    // Query error
                    die("Error: " . mysqli_error($conn));
                }

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $CartItemID = $row['CartItemID'];
                        $productName = $row['Name'];
                        $price = $row['Price'];
                        $quantity = $row['Quantity'];
                        $totalPrice = $price * $quantity;
                        $image_path = $row['Image'];

                        echo '<div class="cart-item">';
                        echo '<div id="eco-image"><img src="' . $image_path . '" alt="' . $productName . '"></div>';
                        echo '<span class="item-name">Name: ' . $productName . '</span>';
                        echo '<span class="item-price">Original Price: $' . $price . '</span>';
                        echo '<div class="quantity-container">';
                        echo '<form action="update_cart.php" method="post">';
                        echo '<input type="hidden" name="cartItemID" value="' . $CartItemID . '">';
                        echo '<input type="number" class="item-quantity" name="quantity" value="' . $quantity . '" min="1">'; 
                        echo '<span class="item-total">Total Price: $' . $totalPrice . '</span>';
                        echo '<button type="submit" name="updateCart">Update</button>';
                        echo '</form>';
                        echo '<form action="remove_from_cart.php" method="post">';
                        echo '<input type="hidden" name="cartItemID" value="' . $CartItemID . '">';
                        echo '<button type="submit">Remove</button>';
                        echo '</form>';
                        echo '<input type="checkbox" name="selectedProducts[]" value="' . $CartItemID . '"> Select for Checkout';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>Your shopping cart is empty.</p>';
                }
                ?>
                
               <button type="submit" name="checkout">Checkout Selected Items</button>
            </div>
        </form>
    </div>
</body>

</html>
