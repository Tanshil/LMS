<?php
// Include database connection
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $book_id = $_POST['book_id'];

    // Check if the book is available
    $check_sql = "SELECT available_copies FROM Books WHERE book_id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $stmt->bind_result($available_copies);
    $stmt->fetch();
    $stmt->close();

    if ($available_copies > 0) {
        // Issue the book
        $loan_date = date('Y-m-d');
        $due_date = date('Y-m-d', strtotime('+15 days'));

        $insert_sql = "INSERT INTO Book_Loans (user_id, book_id, loan_date, due_date)
                       VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("iiss", $user_id, $book_id, $loan_date, $due_date);
        $stmt->execute();
        $stmt->close();

        // Decrease available copies
        $update_sql = "UPDATE Books SET available_copies = available_copies - 1 WHERE book_id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("i", $book_id);
        $stmt->execute();
        $stmt->close();

        echo "<script>alert('Book issued successfully!');</script>";
    } else {
        echo "<script>alert('Sorry, no copies available.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Issue Book</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 50%;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        input, select {
            width: 80%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .back-btn {
            display: block;
            margin-top: 20px;
            text-align: center;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }

        .back-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Issue a Book</h1>

    <form method="POST" action="issue_book.php">
        <input type="number" name="user_id" placeholder="User ID" required>
        <input type="number" name="book_id" placeholder="Book ID" required>
        <button type="submit">Issue Book</button>
    </form>

    <a href="index.php" class="back-btn">Back to Dashboard</a>
</div>

</body>
</html>

