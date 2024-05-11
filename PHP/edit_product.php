<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once("connection.php");
require_once('Supplierhhome-back.php');

// Check if user is logged in
if (!isset($_SESSION['supplier_id'])) {
    header('Location: ../Supplierlogin.html');
    exit();
}

// Check if the product ID is set
if (!isset($_GET['ProductID'])) {
    echo "ProductID is not set.";
    exit();
}

// Get the product details
$ProductID = $_GET['ProductID'];
$sql_product = "SELECT * FROM Product_EEA WHERE ProductID = $ProductID";
$result_product = mysqli_query($conn, $sql_product);

if (!$result_product) {
    echo "Error fetching product details: " . mysqli_error($conn);
    exit();
}

if (mysqli_num_rows($result_product) == 0) {
    echo "Product not found.";
    exit();
}

$row_product = mysqli_fetch_assoc($result_product);

// Get the quantity from Inventory_EEA
$sql_inventory = "SELECT Quantity FROM Inventory_EEA WHERE ProductID = $ProductID";
$result_inventory = mysqli_query($conn, $sql_inventory);

if (!$result_inventory) {
    echo "Error fetching quantity: " . mysqli_error($conn);
    exit();
}

$row_inventory = mysqli_fetch_assoc($result_inventory);
$quantity = isset($row_inventory['Quantity']) ? $row_inventory['Quantity'] : '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['Name']);
    $description = mysqli_real_escape_string($conn, $_POST['Description']);
    $price = mysqli_real_escape_string($conn, $_POST['Price']);
    $category = mysqli_real_escape_string($conn, $_POST['Category']);
    $new_quantity = mysqli_real_escape_string($conn, $_POST['Quantity']);

    // update the product image if a new image was uploaded
    if(isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
        $image_name = $_FILES['image']['name'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_type = $_FILES['image']['type'];
        $image_size = $_FILES['image']['size'];
      
        // generate a unique file name to avoid conflicts with existing files
        $new_image_name = uniqid() . '-' . $image_name;
        $image_path = '../uploads/' . $new_image_name;
      
        // make sure that the uploads directory exists
        if(!file_exists('../uploads')) {
            mkdir('../uploads');
        }

        // move the uploaded image to the desired directory on the server
        if(move_uploaded_file($image_tmp_name, $image_path)) {
            // Delete the old image if it exists
            if (!empty($row_product['Image']) && file_exists($row_product['Image'])) {
                unlink($row_product['Image']);
            }

            // Update product details in Product_EEA table
            $sql_update_product = "UPDATE Product_EEA SET Name='$name', Description='$description', Price='$price', Category='$category', Image = '$image_path' WHERE ProductID=$ProductID";
            $result_update_product = mysqli_query($conn, $sql_update_product);
            if (!$result_update_product) {
                echo "Error updating product details: " . mysqli_error($conn);
                exit();
            }
        } else {
            echo "Error uploading image.";
            exit();
        }
    }

    // Update quantity in Inventory_EEA table
    $sql_update_quantity = "UPDATE Inventory_EEA SET Quantity=$new_quantity WHERE ProductID=$ProductID";
    $result_update_quantity = mysqli_query($conn, $sql_update_quantity);

    if (!$result_update_quantity) {
        echo "Error updating quantity: " . mysqli_error($conn);
        exit();
    }

    // Redirect to product list page after successful update
    header("Location: ./Product_list.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" type="text/css" href="../CSS/add_eco.css">
    <link rel="stylesheet" type="text/css" href="../CSS/back-home.css">
</head>

<body>
    <h1>Edit Product</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?ProductID=' . $ProductID; ?>" method="POST" enctype="multipart/form-data">
        <label for="Name">Product Name</label><br>
        <input type="text" id="Name" name="Name" value="<?php echo $row_product['Name']; ?>"><br><br>
        <label for="Description">Description</label><br>
        <textarea id="Description" name="Description"><?php echo $row_product['Description']; ?></textarea><br><br>
        <label for="Price">Price</label><br>
        <input type="text" id="Price" name="Price" value="<?php echo $row_product['Price']; ?>"><br><br>
        <label for="Category">Category</label><br>
        <input type="text" id="Category" name="Category" value="<?php echo $row_product['Category']; ?>"><br><br>
        <label for="Quantity">Quantity</label><br>
        <input type="number" id="Quantity" name="Quantity" value="<?php echo $quantity; ?>"><br><br>
        <label for="image">Product Image</label><br>
        <input type="file" name="image" id="image"><br><br>
        <div class="buttons">
            <button type="submit" name="submit" value="submit">Save</button>
            <button onclick="window.location.href='./Product_list.php'; return false;">Cancel</button>
        </div>
    </form>
</body>

</html>
