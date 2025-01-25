<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'bookstore';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $author = $conn->real_escape_string($_POST['author']);
    $price = $_POST['price'];
    $discount = isset($_POST['discount']) && $_POST['discount'] !== '' ? $_POST['discount'] : 0;

    $price = floatval($price);
    $discount = floatval($discount);

    $discounted_price = $discount > 0 ? $price - ($price * $discount / 100) : $price;

    $image = $_FILES['image'];
    $image_path = 'books/' . basename($image['name']);
    if (move_uploaded_file($image['tmp_name'], $image_path)) {
        $sql = "INSERT INTO books (title, author, price, discount, discounted_price, image_path) 
                VALUES ('$title', '$author', '$price', '$discount', '$discounted_price', '$image_path')";

        if ($conn->query($sql) === TRUE) {
        echo "<p style='
                font-family: Arial, sans-serif; 
                background-color: #4CAF50; 
                color: white; 
                padding: 10px; 
                border-radius: 5px; 
                text-align: center; 
                margin: 20px auto; 
                max-width: 400px; 
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);'>
                New book added successfully! 
                <a href='home.php' style='color: #ffffff; text-decoration: underline;'>View Home</a>
            </p>";

        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Failed to upload image.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Home </title>
    <link rel="stylesheet" href="page.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    
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
          <li><a href="./dashboard.php">Dashboard</a></li>

        </ul>
        <div class="header-search">
          <input type="search" id="header-search" placeholder="Search books, authors, ISBNs">
          <i class="fas fa-search"></i>
      </div>
      <ul> 
        <li><a href="./profile.php"><i class="fa fa-bookmark"></i></a></li>
        <li><a href="./home.php"><i class="fas fa-shopping-cart"></i></a></li>
        <li><a href="./login.php"><i class="fas fa-user"></i></a></li>
      </ul>
     

    </header>


    
        <div class="add-books-container">
        <form action="add_book.php" method="POST" enctype="multipart/form-data" style="margin-bottom: 20px;">
            <label for="title">Book Title:</label>
            <input type="text" id="title" name="title" required><br><br>

            <label for="author">Author Name:</label>
            <input type="text" id="author" name="author" required><br><br>

            <label for="price">Original Price (€):</label>
            <input type="number" step="0.01" id="price" name="price" required><br><br>

            <label for="discount">Discount Percentage (%):</label>
            <input type="number" id="discount" name="discount" min="0" max="100" ><br><br>

            <label for="image">Book Cover Image:</label>
            <input type="file" id="image" name="image" accept="image/*" required><br><br>

            <button type="submit">Add Book</button>
            </form>
        </div>
        
                   <div class="line">
                    <p><b>FIND YOUR PLACE AT LUMINOUS ONLINE BOOKSTORE</b>
                      Over 5 million books ready to ship, 3.6 million eBooks and 300,000 audiobooks to download right now! Curbside pickup available in most stores!</p>
                    <hr>
                   </div>

                   <div class="another-part">
                    <div class="about-us-small-part">
                      <h2>Get to Know Luminous Online</h2>
                    </div>
                   </div>

                    <div class="extra-part">
                    <div class="about-us-extra-part">
                      <h4>Buy Books Online at Luminous.com, America’s Favorite Bookstore</h4>
                      <p>No matter what you’re a fan of, from <a href="#">Fiction</a> to<a href="#"> Biography</a>, <a href="">Sci-Fi</a>, <a href="">Mystery</a>, <a href="">YA</a>, <a href="">Manga</a>, and more, 
                        LUMINOUS has the perfect book for you. Shop bestselling books from the <a href="#">NY Times Bestsellers list</a>, 
                        or get personalized recommendations to find something new and unique! Discover <a href="">kids books</a> for children of all ages 
                        including classics like <a href="#">Dr. Seuss</a> to modern favorites like the <a href="#">Dog Man series</a>.</p>
                        <button><a href="#">Read More</a> <span style="display: inline-block; transform: rotate(90deg); font-size: 18px; padding: 0 0 5px;">›</span>
                        </button>
                      </div>
                    </div>


   
  <div class="community">
    <h1 class="sentence1"> Join Our Book Lover Community</h1>
    <p class="sentence">Stay updated with the latest releases, exclusive offers, and book recommendations tailored for you!</p>
    <div class="buttons">
        <button class="learn-more">Learn More</button>
    </div>
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