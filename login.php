<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Healthcare Information System Dyspicare</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Mulish', sans-serif;
            background: linear-gradient(135deg, #6E8EF7, #8EC5FC);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .navbar {
            background: transparent !important;
            position: absolute;
            top: 20px;
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .navbar-brand img {
            height: 80px;
        }

        .login-container {
            background: #ffffff;
            padding: 60px 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1.2s ease-in-out;
            text-align: center;
            max-width: 500px;
            width: 100%;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-container h2 {
            margin-bottom: 30px;
            font-weight: 700;
            color: #333;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-control {
            border-radius: 30px;
            padding: 15px 20px;
            font-size: 16px;
        }

        .btn {
            border-radius: 30px;
            padding: 12px 20px;
            font-size: 16px;
            margin: 10px 0;
        }

        .btn-primary {
            background-color: #6E8EF7;
            border: none;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #5a78d1;
        }

        .btn-secondary {
            background-color: #8EC5FC;
            border: none;
            transition: background-color 0.3s;
        }

        .btn-secondary:hover {
            background-color: #76a7d9;
        }

        footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            text-align: center;
            padding: 10px;
            background: rgba(0, 0, 0, 0.1);
            color: #fff;
        }
    </style>
</head>
<body>
    <header>
        <!-- Section Navbar -->
        <nav class="navbar navbar-light">
            <a class="navbar-brand mx-auto" href="#"><img src="images/dispicare.png" alt="Dyspicare Logo"></a>
        </nav>
    </header>

    <main>
        <!-- Section Login Form -->
        <div class="container">
            <div class="login-container">
                <h2>Login</h2>
                <form action="loginbend.php" method="post">
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Login</button>
                    <!-- Register Link -->
                    <a href="register.php" class="btn btn-secondary mt-3">Register</a>
                    <a href="admin_login.php" class="btn btn-secondary mt-3">Admin</a>
                </form>
            </div>
        </div>
    </main>

    <footer>
        <p>©Dyspicare PTY LTD 2024. All rights reserved</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.amazonaws.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
