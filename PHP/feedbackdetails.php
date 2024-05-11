
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./CSS/feedbackdetails.css">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" type="text/css" href="../CSS/back-home.css">
</head>

<body><?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../Adminlogin.html');
    exit();
}

require_once("connection.php");
require_once("Adminhome-back.php");

// Retrieve feedback data along with usernames
$sql = "SELECT uf.FeedbackID, uf.UserID, u.Username, uf.FeedbackType, uf.FeedbackText, uf.Timestamp 
        FROM UserFeedback_EEA uf
        INNER JOIN User_EEA u ON uf.UserID = u.UserID";
$result = mysqli_query($conn, $sql);

// Check if there are any feedback records
if (mysqli_num_rows($result) > 0) {
    // Display feedback in a table
    echo "<h2>All Feedback</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Feedback ID</th><th>User ID</th><th>Username</th><th>Feedback Type</th><th>Feedback Text</th><th>Timestamp</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>".$row['FeedbackID']."</td>";
        echo "<td>".$row['UserID']."</td>";
        echo "<td>".$row['Username']."</td>";
        echo "<td>".$row['FeedbackType']."</td>";
        echo "<td>".$row['FeedbackText']."</td>";
        echo "<td>".$row['Timestamp']."</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No feedback available.";
}

// Close connection
mysqli_close($conn);
?>

</body>

</html>