<?php
session_start();
include 'db.php';

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Fetch the order details from the orders table
    $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();

    if ($order) {
        echo "<h1>Order Confirmation</h1>";
        echo "<p>Your order has been placed successfully!</p>";
        echo "<p>Order ID: " . $order['id'] . "</p>";
        echo "<p>Total Price: " . number_format($order['total_price'], 2) . " €</p>";
        echo "<p>Status: " . $order['status'] . "</p>";
        echo "<p>Order Date: " . $order['order_date'] . "</p>";
    } else {
        echo "Order not found.";
    }
} else {
    echo "No order ID provided.";
}
?>
