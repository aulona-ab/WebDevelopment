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

    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password, $role);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;  
            $_SESSION['role'] = $role; 

            echo "Login successful!<br>";


            echo "<script>
                    setTimeout(function() {
                        window.location.href = 'home.html';
                    }, 1000);  
                  </script>";
            exit; 
        } else {
            echo "Incorrect password. Please try again.";
            echo "<script>
            setTimeout(function() {
                window.location.href = 'login.html';
            }, 2000);  
          </script>";
        }
    } else {
        echo "No account found with this email.";
        echo "<script>
        setTimeout(function() {
            window.location.href = 'login.html';
        }, 2000);  
      </script>";
    }

    $stmt->close();
}
?>
