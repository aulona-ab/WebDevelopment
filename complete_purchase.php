<?php
session_start();
include 'db.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You need to log in to complete the purchase.";
    exit;
}

$user_id = $_SESSION['user_id'];
$total_price = $_POST['total_price'];
$book_ids = $_POST['book_ids']; // Array of book IDs from the cart
$quantities = $_POST['quantities']; // Corresponding quantities for the books

// Check if the user exists in the users table
$stmt = $conn->prepare("SELECT id FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 0) {
    echo "User does not exist. Please log in.";
    exit;
}

// Insert the order into the orders table
$stmt = $conn->prepare("INSERT INTO orders (user_id, total_price, status) VALUES (?, ?, 'pending')");
$stmt->bind_param("id", $user_id, $total_price);
if ($stmt->execute()) {
    // Get the last inserted order ID
    $order_id = $stmt->insert_id;

    // Insert each book and its details into the order_details table
    $stmt = $conn->prepare("INSERT INTO order_details (order_id, book_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($book_ids as $index => $book_id) {
        $quantity = $quantities[$index];
        $price = $_POST['prices'][$index]; // Book price (use the price at the time of purchase)
        $stmt->bind_param("iiid", $order_id, $book_id, $quantity, $price);
        $stmt->execute();
    }

    // Redirect to confirmation page
    header("Location: order_confirmation.php?order_id=" . $order_id);
    exit;
} else {
    echo "Error: Unable to place the order.";
}
?>
