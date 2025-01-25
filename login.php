<?php
include 'db.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST['email']) || empty($_POST['password'])) {
        echo "Please fill in all fields.";
        exit;  
    }

    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username, $hashed_password, $role);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['role'] = $role;
            $_SESSION['username'] = $username;  

            if ($role == 'admin') {
                echo "Admin login successful!<br>";
                echo "<script>
                        setTimeout(function() {
                            window.location.href = 'dashboard.php'; // Redirect to admin dashboard
                        }, 1000);  
                    </script>";
            } else {
                echo "Login successful!<br>";
                echo "<script>
                        setTimeout(function() {
                            window.location.href = 'home.php';
                        }, 1000);  
                    </script>";
            }
            exit; 
        } else {
            echo "Incorrect password. Please try again.";
            echo "<script>
                    setTimeout(function() {
                        window.location.href = 'login.php';
                    }, 2000);  
                </script>";
        }
    } else {
        echo "No account found with this email.";
        echo "<script>
                setTimeout(function() {
                    window.location.href = 'login.php';
                }, 2000);  
            </script>";
    }

    $stmt->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Login </title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="script.js"></script>
</head>

<body>


    <form action="" method="post">
    <div class="login">
        <div class="login-form" id="loginForm">
            <h1>Sign In</h1>
            <div class="social-icons">
                <a href="#" class="social"><i class="fab fa-discord"></i></a>
                <a href="#" class="social"><i class="fab fa-google"></i></a>
                <a href="#" class="'social"><i class="fab fa-pinterest"></i></a>
            </div>
            <p>or use your account</p>
            <input type="email" id="email" name="email" placeholder="Email" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <a href="./ResetPassword">Forgot your password?</a>
            <a href="./register.php" style="margin-top: 1px;">No account? Sign up now!</a>
            <button type="submit">Sign In</button>
        </div>
    </div>
    </form>
    <p class="footer-credit">© 2024 Luminous. All rights reserved.</p>



    
</body>
</html>