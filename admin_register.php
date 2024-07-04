<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'dbCon.php'; // Include the database connection

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($password === $confirm_password) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO admins (email, password) VALUES (:email, :password)");
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':password', $hashed_password);

        if ($stmt->execute()) {
            $message = "Admin registered successfully.";
        } else {
            $message = "Error: " . $stmt->errorInfo()[2];
        }

        $stmt->closeCursor();
    } else {
        $message = "Passwords do not match.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        h2 {
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        label, input {
            margin: 5px 0;
        }
        .message {
            color: green;
            margin-top: 10px;
        }
        a {
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h2>Register Admin</h2>
    <form method="POST" action="admin_register.php">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        <input type="submit" value="Register">
    </form>
    <div class="message"><?php echo $message; ?></div>
    <a href="admin_login.php">Back to Admin Login</a>
</body>
</html>
