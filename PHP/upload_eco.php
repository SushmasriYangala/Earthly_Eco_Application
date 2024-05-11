<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

// Check if a Supplier is logged in
if (!isset($_SESSION['supplier_id'])) {
  header('Location: ../Supplierlogin.html');
  exit();
}

include("connection.php");

// Get the Supplier id from the session
$supplier_id = $_SESSION['supplier_id'];

// Check if the form is submitted
if (isset($_POST['submit'])) {
  $Name = $_POST['Name'];
  $Description = $_POST['Description'];
  $Price = $_POST['Price'];
  $Category = $_POST['Category'];
  $Quantity = $_POST['Quantity'];

  // Counter for ProductID
  $product_counter_result = mysqli_query($conn, "SELECT MAX(ProductID) AS maxProductID FROM Product_EEA");
  $product_counter_row = mysqli_fetch_assoc($product_counter_result);
  $ProductID = $product_counter_row['maxProductID'] + 1;

  // Counter for SupplierProductID
  $Supplierproduct_counter_result = mysqli_query($conn, "SELECT MAX(SupplierProductID) AS maxSupplierProductID FROM SupplierProduct_EEA");
  $Supplierproduct_counter_row = mysqli_fetch_assoc($Supplierproduct_counter_result);
  $SupplierProductID = $Supplierproduct_counter_row['maxSupplierProductID'] + 1;

  // Check if an image was uploaded
  if (isset($_FILES['image']['name']) && $_FILES['image']['error'] == 0) {
    $image_name = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_size = $_FILES['image']['size'];
    $image_error = $_FILES['image']['error'];
    $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

    $allowed_exts = array('jpg', 'jpeg', 'png', 'gif');

    // Check if the file is a valid image
    if (in_array($image_ext, $allowed_exts)) {
      // Generate a unique file name for the image
      $image_new_name = uniqid('', true) . "." . $image_ext;
      $image_dest_path = "../uploads/" . $image_new_name;

      // Move the image to the file system
      if (move_uploaded_file($image_tmp_name, $image_dest_path)) {
        // Prepare the SQL statement for inserting into Product_EEA table
        $stmt_product = mysqli_prepare($conn, "INSERT INTO Product_EEA (ProductID, Name, Description, Price, Category, Image) VALUES (?, ?, ?, ?, ?, ?)");

        // Bind parameters to the prepared statement for Product_EEA table
        mysqli_stmt_bind_param($stmt_product, "isssss", $ProductID, $Name, $Description, $Price, $Category, $image_dest_path);

        // Execute the prepared statement for Product_EEA table
        if (mysqli_stmt_execute($stmt_product)) {
          // Prepare the SQL statement for inserting into Inventory_EEA table
          $stmt_inventory = mysqli_prepare($conn, "INSERT INTO Inventory_EEA (ProductID, Quantity) VALUES (?, ?)");

          // Bind parameters to the prepared statement for Inventory_EEA table
          mysqli_stmt_bind_param($stmt_inventory, "ii", $ProductID, $Quantity);

          // Execute the prepared statement for Inventory_EEA table
          if (mysqli_stmt_execute($stmt_inventory)) {
            // Insert into SupplierProduct_EEA table
            $stmt_supplier_product = mysqli_prepare($conn, "INSERT INTO SupplierProduct_EEA (SupplierProductID, ProductID, SupplierID) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($stmt_supplier_product, "iii", $SupplierProductID, $ProductID, $supplier_id);
            mysqli_stmt_execute($stmt_supplier_product);

            header("Location: ./Product_list.php");
            exit();
          } else {
            echo "Error adding product to inventory: " . mysqli_stmt_error($stmt_inventory);
          }

          // Close the prepared statement for Inventory_EEA table
          mysqli_stmt_close($stmt_inventory);
        } else {
          echo "Error uploading product: " . mysqli_stmt_error($stmt_product);
        }

        // Close the prepared statement for Product_EEA table
        mysqli_stmt_close($stmt_product);
      } else {
        echo "Error moving image to file system.";
      }
    } else {
      echo "Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.";
    }
  } else {
    echo "No image was uploaded or there was an upload error: " . $_FILES['image']['error'];
  }
}
?>
