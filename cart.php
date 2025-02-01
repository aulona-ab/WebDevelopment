<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<script>
            alert('You need to log in to view your cart.');
            window.location.href = 'login.php';
          </script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Database connection
$conn = new mysqli('localhost', 'root', '', 'bookstore');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the cart items for the logged-in user
$stmt = $conn->prepare("
    SELECT cart.id, books.title, books.discount, books.price, books.discounted_price, books.image_path, cart.quantity 
    FROM cart 
    JOIN books ON cart.book_id = books.id 
    WHERE cart.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$total_price = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $cart_id = $_POST['cart_id'];
    $action = $_POST['action'];

    if ($action === 'decrease') {
        // Decrease the quantity
        $stmt = $conn->prepare("UPDATE cart SET quantity = quantity - 1 WHERE id = ? AND quantity > 1");
        $stmt->bind_param("i", $cart_id);
        $stmt->execute();
    } elseif ($action === 'increase') {
        // Increase the quantity
        $stmt = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE id = ?");
        $stmt->bind_param("i", $cart_id);
        $stmt->execute();
    } elseif ($action === 'remove') {
        // Remove the book from the cart
        $stmt = $conn->prepare("DELETE FROM cart WHERE id = ?");
        $stmt->bind_param("i", $cart_id);
        $stmt->execute();
    }

    header("Location: cart.php"); 
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="page.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
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
    
    <h1 style="text-align: center; font-size: 27px; color: #333;">Your Cart</h1>
    <div class="cart-container">
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Discounted Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
        <?php
        // Get the original price (for display) and the discounted price (for subtotal)
        $original_price = $row['price'];
        $discounted_price = $row['discounted_price'] ? $row['discounted_price'] : $original_price;
        $subtotal = $discounted_price * $row['quantity'];
        $total_price += $subtotal;
        ?>
        <tr class="cart-item">
            <td class="cart-item-image">
                <img src="<?= htmlspecialchars($row['image_path']) ?>" alt="Book Image" class="book-image">
            </td>
            <td class="cart-item-title"><?= htmlspecialchars($row['title']) ?></td>
            <td class="cart-item-price">€<?= number_format($original_price, 2) ?></td> <!-- Display original price -->
            <td class="cart-item-quantity">
                <div class="quantity-btns">
                    <form method="POST" action="cart.php" class="quantity-form">
                        <input type="hidden" name="cart_id" value="<?= $row['id'] ?>">
                        <input type="hidden" name="action" value="decrease">
                        <button type="submit" name="update" value="decrease" class="quantity-decrease-btn">-</button>
                    </form>
                    <span class="quantity-value"><?= $row['quantity'] ?></span>
                    <form method="POST" action="cart.php" class="quantity-form">
                        <input type="hidden" name="cart_id" value="<?= $row['id'] ?>">
                        <input type="hidden" name="action" value="increase">
                        <button type="submit" name="update" value="increase" class="quantity-increase-btn">+</button>
                    </form>
                </div>
            </td>
            <td class="cart-item-subtotal">€<?= number_format($subtotal, 2) ?></td> <!-- Subtotal is calculated using discounted price -->
            <td class="cart-item-remove">
                <form method="POST" action="cart.php" class="remove-form">
                    <input type="hidden" name="cart_id" value="<?= $row['id'] ?>">
                    <input type="hidden" name="action" value="remove">
                    <button type="submit" name="update" value="remove" class="remove-btn">Remove</button>
                </form>
            </td>
        </tr>
    <?php endwhile; ?>
</tbody>
            </table>
            <div class="total-section">
                <h2>Total Price: €<?= number_format($total_price, 2) ?></h2>
                <form action="purchase.php" method="POST">
    <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">
    <input type="submit" value="Proceed to Purchase">
</form>            </div>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
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
            <button><a href="./about-us.php">Read More</a> <span style="display: inline-block; transform: rotate(90deg); font-size: 18px; padding: 0 0 5px;">›</span>
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
