<!DOCTYPE html>
<html>
<head>
<title>Product Card</title>
    <link rel="stylesheet" href="../CSS/search.css" />
    <link rel="stylesheet" href="../CSS/recipe-card.css" />
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="../CSS/back-home.css">
    <link rel="stylesheet" type="text/css" href="../CSS/category-list.css">
</head>
<body>
  <?php
require_once('home-back.php');
?>
<?php
session_start();
require_once('connection.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.html');
    exit();
}

if (isset($_POST['Category'])) {
    $Category = $_POST['Category'];

    // Select products from Product_EEA table based on the selected category
    $sql = "SELECT * FROM Product_EEA WHERE Category = '$Category'";
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
            echo '<form action="product_detail.php" method="get">';
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
}
?>

