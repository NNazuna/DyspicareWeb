<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Dyspicare</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: "Mulish", sans-serif;
            margin: 0;
            padding: 20px;
            background-image: url('images/article-bg.png'), url('images/article-bg2.png'), url('images/article-bg2.png');
            background-size: 80% 80%, 17% 17%, 17% 17%;
            background-position: right 20%, left 80%, right 97%;
            background-repeat: no-repeat, no-repeat;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border-radius: 20px;
            box-shadow: 5px 5px 15px rgba(0,0,0,0.1);
            background-color: white;
            transition: all 0.3s ease;
            opacity: 0.95; 
        }

        form:hover {
            box-shadow: 5px 5px 25px rgba(0,0,0,0.2);
        }

        form div {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: 700;
            margin-bottom: 5px;
            color: #495057;
        }

        input[type="number"],
        input[type="date"],
        select {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 10px;
            box-sizing: border-box;
            transition: border 0.3s ease, box-shadow 0.3s ease;
        }

        input[type="number"]:focus,
        input[type="date"]:focus,
        select:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0,123,255,0.5);
            outline: none;
        }

        button[type="submit"],
        button[type="submit"]:hover,
        .back-button:hover {
            background-color: #0056b3;
        }

        h1 {
            text-align: center;
            color: #343a40;
            margin-bottom: 20px;
        }

        .image-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .image-container img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }

        .back-button-container {
    width: 60%;
    max-width: 800px;
    margin: 20px auto;
    text-align: left;
}

.back-button {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 1rem;
    cursor: pointer;
    position: absolute;
    top: 20px; /* Atur jarak dari atas */
    right: 20px; /* Atur jarak dari kanan */
}

.back-button:hover {
    background-color: #0056b3;
}

    </style>
</head>
<body>
<div class="back-button-container">
        <a href="landingpage.php" class="back-button">Home</a>
    </div>
    <div class="image-container">
        <img src="images/dispicare.png" alt="Dispicare Logo">
    </div>
    <h1>Formulir Dyspicare</h1>
    <form action="formbend.php" method="post">
        <div>
            <label for="tanggal">Tanggal:</label>
            <input type="date" id="tanggal" name="tanggal" required>
        </div>
        <div>
            <label for="umur">Umur:</label>
            <input type="number" id="umur" name="umur" min="0" required>
        </div>
        <div>
            <label for="pola_makan">Pola Makan:</label>
            <select id="pola_makan" name="pola_makan" required>
                <option value="1">1x sehari</option>
                <option value="2">2x sehari</option>
                <option value="3">3x sehari</option>
            </select>
        </div>
        <div>
            <label for="jenis_makanan">Jenis Makanan:</label>
            <select id="jenis_makanan" name="jenis_makanan" required>
                <option value="pedas">Makanan Pedas</option>
                <option value="gorengan">Gorengan</option>
                <option value="sitrus">Buah Sitrus</option>
                <option value="non_sitrus">Buah Non Sitrus</option>
                <option value="sayuran">Sayuran</option>
                <option value="telur">Telur</option>
                <option value="berlemak">Makanan Berlemak</option>
            </select>
        </div>
        <div>
            <label for="pola_tidur">Pola Tidur:</label>
            <select id="pola_tidur" name="pola_tidur" required>
                <option value="1">Kurang dari 3 jam</option>
                <option value="2">4-6 jam</option>
                <option value="3">7-8 jam</option>
            </select>
        </div>
        <div>
            <label for="pola_minum_obat">Pola Minum Obat:</label>
            <select id="pola_minum_obat" name="pola_minum_obat" required>
                <option value="1">Tidak minum obat</option>
                <option value="2">Kurang rutin</option>
                <option value="3">Rutin</option>
            </select>
        </div>
        <div>
            <label for="jenis_minuman">Jenis Minuman:</label>
            <select id="jenis_minuman" name="jenis_minuman" required>
                <option value="soda">Soda</option>
                <option value="kopi">Kopi</option>
                <option value="alkohol">Alkohol</option>
                <option value="jus">Jus Buah</option>
                <option value="air_mineral">Air Mineral</option>
                <option value="teh">Teh</option>
            </select>
        </div>
        <div>
            <label for="tingkat_stress">Tingkat Stress:</label>
            <select id="tingkat_stress" name="tingkat_stress" required>
                <option value="3">Rendah</option>
                <option value="2">Sedang</option>
                <option value="1">Tinggi</option>
            </select>
        </div>
        <div>
            <label for="kebersihan_pribadi">Kebersihan Pribadi:</label>
            <select id="kebersihan_pribadi" name="kebersihan_pribadi" required>
                <option value="1">Buruk</option>
                <option value="2">Cukup</option>
                <option value="3">Baik</option>
            </select>
        </div>
        <div>
            <label for="kebersihan_lingkungan">Kebersihan Lingkungan:</label>
            <select id="kebersihan_lingkungan" name="kebersihan_lingkungan" required>
                <option value="1">Buruk</option>
                <option value="2">Cukup</option>
                <option value="3">Baik</option>
            </select>
        </div>
        <button type="submit">Submit</button>
    </form>
    
</body>
</html>
