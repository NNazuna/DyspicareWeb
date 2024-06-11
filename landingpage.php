<?php
// Informasi koneksi ke database
$host = 'localhost';
$dbname = 'dyspicare';
$username = 'root';
$password = '';

// Membuat koneksi ke database menggunakan PDO
try {
    $koneksi = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $koneksi->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch(PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
}

$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Healthcare Information System Dyspicare</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@500&display=swap" rel="stylesheet">
    <style>
        .hidden-article {
            display: none;
        }
    </style>
     
</head>



<body>
    <header>
        <!-- section Navbar -->
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
                                <a class="nav-link active" aria-current="page" href="#">Home</a>
                            </li>
                            <?php if ($email): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="#"><?php echo htmlspecialchars($email); ?></a>
                                </li>
                            <?php endif; ?>
                            <!-- <li class="nav-item">
                                <a class="nav-link" href="#">Find Doctor</a>
                            </li> -->
                            <!-- <li class="nav-item">
                                <a class="nav-link" href="#">Apps</a>
                            </li> -->
                            <li class="nav-item">
                                <a class="nav-link" href="#">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">History</a>
                            </li>
                            <!-- <li class="nav-item">
                                <a class="nav-link" href="#">Education</a>
                            </li> -->
                            <li class="nav-item">
                                <a class="nav-link" href="logoutbend.php">Logout</a>
                    


                        </ul>
                    </div>
                </div>
            </nav>
        </section>


        <!-- Section Intro -->
        <section class="my-5 bg-left">
            <div class="container ">
                <div class="row gx-5 d-flex justify-content-center align-items-center">
                    <div class="col d-flex justify-content-center align-items-center">
                        <div>
                            <h1>Virtual Healthcare
                                Dyspicare</h1>
                            <p class="para-color py-2">Dyspicare provides a progressive and affordable information system to minimize dyspepsia,
                                 which can be accessed via mobile and online for everyone.</p>
                            <button type="button" class="btn btn-primary rounded-pill">Check Today</button>

                        </div>
                    </div>
                    <div class="col">
                        <div><img class="w-100" src="images/intro-img.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </header>

    <main>
        <!-- our service section -->
        <section class="bg-service  d-flex flex-column justify-content-center align-items-center"
            style="margin-top: 190px; margin-bottom: 80px;">

            <div class="d-flex flex-column justify-content-center align-items-center">
                <h4 class="fw-bold">Our Services</h4>
                <hr class="h-row">
                <p class="para-color text-center" style="max-width: 952px;">We
                    provide to you the best choiches for you. Adjust
                    it to your health needs
                    and
                    make sure your
                    undergo
                    treatment with our highly qualified doctors you can consult with us which type of service is
                    suitable
                    for your health</p>
            </div>

            <!--  card for sesrvice -->
            <div class="container ">
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 my-5">

                    <!-- <div class="col">
                        <div class="d-flex flex-column align-items-center border-card p-3">
                            <div class="mt-5">
                                <img src="images/card1.png" class="card-img-top w-100" alt="...">
                            </div>

                            <div class="card-body">
                                <h5 class="card-title">Search doctor</h5>
                                <p class="card-text para-color">Choose your doctor from thousands of specialist,
                                    general,
                                    and trusted hospitals</p>
                            </div>
                        </div>
                    </div> -->
                    <!-- <div class="col">
                        <div class="d-flex flex-column align-items-center border-card p-3">
                            <div class="mt-5">
                                <img src="images/card2.png" class="card-img-top w-100" alt="...">
                            </div>

                            <div class="card-body">
                                <h5 class="card-title">Online pharmacy</h5>
                                <p class="card-text para-color">Buy your medicines with our mobile application with a
                                    simple
                                    delivery system</p>
                            </div>
                        </div>
                    </div> -->
                    <div class="col">
                        <div class="d-flex flex-column align-items-center border-card p-3">
                            <div class="mt-5">
                                <img src="images/card3.png" class="card-img-top w-100" alt="...">
                            </div>

                            <div class="card-body">
                                <a href="radar_chart.php"> Rekap data </a>
                                <p class="card-text para-color">Melihat statistika data anda </p>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col">
                        <div class="d-flex flex-column align-items-center border-card p-3">
                            <div class="mt-5">
                                <img src="images/card4.png" class="card-img-top w-100" alt="...">
                            </div>

                            <div class="card-body">
                                <h5 class="card-title">Details info</h5>
                                <p class="card-text para-color">Free consultation with our trusted doctors and get the
                                    best
                                    recomendations</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="d-flex flex-column align-items-center border-card p-3">
                            <div class=" mt-5">
                                <img src="images/card5.png" class="card-img-top w-100" alt="...">
                            </div>

                            <div class="card-body">
                                <h5 class="card-title">Emergency care</h5>
                                <p class="card-text para-color">You can get 24/7 urgent care for yourself or your
                                    children
                                    and your
                                    lovely family.</p>
                            </div>
                        </div>
                    </div> -->
                    <div class="col">
                        <div class="d-flex flex-column align-items-center border-card p-3">
                            <div class="mt-5">
                                <img src="images/card6.png" class="card-img-top w-100" alt="...">
                            </div>

                            <div class="card-body">
                                <a href="form.php"> Tracking </a>
                                <p class="card-text para-color">Track and save your medical history and health data. You
                                    can
                                    access data any time anywhere.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="d-flex flex-column align-items-center border-card p-3">
                            <div class="mt-5">
                                <img src="images/card6.png" class="card-img-top w-100" alt="...">
                            </div>

                            <div class="card-body">
                                 <a href="edukasi.php"> Education </a>
                                <p class="card-text para-color">Learn and find out more about efforts to minimize 
                                    dyspepsia and the dangers of ignoring it.</p>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="d-flex justify-content-center align-items-center ">
                    <button type="button" class=" btn btn-primary rounded-pill bg-light text-primary px-4">Learn
                        More</button>
                </div>


        </section>


        <!-- leading healthcare section -->

        <section class="bg-left" style="margin-top: 190px; margin-bottom: 80px;">

            <div class="container ">
                <div class="row gx-5 d-flex justify-content-center align-items-center">
                    <div class="col">
                        <div><img class="w-100" src="images/provider-img.png" alt="">
                        </div>
                    </div>

                    <div class="col d-flex justify-content-center align-items-center">


                        <div>
                            <h1>Leading healthcare providers</h1>
                            <hr class="h-row">
                            <p class="para-color py-2">Dyspicare provides progressive, and affordable healthcare,
                                accessible on mobile and online for everyone. To us, it’s not just work. We take pride
                                in the solutions we deliver</p>
                            <button type="button" class=" btn btn-primary rounded-pill bg-light text-primary px-4">Learn
                                More</button>


                        </div>
                    </div>

                </div>
            </div>

        </section>

        <!-- download app section -->
        <section class="bg-left" style="margin-top: 190px; margin-bottom: 80px;">

            <div class="container ">
                <div class="row gx-5 d-flex justify-content-center align-items-center">

                    <div class="col d-flex justify-content-center align-items-center">

                        <div>
                            <h1>Download our
                                mobile apps</h1>
                            <hr class="h-row ">
                            <p class="para-color py-2">Our dedicated patient engagement app and
                                web portal allow you to access information instantaneously (no tedeous form, long calls,
                                or administrative hassle) and securely</p>
                            <button type="button"
                                class=" btn btn-primary rounded-pill bg-light text-primary px-4">Download</button>


                        </div>
                    </div>


                    <div class="col">
                        <div><img class="w-100" src="images/provider-img.png" alt="">
                        </div>
                    </div>

                </div>
            </div>

        </section>


        <!-- customer say -->
        <!-- <section class="bg-left" style="margin-top: 190px; margin-bottom: 80px;">

            <div class="container text-white bg-primary p-5 border-rad">
                <div class="d-flex flex-column justify-content-center align-items-center">
                    <h4>What our customer are saying</h4>
                    <hr class="h-row">
                </div>

                <div class="row gx-5 d-flex justify-content-center align-items-center">

                    <div class="col d-flex justify-content-center align-items-center">
                        <div>
                            <img class=" rounded-circle"" src=" images/mypic.png" alt="">
                        </div>
                        <div class="d-flex flex-column ms-3">
                            <h5>Md. Asaduzzaman</h5>
                            <h6>Web Developer</h6>
                        </div>
                    </div>

                    <div class="col">

                        <p>“Our dedicated patient engagement app and
                            web portal allow you to access information instantaneously (no tedeous form, long calls,
                            or administrative hassle) and securely”</p>
                    </div>

                </div>
            </div>
            <div class="mt-3">
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>

        </section> -->

        <section class="bg-left" style="margin-top: 190px; margin-bottom: 80px;">
            <div class="container text-white bg-primary p-5 border-rad">
                <div class="d-flex flex-column justify-content-center align-items-center">
                    <h4>What our customer are saying</h4>
                    <hr class="h-row">
                </div>
                
                <div id="testimonialCarousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <div class="row gx-5 d-flex justify-content-center align-items-center">
                                <div class="col d-flex justify-content-center align-items-center">
                                    <div>
                                        <img class="rounded-circle" src="images/mypic.png" alt="">
                                    </div>
                                    <div class="d-flex flex-column ms-3">
                                        <h5>Mr. Jordan Petrovski</h5>
                                        <h6>Web Developer</h6>
                                    </div>
                                </div>
                                <div class="col">
                                    <p>“Our dedicated patient engagement app and web portal allow you to access information instantaneously (no tedeous form, long calls, or administrative hassle) and securely”</p>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="row gx-5 d-flex justify-content-center align-items-center">
                                <div class="col d-flex justify-content-center align-items-center">
                                    <div>
                                        <img class="rounded-circle" src="images/mypic.png" alt="">
                                    </div>
                                    <div class="d-flex flex-column ms-3">
                                        <h5>Customer 2</h5>
                                        <h6>Profession 2</h6>
                                    </div>
                                </div>
                                <div class="col">
                                    <p>“Testimonial text for customer 2.”</p>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="row gx-5 d-flex justify-content-center align-items-center">
                                <div class="col d-flex justify-content-center align-items-center">
                                    <div>
                                        <img class="rounded-circle" src="images/mypic.png" alt="">
                                    </div>
                                    <div class="d-flex flex-column ms-3">
                                        <h5>Customer 3</h5>
                                        <h6>Profession 3</h6>
                                    </div>
                                </div>
                                <div class="col">
                                    <p>“Testimonial text for customer 3.”</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#testimonialCarousel" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#testimonialCarousel" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
            <div class="mt-3">
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center">
                        <li class="page-item">
                            <a class="page-link" href="#testimonialCarousel" role="button" data-slide-to="0">1</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#testimonialCarousel" role="button" data-slide-to="1">2</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#testimonialCarousel" role="button" data-slide-to="2">3</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </section>
        



        <!-- article section -->
        <section id="article-section" class="bg-article" style="margin-top: 190px; margin-bottom: 80px;">
            <div class="container bg-left">
                <div class="d-flex flex-column justify-content-center align-items-center">
                    <h4>Check out our latest article</h4>
                    <hr class="h-row">
                </div>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 my-5">
                    <div class="col">
                        <div class="d-flex flex-column align-items-center border-card p-3">
                            <div>
                                <img src="images/article1.png" class="card-img-top rounded-3 w-100" alt="...">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Apa itu Dispepsia?</h5>
                                <p class="card-text para-color">
                                    Dispepsia menjadi suatu kondisi yang dapat mengakibatkan munculnya rasa tidak nyaman pada perut bagian atas karena masalah asam lambung atau ...
                                    <a href="https://www.halodoc.com/kesehatan/dispepsia" class="text-primary">Read More</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="d-flex flex-column align-items-center border-card p-3">
                            <div>
                                <img src="images/article2.png" class="card-img-top rounded-3 w-100" alt="...">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Ketahui Tipe dan Cara Mengobati Dispepsia</h5>
                                <p class="card-text para-color">
                                    Dispepsia atau yang lebih dikenal sebagai maag, adalah rasa tidak nyaman pada perut bagian ...
                                    <a href="https://www.halodoc.com/artikel/ketahui-tipe-dan-cara-mengobati-dispepsia" class="text-primary">Read More</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="d-flex flex-column align-items-center border-card p-3">
                            <div>
                                <img src="images/article3.png" class="card-img-top rounded-3 w-100" alt="...">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Gaya Hidup Sehat untuk Membantu Mengatasi Dispepsia</h5>
                                <p class="card-text para-color">
                                    Dispepsia merupakan sekumpulan gejala sakit perut yang bisa menjadi tanda penyakit...
                                    <a href="https://www.halodoc.com/artikel/gaya-hidup-sehat-untuk-membantu-mengatasi-dispepsia" class="text-primary">Read More</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col hidden-article">
                        <div class="d-flex flex-column align-items-center border-card p-3">
                            <div>
                                <img src="images/article4.png" class="card-img-top rounded-3 w-100" alt="...">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Hal yang Terjadi pada Tubuh saat Mengalami Dispepsia</h5>
                                <p class="card-text para-color">
                                    Sakit perut adalah masalah kesehatan umum yang sering dialami oleh...
                                    <a href="https://www.halodoc.com/artikel/hal-yang-terjadi-pada-tubuh-saat-mengalami-dispepsia" class="text-primary">Read More</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col hidden-article">
                        <div class="d-flex flex-column align-items-center border-card p-3">
                            <div>
                                <img src="images/article5.png" class="card-img-top rounded-3 w-100" alt="...">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Sindrom Dispepsia, Rasa Tidak Nyaman di Perut Usai Makan 2</h5>
                                <p class="card-text para-color">
                                    Dispepsia bukan sebuah penyakit, melainkan gejala. Apa tanda seseorang mengalami dispepsia? Kondisi ini bisa membuatmu merasakan ketidaknyamanan atau nyeri yang terjadi...
                                    <a href="https://www.halodoc.com/artikel/sindrom-dispepsia-rasa-tidak-nyaman-di-perut-usai-makan" class="text-primary">Read More</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col hidden-article">
                        <div class="d-flex flex-column align-items-center border-card p-3">
                            <div>
                                <img src="images/article6.png" class="card-img-top rounded-3 w-100" alt="...">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Dispepsia Bikin Tidak Nyaman? 5 Dokter Ini Paham Pengobatannya</h5>
                                <p class="card-text para-color">
                                    Dispepsia adalah keadaan saat perut bagian atas mengalami ketidaknyamanan akibat masalah dengan asam lambung atau...
                                    <a href="https://www.halodoc.com/artikel/dispepsia-bikin-tidak-nyaman-5-dokter-ini-paham-pengobatannya" class="text-primary">Read More</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-column align-items-center">
                    <button id="view-all-btn" type="button" class="btn btn-primary rounded-pill bg-light text-primary px-4">View All</button>
                </div>
            </div>
        </section>
        <script>
            document.getElementById('view-all-btn').addEventListener('click', function () {
                var hiddenArticles = document.querySelectorAll('.hidden-article');
                hiddenArticles.forEach(function (article) {
                    article.style.display = 'block';
                });
    
                this.style.display = 'none';
            });
        </script>
        <!-- Section Intro -->
        <!-- Isi sesuai dengan kode yang telah Anda buat -->
        <?php


// Langkah 4: Sertakan file koneksi dan fungsi pengambil nama pengguna
require_once('dbCon.php');
require_once('getnama.php');

// Langkah 5: Ambil nama pengguna dan tampilkan di halaman
if(isset($_SESSION['user_email'])) {
    $email_pengguna = $_SESSION['user_email']; // Ganti dengan ID pengguna yang sesuai
    $nama_pengguna = getnama($koneksi, $email_pengguna);
} else {
    // Sesuaikan dengan logika Anda jika session tidak ada
    $nama_pengguna = "Pengguna";
}
?>
<h1>Selamat datang, <?php echo $email; ?></h1>
<!-- Tampilkan nama pengguna di sini -->


    </main>
    <footer class="bg-primary text-white" style="margin-top: 190px;">
        <div class="container d-flex">
            <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 gx-5 p-5">
                <div class="d-flex flex-column">
                    <div><img class="mb-3" src="images/dispicare - Copy.png" alt=""></div>
                    <p>Dyspicare provides progressive, and affordable healthcare, accessible on mobile and online
                        for everyone</p>
                    <p>©Dyspicare PTY LTD 2020. All rights reserved</p>
                </div>

                <div class="d-flex flex-column">
                    <h5 class="mb-3">Company</h5>
                    <h6>About</h6>
                    <h6>Testimonials</h6>
                    <h6>Find a doctor</h6>
                    <h6>Apps</h6>
                </div>

                <div class="d-flex flex-column">
                    <h5 class="mb-3">Region</h5>
                    <h6>Indonesia</h6>
                    <h6>Singapore</h6>
                    <h6>Malaysia</h6>
                    <h6>Australia</h6>
                </div>
                <div class="d-flex flex-column">
                    <h5 class="mb-3">Help</h5>
                    <h6>Help Center</h6>
                    <h6>Support</h6>
                    <h6>Instruction</h6>
                    <h6>How it works</h6>
                </div>
            </div>
        </div>

    </footer>
    <iframe
src="https://www.chatbase.co/chatbot-iframe/FXk3tkbJks6A19_6Bff7Y"
title="Chatbot"
width="100%"
style="height: 100%; min-height: 700px"
frameborder="0"
></iframe>

    <script src="js/bootstrap.bundle.min.js"></script>
    <script>
        window.embeddedChatbotConfig = {
        chatbotId: "FXk3tkbJks6A19_6Bff7Y",
        domain: "www.chatbase.co"
        }
        </script>
        <script
        src="https://www.chatbase.co/embed.min.js"
        chatbotId="FXk3tkbJks6A19_6Bff7Y"
        domain="www.chatbase.co"
        defer>
        </script>
</body>
</body>

</html>