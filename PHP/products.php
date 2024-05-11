<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("connection.php");
require_once("Adminhome-back.php");

// Retrieve products along with supplier names
$sql = "SELECT p.ProductID, p.Name AS ProductName, p.Description, p.Price, p.Category, s.Name AS SupplierName
FROM Product_EEA p
INNER JOIN SupplierProduct_EEA sp ON p.ProductID = sp.ProductID
INNER JOIN Supplier_EEA s ON sp.SupplierID = s.SupplierID;
";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" type="text/css" href="../CSS/back-home.css">
    <title>Products</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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
            padding: 10px;
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

        tr:hover {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<div class="container">
    <?php if (mysqli_num_rows($result) > 0): ?>
        <h2>All Products</h2>
        <table>
            <tr>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Supplier Name</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $row['ProductID'] ?></td>
                    <td><?= $row['ProductName'] ?></td>
                    <td><?= $row['Price'] ?></td>
                    <td><?= $row['SupplierName'] ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No products available.</p>
    <?php endif; ?>
</div>

</body>
</html>

<?php
// Close connection
mysqli_close($conn);
?>
