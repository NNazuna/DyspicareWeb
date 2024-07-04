<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'dbCon.php'; // Include the database connection

// Mengambil email dari session
$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
$overallAverageScore = null;

if ($email) {
    // Mengambil user_id dari email
    $stmtUser = $koneksi->prepare("SELECT id FROM users WHERE email = :email");
    $stmtUser->bindParam(':email', $email, PDO::PARAM_STR);
    $stmtUser->execute();
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        $userId = $user['id'];
        
        // Mengambil skor rata-rata harian user
        $stmtScore = $koneksi->prepare("
            SELECT AVG((pola_makan + pola_tidur + pola_minum_obat + tingkat_stress + kebersihan_pribadi + kebersihan_lingkungan) / 6) AS average_score
            FROM daily_records 
            WHERE user_id = :user_id
        ");
        $stmtScore->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmtScore->execute();
        $scoreResult = $stmtScore->fetch(PDO::FETCH_ASSOC);
        $overallAverageScore = $scoreResult['average_score'];
    }
}
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
    <script src="https://stackpath.amazonaws.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/swiper-bundle.min.css">
    <style>
        .hidden-article {
            display: none;
        }
        .indication {
            font-weight: bold;
            color: <?php echo ($indicationStatus == "Sehat") ? "green" : (($indicationStatus == "Tidak Sehat") ? "red" : "orange"); ?>;
        }
        .average-score {
            font-size: 20px;
            font-weight: bold;
            color: #007bff;
            margin-top: 10px;
        }
        .card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin: 10px;
        }

        .image-content {
            position: relative;
            height: 200px;
        }

        .card-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .overlay {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            /* background: rgba(0, 0, 0, 0.5); */
            /* border-radius: 10px; */
            
        }

        .card-content {
            padding: 20px;
            text-align: center;
        }

        .name {
            font-size: 22px;
            margin: 10px 0;
        }

        .description {
            font-size: 16px;
            color: #555;
        }

        .button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .button:hover {
            background-color: #0056b3;
        }

        .form-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .form-container input, .form-container textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .form-container button {
            padding: 10px 20px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #218838;
        }

        .swiper-slide {
            display: flex;
            justify-content: center;
            align-items: center;
            width: auto;
        }

        .card {
            width: 300px; /* Set width for each card */
            margin: 10px;
        }

    </style>
</head>

