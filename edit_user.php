<?php
session_start(); 

include 'db.php';

if (isset($_GET['id'])) {
    $username = $_GET['id'];
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);
}

if (isset($_POST['update'])) {
    $email = $_POST['email'];
    $role = $_POST['role'];
    $update_reason = $_POST['update_reason'];
    $updated_by = $_SESSION['username'];

    $updateQuery = "UPDATE users SET email='$email', role='$role', updated_by='$updated_by', update_reason='$update_reason' WHERE username='$username'";
    if (mysqli_query($conn, $updateQuery)) {
        echo "User updated successfully!";
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error updating user: " . mysqli_error($conn);
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
            <h1> Edit User Details</h1>

            <div class="edit-user-form">
        <form method="POST">
            <label>Email:</label>
            <input type="email" name="email" value="<?= $user['email']; ?>" required>
            
            <label>Role:</label>
            <input type="text" name="role" value="<?= $user['role']; ?>" required>
            
            <label>Update Reason:</label>
            <textarea name="update_reason" required></textarea>

            <button type="submit" name="update">Update User</button>
        </form>
    </div>
        
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



