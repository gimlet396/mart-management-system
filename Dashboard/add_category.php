<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Category</title>
  <link rel="stylesheet" type="text/css" href="style.css">
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
        <h1>Add Category</h1>
      </header>

      <?php
        include 'db_connection.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
          $categoryName = $_POST["categoryName"];
          $description = $_POST["description"];

          $sql = "INSERT INTO Category (CategoryName, Description)
                  VALUES ('$categoryName', '$description')";

          if ($conn->query($sql) === TRUE) {
            echo "<p class='success-message'>New category added successfully</p>";
          } else {
            echo "<p class='error-message'>Error: " . $sql . "<br>" . $conn->error . "</p>";
          }
        }
      ?>

      <form method="POST" action="" class="category-form">
        <div class="add-form-group">
          <label for="categoryName" class="add-form-label">Category Name:</label>
          <input type="text" id="categoryName" name="categoryName" required class="add-form-input">
        </div>

        <div class="add-form-group">
          <label for="description" class="add-form-label">Description:</label>
          <textarea id="description" name="description" required class="add-form-input"></textarea>
        </div>

        <button type="submit" class="add-submit-button">Add Category</button>
      </form>
    </main>
  </div>
</body>
</html>