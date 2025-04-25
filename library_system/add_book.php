<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $isbn = $_POST['isbn'];
    $category = $_POST['category'];
    $total_copies = (int)$_POST['total_copies'];
    $available_copies = $total_copies;

    $sql = "INSERT INTO Books (title, author, isbn, category, available_copies, total_copies) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssii", $title, $author, $isbn, $category, $available_copies, $total_copies);

    if ($stmt->execute()) {
        echo "Book added successfully.";
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
}
?>
