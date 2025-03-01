<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

  <title>Dashboard</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="dashboard">
    <!-- Sidebar -->
    <aside class="sidebar">
    <a href="index.php" class="logo">
            <i class='bx bx-cart' ></i>
            <div class="logo-name"><span>Shopping</span>Mart</div>
        </a>
      <nav>
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
        <h1>Dashboard</h1>
      </header>
      <section class="widgets">
        <?php
          include 'db_connection.php';

          $totalSales = getTotalSales($conn);
          $totalProducts = getTotalProducts($conn);
          $stockValue = getStockValue($conn);
          $totalOrders = getTotalOrders($conn);

          echo "<div class='widget'>
                <i class='bx bx-dollar-circle'></i>
                <div class='widget-content'>
                    <h3>Total Sales</h3>
                    <p>Rs. $totalSales</p>
                </div>
              </div>";

        echo "<div class='widget'>
                <i class='bx bx-cube'></i>
                <div class='widget-content'>
                    <h3>Products in Stock</h3>
                    <p>$totalProducts</p>
                </div>
              </div>";

        echo "<div class='widget'>
                <i class='bx bx-bar-chart'></i>
                <div class='widget-content'>
                    <h3>Stock Value</h3>
                    <p>Rs. $stockValue</p>
                </div>
              </div>";

        echo "<div class='widget'>
                <i class='bx bx-cart'></i>
                <div class='widget-content'>
                    <h3>Orders</h3>
                    <p>$totalOrders</p>
                </div>
              </div>";
        ?>
      </section>
      

      <!-- New Section: Recent Orders -->
      <section id="recent-orders" class="recent-transactions">
        <h2>Recent Orders</h2>
        <table>
          <thead>
            <tr>
              <th>Order ID</th>
              <th>Date</th>
              <th>Amount</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php
              $orders = getRecentOrders($conn);
              if ($orders->num_rows > 0) {
                while($row = $orders->fetch_assoc()) {
                  echo "<tr>
                          <td>#{$row['OrderID']}</td>
                          <td>{$row['OrderDate']}</td>
                          <td>Rs. {$row['TotalAmount']}</td>
                          <td>{$row['Status']}</td>
                        </tr>";
                }
              } else {
                echo "<tr><td colspan='4'>No recent orders</td></tr>";
              }
            ?>
          </tbody>
        </table>
      </section>
    </main>
  </div>
  <script src="script.js"></script>
</body>
</html>