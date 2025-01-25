<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "bookstore"); 

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch Users
$users_query = "SELECT username, email, role, created_at FROM users";
$users_result = $conn->query($users_query);

// Fetch Books
$books_query = "SELECT title, author, price, discount, discounted_price FROM books";
$books_result = $conn->query($books_query);

// Fetch Subscribers
$subscribers_query = "SELECT email, subscription_date FROM subscribers";
$subscribers_result = $conn->query($subscribers_query);
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
            <a href="#users">Users</a>
            <a href="#books">Books</a>
            <a href="#subscribers">Subscribers</a>
            <br>
            <div class="logo">
                <img src="./dashboardd/dashboard_custom_logo.png" alt="Logo" style="width: 100%;">
            </div>
        </div>

        <!-- Main Content -->
        <div class="content" id="content">
            <h1> Daily Report</h1>

            <div id="overview" class="stats">
                <div class="stat-box">
                    <h3>500</h3>
                    <p>Books Sold</p>
                </div>
                <div class="stat-box">
                    <h3>$20,000</h3>
                    <p>Donations</p>
                </div>
                <div class="stat-box">
                    <h3>1,200</h3>
                    <p>Views</p>
                </div>
            </div>
            <div class="report-section">
        <!-- Users Section -->
        <div class="section">
            <h2>Users</h2>
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = $users_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $user['username'] ?></td>
                        <td><?= $user['email'] ?></td>
                        <td><?= $user['role'] ?></td>
                        <td><?= $user['created_at'] ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Books Section -->
        <div class="section">
            <h2>Books</h2>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Price</th>
                        <th>Discount</th>
                        <th>Discounted Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($book = $books_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $book['title'] ?></td>
                        <td><?= $book['author'] ?></td>
                        <td><?= $book['price'] ?></td>
                        <td><?= $book['discount'] ?>%</td>
                        <td><?= $book['discounted_price'] ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Subscribers Section -->
        <div class="section">
            <h2>Subscribers</h2>
            <table>
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Subscription Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($subscriber = $subscribers_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $subscriber['email'] ?></td>
                        <td><?= $subscriber['subscription_date'] ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
       

        <!-- Summary Section -->
        <div class="summary">
            <h2>Account</h2>
            <div class="balance">
                <h3>Your Balance</h3>
                <p>$10,632.00</p>
            </div>

            <div class="activity">
            <h3>Recent Transactions</h3>
                <ul>
                    <li>Sale: "The Great Adventure" - $12.99</li>
                    <li>Sale: "Mystery Unfolded" - $9.49</li>
                    <li>Refund: "Cooking Made Easy" - -$7.99</li>
                </ul>
            </div>
        </div>
    </div>

</body>
</html>