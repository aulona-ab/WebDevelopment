<?php
// Connection with the database
$host = 'localhost';
$user = 'root'; 
$password = ''; 
$dbname = 'bookstore';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the email from the form
    $email = $conn->real_escape_string($_POST['email']);

    // Check if the email is valid
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Insert the email into the database
        $sql = "INSERT INTO subscribers (email) VALUES ('$email')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>
            window.location.href = 'sub.php';
          </script>";
    
        } else {
            if ($conn->errno === 1062) {
                echo "This email is already subscribed.";
            } else {
                echo "Error: " . $conn->error;
            }
        }
    } else {
        echo "Please enter a valid email address.";
    }
}
?>
