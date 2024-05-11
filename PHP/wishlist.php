<!DOCTYPE html>
<html>
<head>
  <title>Wishlist</title>
  <link rel="stylesheet" href="../CSS/search.css" />
  <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" type="text/css" href="../CSS/back-home.css">
</head>
<body>
  <?php
  require_once('home-back.php');
  ?>
  <?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  // Start session
  session_start();
  require_once('connection.php');

  // Check if user is logged in
  if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.html');
    exit();
  }

  // Get user id from session
  $user_id = $_SESSION['user_id'];

  // Get wishlist items for the user
  $query = "SELECT * FROM Wishlist_EEA WHERE UserID = $user_id";
  $result = mysqli_query($conn, $query);
  if (!$result) {
    // Query error
    die("Error: " . mysqli_error($conn));
  }

  if (mysqli_num_rows($result) > 0) {
    // Display wishlist items
    while ($row = mysqli_fetch_assoc($result)) {
      $productID = $row['ProductID'];
      
      // Fetch product details from products table
      $productQuery = "SELECT * FROM Product_EEA WHERE ProductID = $productID";
      $productResult = mysqli_query($conn, $productQuery);
      if ($productResult && mysqli_num_rows($productResult) > 0) {
        // Loop through results and display product cards
        while ($row = mysqli_fetch_assoc($productResult)) {
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
            echo '<form action="delete_favourites.php" method="get">';
            echo '<input type="hidden" name="ProductID" value="'.$ProductID.'">';
            echo '<button type="submit">Remove from Favourites</button>';
            echo '</form>';
            echo '</div>';
        }
    } else {
        echo "No products found.";
    }
    }
  } else {
    echo 'Your wishlist is empty.';
  }
  ?>
</body>
</html>
