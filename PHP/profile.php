<!DOCTYPE html>
<html>
<head>
  <title>Profile Page</title>
  
  <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" type="text/css" href="../CSS/back-home.css">
  <link rel="stylesheet" type="text/css" href="../CSS/profile.css">
</head>
<body> 
<?php
session_start();
require_once("connection.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.html');
    exit();
}
require_once('home-back.php');
?>
	<header>
		<h1>My Profile</h1>
	</header>

	<section>
		<h2>Profile Information</h2>
    <?php
  $user_id = $_SESSION['user_id'];
  $sql = "SELECT * FROM User_EEA WHERE UserID = $user_id";
  $result = mysqli_query($conn, $sql);
  if ($result) {
      $row = mysqli_fetch_assoc($result);
      $Username = $row['Username'];
      $email = $row['Email'];
      $address = $row['Address']; // Assuming 'Address' is a field in your database
  }
  
    ?>
		<p><strong>Username:</strong><?php echo  $Username; ?></p>
		<p><strong>Email:</strong><?php echo  $email; ?></p>
    <p><strong>Address:</strong><?php echo $address; ?></p>
		<div class="buttons-wrapper">
			<button onclick="window.location.href='../editdetails.html'; return false;">Edit Username</button>
			<button onclick="window.location.href='../updatepassword.html'; return false;">Update Password</button>
		</div>
	</section>

	<footer>
		<p>&copy; 2024 Earthly Eco</p>
	</footer>
</body>
</html>