<?php
// Include the database connection file
include('db.php');

// Start the session
session_start();

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // Check if book_id is provided in the POST request
    if (isset($_POST['book_id'])) {
        $book_id = $_POST['book_id'];
        $user_id = $_SESSION['user_id'];

        // Prepare the SQL statement to remove the bookmark
        $sql = "DELETE FROM bookmarks WHERE user_id = ? AND book_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $book_id);

        // Execute the query
        if ($stmt->execute()) {
            // Redirect back to the bookmarked books page
            header("Location: bookmark.php");
            exit();
        } else {
            echo "Error removing the bookmark. Please try again.";
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "No book selected.";
    }
} else {
    echo "You need to be logged in to remove bookmarks.";
}

$conn->close();
?>
