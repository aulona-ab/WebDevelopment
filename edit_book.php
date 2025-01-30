<?php

session_start(); 

include 'db.php'; 

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM books WHERE title = '$id'";
    $result = mysqli_query($conn, $query);
    $book = mysqli_fetch_assoc($result);
}

if (isset($_POST['update'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $isbn = $_POST['isbn'];
    $price = $_POST['price'];
    $discount = $_POST['discount'];
    $discounted_price = $_POST['discounted_price'];
    $image_path = $book['image_path'];
    $added_by = $_POST['added_by'];

    if ($discount > 0) {
        $discounted_price = $price - ($price * ($discount / 100));
    } else {
        $discounted_price = $price; 
    }

    // Check if a new image is uploaded
    if (!empty($_FILES['image']['name'])) {
        // Handle the new image upload
        $image = $_FILES['image']['name'];
        $target = "bookss/books/" . basename($image);

        // Try to upload the new image
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            // Do not delete the old image - only update the path
            $image_path = 'books/' . $image;
        } else {
            echo "Error uploading image!";
            exit();
        }
    }

    // Update the database with the correct image path
    $updateQuery = "UPDATE books SET title='$title', author='$author', isbn='$isbn', price='$price', discount='$discount', discounted_price='$discounted_price', added_by='$added_by',  image_path ='$image_path'  WHERE title='$id'";
    if (mysqli_query($conn, $updateQuery)) {
        echo "Book updated successfully!";
        header("Location: dashboard.php"); 
        exit();
    } else {
        echo "Error updating book: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Dashboard</title>
    <link rel="stylesheet" href="dboard.css">
</head>
<body>
    <div class="dashboard">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <img src="./Logo/7-removebg-preview.png" alt="Logo" style="width: 100%;">
            </div>
            
            <br>
            <br>
            <br>
            <br>
            <br>
            <a href="./home.php">Home</a>
            <a href="./profile.php">Profile</a>
            <a href="./cart.php">Cart</a>
            <a href="./dashboard.php">Daily Report</a>
            <br>
            <div class="logo">
                <img src="./dashboardd/dashboard_custom_logo.png" alt="Logo" style="width: 100%;">
            </div>
        </div>

        <!-- Main Content -->
        <div class="content" id="content">
            <h1> Edit Book</h1>

        <form method="POST" class="edit-book-form" enctype="multipart/form-data">
            <label>Title:</label>
            <input type="text" name="title" value="<?= $book['title']; ?>" required>
            
            <label>Author:</label>
            <input type="text" name="author" value="<?= $book['author']; ?>" required>
            
            <label>ISBN:</label>
            <input type="text" name="isbn" value="<?= $book['isbn']; ?>" required>

            <label>Price:</label>
            <input type="text" name="price" value="<?= $book['price']; ?>" required>
            
            <label>Discount (%):</label>
            <input type="text" name="discount" value="<?= $book['discount']; ?>" required>
            
            <label>Discounted Price:</label>
            <input type="text" name="discounted_price" value="<?= $book['discounted_price']; ?>" required>
           
            <label>Added By:</label>
            <input type="text" name="added_by" value="<?= $book['added_by']; ?>" readonly>


           <!-- Display Current Image -->
    <div class="image-preview">
        <p>Current Image:</p>
        <div class="book-image">
            <img src="<?= $book['image_path']; ?>" alt="<?= $book['title']; ?>" style=" width:150px; height: auto;" />
        </div>  
        </div>

    <label>Update Image:</label>
    <input type="file" name="image">

    <button type="submit" name="update">Update Book</button>
        </form>

        
</div>
       

<div class="admin-profile">
  <br>
  <br>
    <div class="profile-info">
        <img src="./Logo/Female-avatar-2.png" alt="Admin Profile Picture" class="profile-pic">
        <div class="admin-details">
            <h3>Welcome, Admin <?php echo $_SESSION['username']; ?></h3>
            <p>Email: admin@luminousbookstore.com</p>
        </div>
    </div>

    <div class="recent-activities">
        <h3>Recent Activities</h3>
        <ul>
            <li>New Order: "The Great Adventure" - $12.99</li>
            <li>New Customer: John Doe has registered</li>
            <li>New Book Added: "Mystery Unfolded"</li>
        </ul>
    </div>

    <div class="admin-actions">
        <h3>Quick Actions</h3>
        <br>
        <a href="add_book.php"><button class="action-btn">Add New Book</button></a>   
        <br>     
        <button class="action-btn">View Reports</button>
        <button class="action-btn">Manage Customers</button>
    </div>
</div>

    </div>

</body>
</html>
