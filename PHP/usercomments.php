<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if (!isset($_SESSION['supplier_id'])) {
    header('Location: ../supplierlogin.html');
    exit();
}

require_once("connection.php");
require_once("Supplierhhome-back.php");

// Retrieve supplier ID from session
$supplierID = $_SESSION['supplier_id'];

// Retrieve comments for products associated with the supplier
$sql = "SELECT r.Comment, r.Rating, p.Name
        FROM Review_EEA r
        INNER JOIN Product_EEA p ON r.ProductID = p.ProductID
        INNER JOIN SupplierProduct_EEA sp ON p.ProductID = sp.ProductID
        WHERE sp.SupplierID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $supplierID);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="../CSS/back-home.css">
    <title>User Comments</title>
    <style>
         body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: left;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>User Comments</h2>
    <table>
        <tr>
            <th>Product</th>
            <th>Comment</th>
            <th>Rating</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['Name'] . '</td>';
                echo '<td>' . $row['Comment'] . '</td>';
                echo '<td>' . $row['Rating'] . '</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="3">No comments available.</td></tr>';
        }
        ?>
    </table>
</div>


</body>
</html>

<?php
// Close statement and connection
$stmt->close();
$conn->close();
?>
