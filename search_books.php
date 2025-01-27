<?php
// Include your database connection
include 'db.php';

if (isset($_GET['query'])) {
    $query = htmlspecialchars($_GET['query']);
    
    // Modify SQL query to search across title, isbn, and author
    $sql = "SELECT id, title FROM books WHERE title LIKE ? OR isbn LIKE ? OR author LIKE ?";
    $stmt = $conn->prepare($sql);
    
    // Prepare the search term for all three fields (title, isbn, author)
    $searchTerm = "%$query%";
    $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div onclick=\"window.location.href='book_details.php?book_id={$row['id']}'\" style=\"cursor: pointer; padding: 8px 12px;\">"
                 . htmlspecialchars($row['title']) .
                 "</div>";
        }
    } else {
        echo "<div>No results found.</div>";
    }

    $stmt->close();
    $conn->close();
}
?>
