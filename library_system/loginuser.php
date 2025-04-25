<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Library Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
            width: 350px;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .back-link {
            text-align: center;
            margin-top: 15px;
        }

        .back-link a {
            text-decoration: none;
            color: #007bff;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Library Login</h2>
        <form action="login.php" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <div class="back-link">
            <p>← <a href="index.php">Back to Dashboard</a></p>
        </div>
        <div style="margin-top: 10px; color: red;">
            <?php
            include 'db_connect.php';
            session_start();

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $email = $_POST['email'];
                $password = $_POST['password'];

                $sql = "SELECT * FROM Users WHERE email = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $email);
                $stmt->execute();
                
                $result = $stmt->get_result();
                if ($row = $result->fetch_assoc()) {
                    if (password_verify($password, $row['password'])) {
                        $_SESSION['user_id'] = $row['user_id'];
                        $_SESSION['role'] = $row['role'];
                        echo "<script>alert('Login Successful!'); window.location.href='index.php';</script>";
                    } else {
                        echo "❌ Invalid password.";
                    }
                } else {
                    echo "❌ No user found with this email.";
                }

                $stmt->close();
            }
            ?>
        </div>
    </div>
</body>
</html>

