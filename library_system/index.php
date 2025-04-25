<?php
// Include database connection
include 'db_connect.php';

// Fetch statistics for the dashboard
$response = array();
$response['totalBooks'] = $conn->query("SELECT COUNT(*) as total FROM books")->fetch_assoc()['total'];
$response['totalUsers'] = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];
$response['booksIssued'] = $conn->query("SELECT COUNT(*) as total FROM book_loans")->fetch_assoc()['total'];
$response['booksReturned'] = $conn->query("SELECT COUNT(*) as total FROM book_loans WHERE return_date IS NOT NULL")->fetch_assoc()['total'];
$response['totalFine'] = $conn->query("SELECT SUM(fine) as total FROM book_loans WHERE fine IS NOT NULL")->fetch_assoc()['total'];

// Add book functionality (If the form is submitted)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addBook'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $category = $_POST['category'];
    $available_copies = $_POST['available_copies'];

    // Insert into database
    $query = "INSERT INTO Books (title, author, category, available_copies) VALUES ('$title', '$author', '$category', $available_copies)";
    if ($conn->query($query)) {
        echo "New book added successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>📚 Library Management System - Admin Dashboard</h1>
        </header>

        <!-- Admin Dashboard Stats -->
        <div class="stats">
            <div class="card">
                <h2>Total Books</h2>
                <p><?php echo $response['totalBooks']; ?></p>
            </div>
            <div class="card">
                <h2>Total Users</h2>
                <p><?php echo $response['totalUsers']; ?></p>
            </div>
            <div class="card">
                <h2>Books Issued</h2>
                <p><?php echo $response['booksIssued']; ?></p>
            </div>
            <div class="card">
                <h2>Books Returned</h2>
                <p><?php echo $response['booksReturned']; ?></p>
            </div>
            <div class="card">
                <h2>Total Fine Collected</h2>
                <p>₹<?php echo $response['totalFine']; ?></p>
            </div>
        </div>

        <!-- Add Book Form -->
        <h2>Add New Book</h2>
        <form action="index.php" method="POST">
            <input type="text" name="title" placeholder="Book Title" required>
            <input type="text" name="author" placeholder="Author Name" required>
            <input type="text" name="category" placeholder="Category" required>
            <input type="number" name="available_copies" placeholder="Available Copies" required>
            <button type="submit" name="addBook">Add Book</button>
        </form>

        <!-- Navigation Links -->
        <nav>
            <a href="issue_book.php">✅ Issue Book</a>
            <a href="return_book.php">🔁 Return Book</a>
            <a href="userhistory.php">🕓 User History</a>
            <a href="register_user.php">📝 Register User</a> 
        </nav>
    </div>

    <script src="dashboard.js"></script>
</body>
</html>
