<!DOCTYPE html>
<html lang="en">

<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="styles.css">

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Treat Better Online Treatment</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <section>
            <nav class="navbar navbar-expand-lg navbar-light p-3">
                <div class="container">
                    <a class="navbar-brand" href="#"><img src="images/dispicare.png" alt=""></a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="login.php">Login</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </section>
    </header>

    <main>
        <section class="bg-left">
            <div class="container">
                <div class="row gx-5 d-flex justify-content-center align-items-center">
                    <div class="col-md-6">
                        <div class="register-form">
                            <h2 class="text-center">Register</h2>
                            <form action="registerbend.php" method="post">
                                <div class="mb-3">
                                    <input type="text" class="form-control" name="name" placeholder="Name" required>
                                </div>
                                <div class="mb-3">
                                    <input type="email" class="form-control" name="email" placeholder="Email" required>
                                </div>
                                <div class="mb-3">
                                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">Register</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer>
    </footer>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
