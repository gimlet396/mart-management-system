<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inventory Page</title>
  <link rel="stylesheet" href="style.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
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
        <h1>Inventory Overview</h1>
      </header>

      <?php
        include 'db_connection.php';
      ?>

      <!-- Filters Section -->
      <section class="filters">
        <label for="search-item">Search Item:</label>
        <input type="text" id="search-item" placeholder="Enter item name or ID">
        <button onclick="searchInventory()">Search</button>
      </section>

      <!-- Inventory Summary Widgets -->
      <section class="widgets">
        <div class="widget">
          <i class='bx bx-package'></i> <!-- Icon for Total Products -->
          <div class="widget-content">
            <h3>Total Products</h3>
            <p><?php echo getTotalProducts($conn); ?></p>
          </div>
        </div>
        <div class="widget">
          <i class='bx bx-store'></i> <!-- Icon for Out of Stock -->
          <div class="widget-content">
            <h3>Out of Stock</h3>
            <p><?php echo getOutOfStockProducts($conn); ?></p>
          </div>
        </div>
        <div class="widget">
          <i class='bx bx-low-vision'></i> <!-- Icon for Low Stock -->
          <div class="widget-content">
            <h3>Low Stock</h3>
            <p><?php echo getLowStockProducts($conn); ?></p>
          </div>
        </div>
        <div class="widget">
          <i class='bx bx-dollar-circle'></i> <!-- Icon for Stock Value -->
          <div class="widget-content">
            <h3>Stock Value</h3>
            <p>$<?php echo getStockValue($conn); ?></p>
          </div>
        </div>
      </section>

      <!-- Inventory Details Table -->
      <section class="inventory-details">
        <h2>Inventory Details</h2>
        <table>
          <thead>
            <tr>
              <th>Product ID</th>
              <th>Product Name</th>
              <th>Category</th>
              <th>Stock</th>
              <th>Price</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php
              $result = getInventoryDetails($conn);

              if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                  echo "<tr>
                          <td>{$row['ProductID']}</td>
                          <td>{$row['ProductName']}</td>
                          <td>{$row['CategoryName']}</td>
                          <td>{$row['QuantityInStock']}</td>
                          <td>\${$row['UnitPrice']}</td>
                          <td>" . getStockStatus($row['QuantityInStock']) . "</td>
                        </tr>";
                }
              } else {
                echo "<tr><td colspan='6'>No products found</td></tr>";
              }

              $conn->close();
            ?>
          </tbody>
        </table>
      </section>
    </main>
  </div>
  <script src="script.js"></script>
</body>
</html>