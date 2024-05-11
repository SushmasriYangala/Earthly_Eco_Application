<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/fontawesome.css">
  <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" type="text/css" href="../CSS/eco_list.css">
  <link rel="stylesheet" type="text/css" href="../CSS/Rating.css">
  <link rel="stylesheet" type="text/css" href="../CSS/back-home.css">
  <title>Products</title>
</head>

<body>

  <?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
  session_start();
  require_once('connection.php');
  require_once('home-back.php');

  if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.html');
    exit();
  }

  // Retrieve product details based on ProductID
  $ProductID = $_GET['ProductID'];
  $sql = "SELECT * FROM Product_EEA WHERE ProductID = $ProductID";
  $result = mysqli_query($conn, $sql);

  if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $name = $row['Name'];
    $description = $row['Description'];
    $price = $row['Price'];
    $category = $row['Category'];
    $image_path = $row['Image'];
    // If you want to retrieve additional details, add them here

    echo '<div id="card-container">';
    echo '<div id="card-title">' . $name . '</div>';
    echo '<div id="eco-image"><img src="' . $image_path . '" alt="' . $name . '"></div>';
    echo '<div id="card-title-time">Category: <span class="detail-value-time">' . $category . '</div>';
    echo '<div id="details">Description: <span class="detail-value">' . $description . '</span></div>';
    echo '<div id="details">Price: <span class="detail-value">$' . $price . '</span></div>';
    echo '<div id="card-items">';
    echo '<div class="button-container">';
    echo '<form action="add_to_cart.php" method="POST">';
    echo '<input type="hidden" name="ProductID" value="' . $ProductID . '">';
    echo '<button type="submit" name="add_to_list">Add to Cart</button>';
    echo '</form>';
    echo '<form action="add_to_wishlist.php" method="POST">';
    echo '<input type="hidden" name="ProductID" value="' . $ProductID . '">';
    echo '<button type="submit" name="add_to_list">Add to Wishlist</button>';
    echo '</form>'; 
    echo '</div>';       
    echo '</div>';

  // Fetch comments from the database
  $sql = "SELECT * FROM Review_EEA WHERE ProductID = $ProductID ";
  $result = mysqli_query($conn, $sql);
  if ($result) {
    // Form for adding a comment
    // Form for adding a comment and rating
    echo '<form action="add_comment.php" method="POST">';
    echo '<div class="rating">';
    echo '<input type="radio" id="star1" name="Rating" value="1"><label for="star1"><i class="fas fa-star"></i></label>';
    echo '<input type="radio" id="star2" name="Rating" value="2"><label for="star2"><i class="fas fa-star"></i></label>';
    echo '<input type="radio" id="star3" name="Rating" value="3"><label for="star3"><i class="fas fa-star"></i></label>';
    echo '<input type="radio" id="star4" name="Rating" value="4"><label for="star4"><i class="fas fa-star"></i></label>';
    echo '<input type="radio" id="star5" name="Rating" value="5"><label for="star5"><i class="fas fa-star"></i></label>';
    echo '</div>';
    echo '<input type="hidden" name="ProductID" value="' . $ProductID . '">'; // Hidden input for ProductID
    echo '<textarea name="Comment" placeholder="Add a comment"></textarea>';
    echo '<button type="submit" name="submit_comment">Submit</button>';
    echo '</form>';


    // Fetch comments from the database
$sql = "SELECT * FROM Review_EEA WHERE ProductID = $ProductID ";
$result = mysqli_query($conn, $sql);
if ($result && mysqli_num_rows($result) > 0) {
    // Loop through each comment
    while ($row = mysqli_fetch_assoc($result)) {
        $Comment = $row['Comment'];
        $UserID = $row['UserID']; 
        $ReviewID = $row['ReviewID'];
        $Rating = $row['Rating'];
        
        // Fetch username associated with the comment user ID from User_EEA table
        $user_query = "SELECT username FROM User_EEA WHERE UserID = $UserID";
        $user_result = mysqli_query($conn, $user_query);
        $user_row = mysqli_fetch_assoc($user_result);
        $username = $user_row['username'];

        // Display the comment
        echo '<div class="comment">';
        echo '<div class="comment-header">' . $username . '</div>';
        echo '<div class="comment-text">' . $Comment . '</div>';
        echo '<div class="comment-rating">Rating: ' . $Rating . '</div>';
        echo '<form action="" method="POST">';
        echo '<a class="delete_comment" href="delete_comment.php?ProductID=' . $ProductID . '&ReviewID=' . $ReviewID . '" onclick="return confirm(\'Are you sure you want to delete this comment?\')">Delete</a>';
        echo '</form>';
        echo '</div>';
    }
} else {
    echo 'No comments yet.<br>';
}

  }
  } 
  
  ?>
<!-- Copy link button -->
<button onclick="copyToClipboard()"><i class='bx bx-copy-alt' ></i>Copy link</button>

<!-- Social media shareable link -->
<a class ="edit" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" target="_blank"><i class='bx bxl-facebook-circle' ></i>Share on Facebook</a>
<a class ="edit" href="https://twitter.com/intent/tweet?url=<?php echo urlencode('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" target="_blank"><i class='bx bxl-twitter'></i>Share on Twitter</a>
<a class ="edit" href="https://www.instagram.com/?url=<?php echo urlencode('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" target="_blank"><i class='bx bxl-instagram' ></i>Share on Instagram</a>



<script>
  function copyToClipboard() {
    var text = window.location.href;
    navigator.clipboard.writeText(text).then(function() {
      alert("Link copied to clipboard");
    }, function() {
      alert("Failed to copy link");
    });
  }
</script>

</body>

</html>