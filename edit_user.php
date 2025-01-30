<?php
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

    $updateQuery = "UPDATE users SET email='$email', role='$role' WHERE username='$username'";
    if (mysqli_query($conn, $updateQuery)) {
        echo "User updated successfully!";
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error updating user: " . mysqli_error($conn);
    }
}
?>

<form method="POST">
    <label>Email:</label>
    <input type="email" name="email" value="<?= $user['email']; ?>" required>
    
    <label>Role:</label>
    <input type="text" name="role" value="<?= $user['role']; ?>" required>
    
    <button type="submit" name="update">Update User</button>
</form>
