<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Healthcare Information System Dyspicare</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@500&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <!-- Section Navbar -->
        <section>
            <nav class="navbar navbar-expand-lg navbar-light p-3">
                <div class="container">
                    <a class="navbar-brand" href="#"><img src="images/dispicare.png" alt=""></a>
                </div>
            </nav>
        </section>
    </header>

    <main>
        <!-- Section Login Form -->
        <section class="my-5">
            <div class="container">
                <div class="row d-flex justify-content-center align-items-center">
                    <div class="col-md-6">
                        <h2 class="text-center">Login</h2>
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
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-primary text-white mt-5">
        <div class="container d-flex justify-content-center align-items-center p-3">
            <p>©Dyspicare PTY LTD 2020. All rights reserved</p>
        </div>
    </footer>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
