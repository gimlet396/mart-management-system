<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Billing</title>
  <link rel="stylesheet" type="text/css" href="style.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<style>
  .add-form-input {
  font-size: 12px;
  padding: 10px;
}
.remove-item-button {
  background-color: red;
  color: white;
  border: none;
  border-radius: 12px;
  font-size: 10px;
  padding: 5px 10px;
  cursor: pointer;
}

.remove-item-button:hover {
  background-color: darkred;
}


</style>
<body>
  <div class="dashboard">
    <!-- Sidebar -->
    <aside class="sidebar">
    <a href="index.php" class="logo">
            <i class='bx bx-cart' ></i>
            <div class="logo-name"><span>Shopping</span>Mart</div>
        </a>      <nav>
        <ul>
          <li><i class='bx bxs-dashboard'></i><a href="index.php">Dashboard</a></li>
          <li><i class='bx bx-receipt'></i><a href="billing.php">Billing</a></li>
          <li><i class='bx bx-shopping-bag'></i><a href="sales.php">Sales</a></li>
          <li><i class='bx bx-box'></i><a href="inventory.php">Inventory</a></li>
          <li><i class='bx bxs-plus-circle'></i><a href="add_product.php">Add Product</a></li>
          <li><i class='bx bxs-category'></i><a href="add_category.php">Add Category</a></li>
        </ul>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
      <header>
        <h1>Billing</h1>
      </header>

      
<?php
  include 'db_connection.php';

  if (!isset($_SESSION)) {
    session_start();
  }

  // Add product to cart
  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addProduct'])) {
    $productID = $_POST["productID"];
    $quantity = $_POST["quantity"];
    $sql = "SELECT ProductName, UnitPrice, QuantityInStock FROM Product WHERE ProductID = '$productID'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
      $product = $result->fetch_assoc();
      if ($product['QuantityInStock'] >= $quantity) {
        $productName = $product['ProductName'];
        $unitPrice = $product['UnitPrice'];
        $totalPrice = $unitPrice * $quantity;

        if (!isset($_SESSION['cart'])) {
          $_SESSION['cart'] = [];
        }
        array_push($_SESSION['cart'], [
          'productID' => $productID,
          'productName' => $productName,
          'quantity' => $quantity,
          'unitPrice' => $unitPrice,
          'totalPrice' => $totalPrice
        ]);
      } else {
        echo "<p class='error-message'>{$product['ProductName']} is out of stock.</p>";
      }
    }
  }

  // Handle removing an item from the cart
  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['removeItem'])) {
    $itemIndex = $_POST['itemIndex'];

    if (isset($_SESSION['cart'][$itemIndex])) {
      unset($_SESSION['cart'][$itemIndex]);
      $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index the array after removing
    }
  }

  // Checkout process
  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['checkout'])) {
    $totalAmount = 0;
    if (isset($_SESSION['cart'])) {
      foreach ($_SESSION['cart'] as $cartItem) {
        $productID = $cartItem['productID'];
        $quantity = $cartItem['quantity'];
        $totalAmount += $cartItem['totalPrice'];
        $sql = "UPDATE Product SET QuantityInStock = QuantityInStock - $quantity WHERE ProductID = '$productID'";
        $conn->query($sql);
      }

      $billedBy = 'Admin';
      $orderCreated = createOrder($conn, $totalAmount, $_SESSION['cart'], $billedBy);

      if ($orderCreated) {
        $bill = '<div class="billing-info">';
        $bill .= '<h1>Bill Receipt</h1>';
        $bill .= '<p>Date of Purchase: ' . date("Y-m-d H:i:s") . '</p>';
        $bill .= '<p>Billed by: Admin</p>';
        $bill .= '</div>';
        $bill .= '<table border="1" width="100%" style="border-collapse: collapse;">
                    <thead>
                      <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total Price</th>
                      </tr>
                    </thead>
                    <tbody>';
        foreach ($_SESSION['cart'] as $cartItem) {
          $bill .= '<tr>
                      <td>' . $cartItem['productName'] . '</td>
                      <td>' . $cartItem['quantity'] . '</td>
                      <td>' . $cartItem['unitPrice'] . '</td>
                      <td>' . $cartItem['totalPrice'] . '</td>
                    </tr>';
        }
        $bill .= '</tbody>
                  </table>';
        $bill .= '<div class="billing-info">';
        $bill .= '<p>Total Billed Amount: Rs. ' . $totalAmount . '</p>';
        $bill .= '</div>';
        echo $bill;

        unset($_SESSION['cart']);
      } else {
        echo "<p class='error-message'>Failed to create order. Please try again.</p>";
      }
    }
  }
?>

      <form method="POST" action="" class="billing-form">
      <div class="add-form-group">
  <label for="productID" class="add-form-label">Product:</label>
  <select id="productID" name="productID" required class="add-form-input">
    <?php
      $sql = "SELECT ProductID, ProductName, UnitPrice, QuantityInStock FROM Product";
      $result = $conn->query($sql);
      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          echo "<option value='{$row['ProductID']}'>
                  ID: {$row['ProductID']} &nbsp;&nbsp;&nbsp; {$row['ProductName']} &nbsp;&nbsp;&nbsp; Price: Rs. {$row['UnitPrice']} &nbsp;&nbsp;&nbsp; Available: {$row['QuantityInStock']}
                </option>";
        }
      }
    ?>
  </select>
</div>



        <div class="add-form-group">
          <label for="quantity" class="add-form-label">Quantity:</label>
          <input type="number" id="quantity" name="quantity" required class="add-form-input">
        </div>

        <button type="submit" name="addProduct" class="add-submit-button">Add Product</button>
      </form>

      <div class="cart">
  <h2>Cart</h2>
  <table>
    <thead>
      <tr>
        <th>Product Name</th>
        <th>Quantity</th>
        <th>Unit Price</th>
        <th>Total Price</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php
        if (isset($_SESSION['cart'])) {
          foreach ($_SESSION['cart'] as $index => $cartItem) {
            echo '<tr>
                    <td>' . $cartItem['productName'] . '</td>
                    <td>' . $cartItem['quantity'] . '</td>
                    <td>' . $cartItem['unitPrice'] . '</td>
                    <td>' . $cartItem['totalPrice'] . '</td>
                    <td>
                      <form method="POST" action="" class="remove-item-form">
                        <input type="hidden" name="itemIndex" value="' . $index . '">
                        <button type="submit" name="removeItem" class="remove-item-button">Remove</button>
                      </form>
                    </td>
                  </tr>';
          }
        }
      ?>
    </tbody>
  </table>
  <form method="POST" action="" class="checkout-form">
    <button type="submit" name="checkout" class="add-submit-button">Checkout</button>
  </form>
</div>

    </main>
  </div>
</body>
</html>