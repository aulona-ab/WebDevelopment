<?php
include 'db.php';  

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];

    if ($password !== $confirm_password) {
        echo "Passwords do not match!";
        exit;
    }

    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($email_count);
    $stmt->fetch();
    
    if ($email_count > 0) {
        echo "Email is already registered.";
        exit;
    }
    $stmt->close();

    $password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $password, $role);

    if ($stmt->execute()) {
        echo "<script>
        window.location.href = 'successful.php';
      </script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Register </title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
    

    <form action="" method="POST" id="registerform">
        <div class="register">
            <div class="register-form" id="loginForm">
                <h1>Enter your details</h1>
                <input type="text" name="username" id="username" placeholder="Username" required>
                <input type="email" name="email" id="email" placeholder="Email" required>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
                <select name="role" id="role" required>
                    <option value="">Select Role</option>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
                <p id="not-link">───────────&nbsp;&nbsp;or&nbsp;&nbsp;───────────</p>
                <div class="social-icons">
                    <a href="#" class="social"><i class="fab fa-discord"></i></a>
                    <a href="#" class="social"><i class="fab fa-google"></i></a>
                    <a href="#" class="social"><i class="fab fa-pinterest"></i></a>
                </div>
                <div class="register-img" id="loginImg">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="terms" name="accept_terms" required>
                        <label class="form-check-label" for="terms">Accept Luminous Terms and Conditions</label>
                    </div>
                    <button type="submit" >Sign Up</button>
                </div>
            </div>
        </div>
    </form>
    <p class="footer-credit">© 2024 Luminous. All rights reserved.</p>

</body>
</html>