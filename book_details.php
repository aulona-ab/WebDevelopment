<?php

session_start();

include 'db.php'; 

if (isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];

    // Fetch book details from the database
    $stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();

    if (!$book) {
        echo "Book not found.";
        exit;
    }
} else {
    echo "No book ID provided.";
    exit;
}

$discounted_price = $book['discounted_price'] > 0 ? $book['discounted_price'] : $book['price']; // If discounted_price is 0, use the original price


// Handle adding books to the cart
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id']; 
    $book_id = $_POST['book_id'];
    // Check if the book is already in the user's cart
    $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND book_id = ?");
    $stmt->bind_param("ii", $user_id, $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // Book is already in the cart, update the quantity
        $stmt = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND book_id = ?");
        $stmt->bind_param("ii", $user_id, $book_id);
        $stmt->execute();
    } else {
        // Book is not in the cart, insert a new row
        $stmt = $conn->prepare("INSERT INTO cart (user_id, book_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $book_id);
        $stmt->execute();
    }
    $stmt->close();

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
        Book added to Cart! 
        <a href='cart.php' style='color: #ffffff; text-decoration: underline;'>View Cart</a>
      </p>";

} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $book['title']; ?></title>
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
          <li><a href="./about-us.php">About Us</a></li>
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
              <?php endif; ?>     
 </ul>
     

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

    <br>
    <br>
    <div class="card-background">
    <div class="book-details-container">
        <!-- Book Image -->
        <div class="book-image">
            <img src="<?= $book['image_path']; ?>" alt="<?= $book['title']; ?>" />
        </div>
        

        <!-- Book Info -->
        <div class="book-info">
            <h1><?= $book['title']; ?></h1>
            <p><strong>Author:</strong> <?= $book['author']; ?></p>
            <p><strong>ISBN:</strong> <?= $book['isbn']; ?></p>
            <p><strong>Genre:</strong> <?= $book['genre']; ?></p>
            <p><strong>Summary:</strong> <?= $book['summary']; ?></p>
            <div class="price-details">
                <p><strong>Price:</strong> <span class="discount_price"> <?= number_format($book['discounted_price'], 2); ?> € </span></p>
                <?php if ($book['discount'] > 0): ?>
                    <p><strong>Original Price:</strong> <span class="original_price"><?= number_format($book['price'], 2); ?> €</span></p>
                <?php endif; ?>
            </div>


            <!-- Buttons -->
            <div class="details-buttons">
                <form method="POST" action="" class="details-add_to_cart">
                    <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                    <button type="submit" class="details-add-to-cart">Add to Cart</button>
                </form>
                <form action="purchase.php" method="POST">
            <!-- Send the book details to purchase.php -->
            <input type="hidden" name="total_price" value="<?php echo number_format($discounted_price, 2); ?>">
            <input type="hidden" name="original_price" value="<?php echo number_format($book['price'], 2); ?>">
            <input type="submit" value="Purchase">
        </form>
            </div>

        </div>
    </div>
    </div>

    <br>
    <br>

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



</body>
</html>
