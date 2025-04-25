<?php
include 'db_connect.php';

// Handle form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['returnBook'])) {
    $user_id = $_POST['user_id'];
    $book_id = $_POST['book_id'];
    $return_date = date('Y-m-d');

    $sql = "SELECT * FROM book_loans 
            WHERE user_id = $user_id AND book_id = $book_id AND return_date IS NULL";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $loan = $result->fetch_assoc();
        $due_date = $loan['due_date'];
        $loan_id = $loan['loan_id'];

        $fine = 0;
        if ($return_date > $due_date) {
            $days_late = (strtotime($return_date) - strtotime($due_date)) / (60 * 60 * 24);
            $fine = $days_late * 10;
        }

        $update = "UPDATE book_loans 
                   SET return_date = '$return_date', fine = $fine 
                   WHERE loan_id = $loan_id";
        $conn->query($update);

        $conn->query("UPDATE books SET available_copies = available_copies + 1 WHERE book_id = $book_id");

        $message = "✅ Book returned successfully. Fine: ₹$fine";
    } else {
        $message = "❌ No active issue record found for this user and book.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Return Book</title>
</head>
<body style="margin: 0; font-family: 'Segoe UI', sans-serif; background: #f4f4f4;">

<div style="max-width: 600px; margin: 50px auto; padding: 30px; background: #fff; border-radius: 12px; box-shadow: 0 0 20px rgba(0,0,0,0.1); text-align: center;">
    <h1 style="margin-bottom: 20px; font-size: 28px; color: #333;">🔁 Return Book</h1>

    <form method="POST" action="return_book.php" style="display: flex; flex-direction: column; gap: 15px; margin-top: 20px;">
        <input type="number" name="user_id" placeholder="User ID" required
               style="padding: 10px; border: 1px solid #ccc; border-radius: 6px; font-size: 16px;">
        <input type="number" name="book_id" placeholder="Book ID" required
               style="padding: 10px; border: 1px solid #ccc; border-radius: 6px; font-size: 16px;">
        <button type="submit" name="returnBook"
                style="padding: 12px; background-color: #007bff; color: white; border: none; border-radius: 6px; font-size: 16px; cursor: pointer;">
            Return Book
        </button>
    </form>

    <?php if ($message): ?>
        <p style="margin-top: 20px; font-weight: bold; font-size: 16px; color: #444;"><?php echo $message; ?></p>
    <?php endif; ?>

    <a href="index.php"
       style="display: inline-block; margin-top: 20px; color: #007bff; text-decoration: none; font-weight: 500;">
        🔙 Back to Dashboard
    </a>
</div>

</body>
</html>
