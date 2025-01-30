<?php

session_start(); 

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

// Fetch Messages
$contact_query = "SELECT * FROM contact_messages";
$contact_result = $conn->query($contact_query);


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
            <a href="#users">Users</a>
            <a href="">Books</a>
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
                        <td><a href="edit_user.php?id=<?= $user['username']; ?>" style=" text-decoration: none; color: green;">Edit</a></td>
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
                        <td><a href="edit_book.php?id=<?= $book['title']; ?>" style=" text-decoration: none; color: green;">Edit</a></td>
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

        <!-- Contact Form Submissions Section -->
<div class="section">
    <h2>Contact Form Submissions</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Submitted At</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($contact = $contact_result->fetch_assoc()): ?>
            <tr>
                <td><?= $contact['name'] ?></td>
                <td><?= $contact['email'] ?></td>
                <td><?= $contact['subject'] ?></td>
                <td><?= substr($contact['message'], 0, 1000) . '...' ?></td> 
                <td><?= $contact['submitted_at'] ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
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