<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php'; 

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    session_unset();
    session_destroy();
    echo "<h1>Account Deleted Successfully</h1>";
    echo "<p>Your account has been deleted. Would you like to:</p>";
    echo "<ul>
            <li><a href='./login.php'>Log In</a></li>
            <li><a href='./home.php'>Go to Home Page</a></li>
          </ul>";
} else {
    echo "<h1>Error</h1>";
    echo "<p>There was an issue deleting your account. Please try again later.</p>";
}

$stmt->close();
$conn->close();
?>
