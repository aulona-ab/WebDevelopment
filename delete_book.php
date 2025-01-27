<?php
// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'bookstore';

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $book_id = $_POST['book_id'];

    // Fetch the book to delete the image file
    $result = $conn->query("SELECT image_path FROM books WHERE id = $book_id");
    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
        $image_path = $book['image_path'];

        // Delete the image file from the server
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }

    // Delete the book from the database
    $sql = "DELETE FROM books WHERE id = $book_id";

    if ($conn->query($sql) === TRUE) {
        header('Location: home.php');
        } else {
        echo "Error deleting book: " . $conn->error;
    }
}

$conn->close();
?>
