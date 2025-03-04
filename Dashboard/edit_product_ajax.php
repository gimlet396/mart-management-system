<?php
include 'db_connection.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['productId'], $data['productName'], $data['quantityInStock'], $data['unitPrice'])) {
    $productId = $data['productId'];
    $productName = $data['productName'];
    $quantityInStock = $data['quantityInStock'];
    $unitPrice = $data['unitPrice'];

    // Prepare SQL query to update the product
    $sql = "UPDATE Product SET ProductName = ?, QuantityInStock = ?, UnitPrice = ? WHERE ProductID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sidi", $productName, $quantityInStock, $unitPrice, $productId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Product updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating product']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
}
?>
