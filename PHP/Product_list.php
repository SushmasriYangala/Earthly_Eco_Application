<!DOCTYPE html>
<html>
<head>
    <title>Product Search</title>
    <link rel="stylesheet" href="../CSS/search.css" />
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="../CSS/back-home.css">
</head>
<body>
<?php
    session_start();
    require_once("connection.php");
    require_once('Supplierhhome-back.php');

    // Check if user is logged in
    if (!isset($_SESSION['supplier_id'])) {
        header('Location: ../Supplierlogin.html');
        exit();
    }

    // Retrieve the supplier's ID from the session
    $supplier_id = $_SESSION['supplier_id'];

    // Retrieve products associated with the supplier
    $sql = "SELECT p.* FROM Product_EEA p
            JOIN SupplierProduct_EEA sp ON p.ProductID = sp.ProductID
            WHERE sp.SupplierID = $supplier_id";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        // Loop through results and display product cards
        while ($row = mysqli_fetch_assoc($result)) {
            $ProductID = $row['ProductID'];
            $name = $row['Name'];
            $description = $row['Description'];
            $price = $row['Price'];
            $image = $row['Image'];

            echo '<div class="eco-card">';
            echo '<img src="'.$image.'" alt="'.$name.'">';
            echo '<h2>'.$name.'</h2>';
            echo '<p>Price: $'.$price.'</p>';
            echo '<form action="product_details.php" method="get">';
            echo '<input type="hidden" name="ProductID" value="'.$ProductID.'">';
            echo '<button type="submit">View Product</button>';
            echo '</form>';
            echo '</div>';
        }
    } else {
        echo "No products found.";
    }

    // Close database connection
    mysqli_close($conn);
?>
</body>
</html>