<body>
    <header>
        <!-- section Navbar -->
        <section>
            <nav class="navbar navbar-expand-lg navbar-light p-3">
                <div class="container">
                    <a class="navbar-brand" href="landingpage.php">
                        <img src="images/dispicare.png" alt=""></a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="landingpage.php">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="dashboard.php">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="status.php">Status</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="logoutbend.php">Logout</a>
                            </li>

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
                            <h1>Edukasi Dyspepsia</h1>
                            <p class="para-color py-2"> Dyspepsia adalah istilah yang digunakan untuk menggambarkan gejala-gejala yang muncul di daerah perut bagian atas, seperti rasa tidak nyaman, nyeri, atau sensasi kembung. 
                                Ini bisa terjadi karena berbagai faktor, termasuk pola makan yang tidak sehat, stres, atau kondisi medis tertentu seperti gastritis atau refluks asam.
                                Gejala dyspepsia dapat bervariasi dari satu individu ke individu lainnya, dan dapat meliputi perasaan penuh cepat saat makan, sensasi terbakar di dada, atau mual.
                                 Seringkali, dyspepsia adalah gejala dari masalah pencernaan yang mendasarinya, meskipun dalam beberapa kasus penyebabnya tidak dapat diidentifikasi dengan jelas.
                                </p>
                                <p class="para-color py-2"> Dispepsia bisa ditandai dengan beberapa tanda dan gejala, antara lain seperti rasa cepat kenyang saat makan,perut terasa kembung dan begah setelah makan,
                                timbulnya rasa tak nyaman di bagian ulu hati, bisa pula disertai rasa sakit dan peri,rasa terbakar atau panas di ulu hati. kadang-kadang rasa terbakar ini bisa menjalar dari ulu hati hingga ke tenggorokan,
                            dan mual dan kadang-kadang dapat disertai dengan muntah, meskipun hal ini jarang terjadi. jika gejala-gejala tersebut kamu alami, segera hubungi dokter untuk mendapat penanganan lebih lanjut. Berikut adalah cara mengobati Dyspepsia
                        <li>Menjalani pola makan sehat</li>
                        <li>Mempertahankan berat badan ideal</li>
                        <li>Berolahraga secara teratur</li>
                        <li>Mengurangi stres</li>
                        <li>Menghindari kebiasaan berbaring setelah makan</li>
                        <li>Menggunakan obat-obatan</li>
                        
                     </p>
                            
                               
                        </div>
                    </div>
                    <div class="col">
                        <div><img class="w-100" src="dyspepsia.jpg" alt="">
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

                    
                    <div class="col">
                        <a href="chatbotfend.php" class="d-block text-decoration-none">
                            <div class="d-flex flex-column align-items-center border-card p-3 card-hover">
                                    <div class="mt-5">
                                        <img src="images/card3.png" class="card-img-top w-100" alt="...">
                                    </div>
                                <div class="card-body text-center">
                                    <h5 class="card-title">Consultation</h5>
                                    <p class="card-text para-color">Free consultation with our trusted doctors and get the best recommendations</p>
                                </div>
                            </div>
                        </a>
                     </div>

                     <div class="col">
                        <a href="form.php" class="d-block text-decoration-none">
                            <div class="d-flex flex-column align-items-center border-card p-3 card-hover">
                                    <div class="mt-5">
                                        <img src="images/card4.png" class="card-img-top w-100" alt="...">
                                    </div>
                                <div class="card-body text-center">
                                    <h5 class="card-title">Tracking</h5>
                                    <p class="card-text para-color">Track and save your medical history and health data. You
                                        can
                                        access data any time anywhere.</p>
                                </div>
                            </div>
                        </a>
                     </div>

                     <div class="col">
                        <a href="edukasi.php" class="d-block text-decoration-none">
                            <div class="d-flex flex-column align-items-center border-card p-3 card-hover">
                                    <div class="mt-5">
                                        <img src="images/card6.png" class="card-img-top w-100" alt="...">
                                    </div>
                                <div class="card-body text-center">
                                    <h5 class="card-title">Education</h5>
                                    <p class="card-text para-color">Learn and find out more about efforts to minimize 
                                    dyspepsia and the dangers of ignoring it.</p>
                                </div>
                            </div>
                        </a>
                     </div>
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



                        </div>
                    </div>

                </div>
            </div>

        </section>

        <section class="bg-left" style="margin-top: 190px; margin-bottom: 80px;">
            <div class="container text-white bg-primary p-5 border-rad">
                <div class="d-flex flex-column justify-content-center align-items-center">
                    <h4>What our customer are saying</h4>
                    <hr class="h-row">
                </div>
                
                <div class="slide-container swiper">
                    <div class="swiper-wrapper" id="cardWrapper">
                        <!-- Initial customer reviews go here -->
                    </div>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
                
               
                <div class="form-container">
                    <input type="text" id="name" placeholder="Name">
                    <input type="file" id="imageFile" accept="image/*">
                    <textarea id="description" placeholder="Description"></textarea>
                    <button onclick="addReview()">Add Review</button>
                </div>
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

            function loadReviews() {
                const reviews = JSON.parse(localStorage.getItem('reviews')) || [];
                const cardWrapper = document.getElementById('cardWrapper');

            reviews.forEach(review => {
                const card = document.createElement('div');
                card.classList.add('card', 'swiper-slide');
                card.innerHTML = `
            <div class="image-content">
                <span class="overlay"></span>
                <div class="card-image">
                    <img src="${review.imageUrl}" alt="" class="card-img">
                </div>
            </div>
            <div class="card-content">
                <h2 class="name">${review.name}</h2>
                <p class="description">${review.description}</p>
                <button class="button" onclick="removeReview(this)">Remove</button>
            </div>
        `;
        cardWrapper.appendChild(card);
    });
}

        function saveReviews(reviews) {
            localStorage.setItem('reviews', JSON.stringify(reviews));
        }

        function addReview() {
            const name = document.getElementById('name').value;
            const imageFile = document.getElementById('imageFile').files[0];
            const description = document.getElementById('description').value;

            if (name && imageFile && description) {
                const reader = new FileReader();

                reader.onload = function(event) {
                    const imageUrl = event.target.result;
                    const reviews = JSON.parse(localStorage.getItem('reviews')) || [];

                    const newReview = { name, imageUrl, description };
                    reviews.push(newReview);
                    saveReviews(reviews);

                    const cardWrapper = document.getElementById('cardWrapper');

                    const card = document.createElement('div');
                    card.classList.add('card', 'swiper-slide');

                    card.innerHTML = `
                        <div class="image-content">
                            <span class="overlay"></span>
                            <div class="card-image">
                                <img src="${imageUrl}" alt="" class="card-img">
                            </div>
                        </div>
                        <div class="card-content">
                            <h2 class="name">${name}</h2>
                            <p class="description">${description}</p>
                            <button class="button" onclick="removeReview(this)">Remove</button>
                        </div>
                    `;

                    cardWrapper.appendChild(card);

                    document.getElementById('name').value = '';
                    document.getElementById('imageFile').value = '';
                    document.getElementById('description').value = '';
                };

                reader.readAsDataURL(imageFile);
            } else {
                alert('Please fill in all fields');
            }
        }

        function removeReview(button) {
            const card = button.closest('.card');
            const name = card.querySelector('.name').textContent;
            const reviews = JSON.parse(localStorage.getItem('reviews')) || [];

            const updatedReviews = reviews.filter(review => review.name !== name);
            saveReviews(updatedReviews);

            card.remove();
        }

        window.onload = function() {
            loadReviews();

            // Initialize Swiper
            var swiper = new Swiper('.swiper', {
                slidesPerView: 3,
                spaceBetween: 30,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });
        }
        </script>
    </main>

    <footer class="bg-primary text-white" style="margin-top: 190px;">
        <div class="container d-flex">
            <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 gx-5 p-5">
                <div class="d-flex flex-column">
                    <div><img class="mb-3" src="images/dispicare - Copy.png" alt=""></div>
                    <p>Dyspicare provides progressive, and affordable healthcare, accessible on mobile and online
                        for everyone</p>
                    <p>©Dyspicare PTY LTD 2024. All rights reserved</p>
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
      
</body>
<script src= "js/swiper-bundle.min.js"></script>
<script src="review.js"></script>
</html>
