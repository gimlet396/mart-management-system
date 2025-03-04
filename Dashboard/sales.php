<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sales Page</title>
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
        <h1>Sales Overview</h1>
      </header>

      <!-- Filters Section -->
      <!-- <section class="filters">
        <label for="date-range">Date Range:</label>
        <input type="date" id="start-date"> to 
        <input type="date" id="end-date">
        <button onclick="filterSales()">Filter</button>
      </section> -->

      <?php
        include 'db_connection.php';

        $totalSales = getTotalSales($conn);
        $totalOrders = getTotalOrders($conn);
        $averageOrderValue = $totalOrders ? $totalSales / $totalOrders : 0;
        $recentOrders = getRecentOrders($conn);
      ?>

      <!-- Sales Summary Widgets -->
      <section class="widgets">
    <div class="widget">
        <i class='bx bx-dollar-circle'></i> <!-- Icon for Total Sales -->
        <div class="widget-content">
            <h3>Total Sales</h3>
            <p>Rs. <?php echo number_format($totalSales, 2); ?></p>
        </div>
    </div>
    <div class="widget">
        <i class='bx bx-cart-alt'></i> <!-- Icon for Total Orders -->
        <div class="widget-content">
            <h3>Total Orders</h3>
            <p><?php echo $totalOrders; ?></p>
        </div>
    </div>
    <div class="widget">
        <i class='bx bx-line-chart'></i> <!-- Icon for Average Order Value -->
        <div class="widget-content">
            <h3>Average Order Value</h3>
            <p>$<?php echo number_format($averageOrderValue, 2); ?></p>
        </div>
    </div>
    <div class="widget">
        <i class='bx bx-package'></i> <!-- Icon for Total Sold Items -->
        <div class="widget-content">
            <h3>Total Sold Items</h3>
            <p><?php echo $totalOrders; ?></p>
        </div>
    </div>
</section>

      <!-- Recent Sales Table -->
      <section class="recent-sales">
        <h2>Recent Sales</h2>
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
              if ($recentOrders->num_rows > 0) {
                while ($row = $recentOrders->fetch_assoc()) {
                  echo "<tr>
                          <td>#{$row['OrderID']}</td>
                          <td>{$row['OrderDate']}</td>
                          <td>$" . number_format($row['TotalAmount'], 2) . "</td>
                          <td>{$row['Status']}</td>
                        </tr>";
                }
              } else {
                echo "<tr><td colspan='4'>No recent sales</td></tr>";
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