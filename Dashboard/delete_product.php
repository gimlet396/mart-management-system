<?php
include 'db_connection.php'; // Ensure this file connects to your database

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$productId = isset($data['productId']) ? intval($data['productId']) : 0;
$quantityToDelete = isset($data['quantity']) ? intval($data['quantity']) : 0;

if ($productId > 0 && $quantityToDelete > 0) {
    // Get current stock
    $query = $conn->prepare("SELECT QuantityInStock FROM product WHERE ProductID = ?");
    $query->bind_param("i", $productId);
    $query->execute();
    $result = $query->get_result();

    if ($row = $result->fetch_assoc()) {
        $currentStock = $row['QuantityInStock'];

        if ($quantityToDelete >= $currentStock) {
            // Delete product if quantity to delete is equal or greater than stock
            $deleteQuery = $conn->prepare("DELETE FROM product WHERE ProductID = ?");
            $deleteQuery->bind_param("i", $productId);
            if ($deleteQuery->execute()) {
                echo json_encode(["success" => true, "message" => "Product removed from inventory."]);
            } else {
                echo json_encode(["success" => false, "message" => "Error deleting product: " . $conn->error]);
            }
        } else {
            // Reduce quantity instead of deleting the entire row
            $updateQuery = $conn->prepare("UPDATE product SET QuantityInStock = QuantityInStock - ? WHERE ProductID = ?");
            $updateQuery->bind_param("ii", $quantityToDelete, $productId);
            if ($updateQuery->execute()) {
                echo json_encode(["success" => true, "message" => "Stock updated successfully."]);
            } else {
                echo json_encode(["success" => false, "message" => "Error updating stock: " . $conn->error]);
            }
        }
    } else {
        echo json_encode(["success" => false, "message" => "Product not found."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}

$conn->close();
?>
