<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mart_management_system";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

function getTotalProducts($conn) {
  $sql = "SELECT COUNT(*) as total FROM Product";
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();
  return $row['total'];
}

function getOutOfStockProducts($conn) {
  $sql = "SELECT COUNT(*) as total FROM Product WHERE QuantityInStock = 0";
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();
  return $row['total'];
}

function getLowStockProducts($conn) {
  $sql = "SELECT COUNT(*) as total FROM Product WHERE QuantityInStock < 20 AND QuantityInStock > 0";
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();
  return $row['total'];
}

function getStockValue($conn) {
  $sql = "SELECT SUM(UnitPrice * QuantityInStock) as total FROM Product";
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();
  return $row['total'];
}

function getInventoryDetails($conn) {
  $sql = "SELECT p.ProductID, p.ProductName, p.QuantityInStock, p.UnitPrice, c.CategoryName 
          FROM Product p 
          LEFT JOIN Category c ON p.CategoryID = c.CategoryID";
  return $conn->query($sql);
}

function getStockStatus($quantity) {
  if ($quantity == 0) {
    return 'Out of Stock';
  } elseif ($quantity < 20) {
    return 'Low Stock';
  } else {
    return 'In Stock';
  }
}

function getTotalSales($conn, $startDate = null, $endDate = null) {
  $sql = "SELECT SUM(TotalAmount) as totalSales FROM Orders";
  if ($startDate && $endDate) {
    $sql .= " WHERE OrderDate BETWEEN '$startDate' AND '$endDate'";
  }
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();
  return $row['totalSales'];
}

function getTotalOrders($conn, $startDate = null, $endDate = null) {
  $sql = "SELECT COUNT(*) as totalOrders FROM Orders";
  if ($startDate && $endDate) {
    $sql .= " WHERE OrderDate BETWEEN '$startDate' AND '$endDate'";
  }
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();
  return $row['totalOrders'];
}

function getAverageOrderValue($conn, $startDate = null, $endDate = null) {
  $totalSales = getTotalSales($conn, $startDate, $endDate);
  $totalOrders = getTotalOrders($conn, $startDate, $endDate);
  return $totalOrders ? $totalSales / $totalOrders : 0;
}

function getRecentOrders($conn) {
  $sql = "SELECT OrderID, OrderDate, TotalAmount, Status FROM Orders ORDER BY OrderDate DESC LIMIT 5";
  $result = $conn->query($sql);
  return $result;
}

function createOrder($conn, $totalAmount, $cartItems, $billedBy) {
  $orderDate = date("Y-m-d H:i:s");
  $status = 'Completed';
  
  $sql = "INSERT INTO Orders (OrderDate, TotalAmount, Status, BilledBy) VALUES ('$orderDate', '$totalAmount', '$status', '$billedBy')";
  if ($conn->query($sql) === TRUE) {
    $orderID = $conn->insert_id;
    foreach ($cartItems as $cartItem) {
      $productID = $cartItem['productID'];
      $quantity = $cartItem['quantity'];
      $sql = "INSERT INTO OrderDetails (OrderID, ProductID, Quantity) VALUES ('$orderID', '$productID', '$quantity')";
      $conn->query($sql);
    }
    return true;
  } else {
    return false;
  }
}
?>