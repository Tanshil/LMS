<?php
// Include database connection
include 'db_connect.php';

// Check if user_id is passed via GET parameter (for example, `user_id=1`)
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Fetch the user's loan history
    $query = "
        SELECT book_loans.loan_id, books.title, books.author, book_loans.loan_date, 
               book_loans.due_date, book_loans.return_date, book_loans.fine
        FROM book_loans
        JOIN books ON book_loans.book_id = books.book_id
        WHERE book_loans.user_id = $user_id
        ORDER BY book_loans.loan_date DESC
    ";
    $result = $conn->query($query);
} else {
    echo "User ID is missing!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Loan History</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f1f1f1;
        }

        td {
            background-color: #fafafa;
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
    <h1>User Loan History</h1>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Book Title</th>
                    <th>Author</th>
                    <th>Loan Date</th>
                    <th>Due Date</th>
                    <th>Return Date</th>
                    <th>Fine</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['title']; ?></td>
                        <td><?php echo $row['author']; ?></td>
                        <td><?php echo $row['loan_date']; ?></td>
                        <td><?php echo $row['due_date']; ?></td>
                        <td><?php echo $row['return_date'] ? $row['return_date'] : 'Not Returned'; ?></td>
                        <td><?php echo $row['fine'] ? '₹' . $row['fine'] : 'No Fine'; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No loan history found for this user.</p>
    <?php endif; ?>

    <a href="index.php" class="back-btn">Back to Dashboard</a>
</div>

</body>
</html>
