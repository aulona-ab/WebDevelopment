<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'bookstore');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT username, created_at FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $created_at);

if ($stmt->fetch()) {
} else {
    echo "Error: Could not fetch user details.";
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="page.css">
    <style>

      body{
       background-image: url(./wallpaper/lule.jpg);
       background-size: contain;
      }
    </style>
</head>
<body>


  <header>
    <div class="logo">
        <img src="./Logo/7-removebg-preview.png">
    </div>
    <ul>
      <li><a href="./home.php">Home</a></li>
      <li><a href="./contact.php">Contact</a></li>
      <li><a href="./home.php">About Us</a></li>
      <li><a href="./home.php">Shop</a></li>
      <li><a href="./home.php">Blog</a></li>
      <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
    <li><a href="./dashboard.php">Dashboard</a></li>
<?php endif; ?>
    </ul>
    <div class="header-search">
      <input type="search" id="header-search" placeholder="Search books, authors, ISBNs">
      <i class="fas fa-search"></i>
  </div>
  <ul> 
    <li><a href="./bookmark.php"><i class="fa fa-bookmark"></i></a></li>
    <li><a href="./cart.php"><i class="fas fa-shopping-cart"></i></a></li>
    <?php if (isset($_SESSION['user_id'])): ?>
        <li><a href="./profile.php"><i class="fas fa-user"></i></a></li>
              <?php else: ?>
        <li><a href="./login.php"><i class="fas fa-user"></i></a></li>
              <?php endif; ?>    </ul>
 

</header>

<div class="genre-container">
    <nav class="genre-bar">
        <ul>
          <li><a href="#romance">Gift Cards</a></li>
          <li><a href="#romance">Best Sellers</a></li>
          <li><a href="#romance">New Arrivals</a></li>
          <li><a href="#romance">Top Picks</a></li>
          <li><a href="#romance">Free Shipping</a></li>
          <li><a href="#non-fiction">Author Events</a></li>
          <li><a href="#romance">YA</a></li>
          <li><a href="#mystery">Audiobooks</a></li>
          <li><a href="#fantasy">eBooks</a></li>
          <li><a href="#sci-fi">Fiction</a></li>
          <li><a href="#non-fiction">Non-Fiction</a></li>
          <li><a href="#non-fiction">Kids</a></li>
        </ul>
      </nav>
    </div>


    <div class="welcome-message">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <p>Dive into your reading journey, manage your preferences, and explore your favorite stories all in one place.</p>
    </div>

    <main class="profile-container">

        <aside class="settings">
            <h2>Settings</h2>
            <ul>
                <li>
                    <a href="#">Profile Settings</a>
                    <ul class="sub-settings">
                        <li><a href="#">Edit Profile</a></li>
                    </ul>
                </li>
                        <li>
                    <a href="#">Account Settings</a>
                    <ul class="sub-settings">
                        <li><a href="#">Change Password</a></li>
                    </ul>
                </li>
                <li><a href="./contact.php">Help & Support</a></li>
        
                <li><a href="./log_out.php">Log Out</a></li>
                <li><a href="./delete_account.php" onclick="return confirm('Are you sure you want to delete this account? This action cannot be undone.');">Delete Account</a></li>
                </aside>


        <section class="profile-details">
            <div class="profile-header">
                <img src="./Logo/blank-profile-picture-973460_1280.webp" alt="Profile Picture" class="profile-pic">
                <div class="info">
                    <h2><?php echo htmlspecialchars($_SESSION['username']); ?></h2>
                    <p>Member Since: <?php echo date("F j, Y", strtotime($created_at)); ?></p>
                </div>
            </div>

            <div class="purchase-history">
                <h2>Purchase History</h2>
                <ul>
                    <li><a href="#"><strong>Order #12345:</strong></a> The Great Gatsby - $10.99</li>
                    <li><a href="#"><strong>Order #12345:</strong></a> 1984 - $8.99</li>
                    <li><a href="#"><strong>Order #12345:</strong></a> Pride and Prejudice - $12.49</li>
                </ul>
            </div>
    
            <section class="personal-stats">
                <h3>Your Stats</h3>
                <p>Books Purchased: <strong>15</strong></p>
                <p>Books Reviewed: <strong>7</strong></p>
                <p>Favorite Genre: <strong>Fiction</strong></p>
            </section>
        </section>
    </main>



    <div class="ads-section">
      <img src="./Posters/poster-poster.jpg" alt="4">
    </div>



      <footer>
        <div class="footer-container">
          <div class="footer-section-links">
            <h3>SERVICES</h3>
              <ul>
                <li><a href="./home.html">Affiliate Program</a></li>
                <li><a href="./home.html">Publisher & Author <br>
                  Guidelines</a></li>
                <li><a href="./home.html">Order Discounts</a></li>
                <li><a href="./home.html">Mobile App</a></li>
                <li><a href="./home.html">Membership</a></li>
                <li><a href="./home.html">Mastercard</a></li>
                <li><a href="./home.html">Bookfairs</a></li>
                <li><a href="./home.html">Press</a></li>
              </ul>
          </div>
          <div class="footer-section-links">
            <h3>ABOUT US</h3>
              <ul>
                <li><a href="./home.html">About Luminous</a></li>
                <li><a href="./home.html">Contact</a></li>
                <li><a href="./home.html">FAQs</a></li>
                <li><a href="./home.html">Blog</a></li>
                <li><a href="./home.html">Support</a></li>
              </ul>
          </div>
          <div class="footer-section-links">
            <h3>CATEGORY</h3>
              <ul>
                <li><a href="./home.html">Best Sellers</a></li>
                <li><a href="./home.html">Books</a></li>
                <li><a href="./home.html">Fiction</a></li>
                <li><a href="./home.html">Non-Fiction</a></li>
                <li><a href="./home.html">eBooks</a></li>
                <li><a href="./home.html">Audiobooks</a></li>
                <li><a href="./home.html">YA</a></li>
                <li><a href="./home.html">Kids</a></li>
              </ul>
          </div>
          <div class="footer-section-links">
            <h3>HELP CENTER</h3>
              <ul>
                <li><a href="./home.html">Holiday Shipping</a></li>
                <li><a href="./home.html">Shipping & Return</a></li>
                <li><a href="./home.html">Store Pickup</a></li>
                <li><a href="./home.html">Order Status</a></li>
                <li><a href="./home.html">Gift Cards</a></li>
              </ul>
          </div>
          <div class="footer-section-links">
            <h3>STAY CONNECTED</h3>
              <ul>
                <li><a href="./home.html"><i class="fab fa-twitter"></i> Twitter</a></li>
                <li><a href="./home.html"><i class="fab fa-instagram"></i> Instagram</a></li>
                <li><a href="./home.html"><i class="fab fa-discord"></i> Discord</a></li>
                <li><a href="./home.html"><i class="fab fa-pinterest"></i> Pinterest</a></li>
                <li><a href="./home.html"><i class="fab fa-tiktok"></i> TikTok</a></li>
              </ul>
          </div>
          <div class="footer-section-subscribe">
                <h3>Subscribe</h3>
                <form action="subscribe.php" method="POST" class="sub-form">
                    <input type="email" name="email" placeholder="Your Email Here" required>
                    <button type="submit" name="subscribe">Join</button>
                </form>
            <p>Submit your email address to receive Luminous offers <br> & updates.
              You can view Luminous's <a href="#">Privacy Policy</a> here. <br>Unsubscribe from our emails at any time.</p> <br>
              <h5>Rewards</h5>
              <p>Enroll in Rewards for <b>FREE</b>. Watch your savings add up!</p>
              <p><a href="#">Learn More</a>   <a href="#">Sign Up Free</a></p>
          </div>
        </div>
        <hr>
        <br>
        <div class="footer-bottom-section">
          <p>© 2024 Luminous. All rights reserved. <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a> | <a href="#">Cookie Settings</a></p>
        </div>
      </footer>



<script src="script.js"></script>
</body>
</html>