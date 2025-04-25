<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // secure password hashing
    $role = $_POST['role'];

    $sql = "INSERT INTO Users (name, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $email, $password, $role);

    if ($stmt->execute()) {
        echo "<p style='color: green;'>✅ User registered successfully!</p>";
    } else {
        echo "<p style='color: red;'>❌ Error: " . $conn->error . "</p>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Registration - Library System</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f9f9f9; padding: 20px;">
    <div style="max-width: 500px; margin: auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <h2 style="text-align: center; color: #333;">👤 Register New User</h2>
        <form method="POST" action="register.php">
            <label>Name:</label><br>
            <input type="text" name="name" required style="width:100%; padding:8px; margin:8px 0;"><br>

            <label>Email:</label><br>
            <input type="email" name="email" required style="width:100%; padding:8px; margin:8px 0;"><br>

            <label>Password:</label><br>
            <input type="password" name="password" required style="width:100%; padding:8px; margin:8px 0;"><br>

            <label>Role:</label><br>
            <select name="role" required style="width:100%; padding:8px; margin:8px 0;">
                <option value="student">Student</option>
                <option value="librarian">Librarian</option>
                <option value="admin">Admin</option>
            </select><br>

            <button type="submit" style="width:100%; background: #4CAF50; color: white; border: none; padding: 10px 0; margin-top: 10px; cursor: pointer;">
                Register
            </button>
        </form>
        <p style="text-align: center; margin-top: 15px;">
            <a href="index.php">← Back to Dashboard</a>
        </p>
    </div>
</body>
</html>

