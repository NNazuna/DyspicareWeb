<?php
session_start();
include 'dbCon.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['users_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

$users_id = $_SESSION['users_id'];
$date = $_GET['date'];

// Query untuk mengambil data berdasarkan tanggal dan user_id
$sql = "SELECT pola_makan, pola_tidur, pola_minum_obat, jenis_makanan, jenis_minuman, tingkat_stress, kebersihan_pribadi, kebersihan_lingkungan FROM daily_records WHERE users_id = :users_id AND tanggal = :tanggal";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':users_id', $users_id);
$stmt->bindParam(':tanggal', $date);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {

    // Konversi nilai jenis_makanan dan jenis_minuman ke skala 1-3
    $row['jenis_makanan'] = getJenisMakananScore($row['jenis_makanan']);
    $row['jenis_minuman'] = getJenisMinumanScore($row['jenis_minuman']);
    
    echo json_encode($row);
} else {
    echo json_encode(['error' => 'No data found for selected date']);
}


function getJenisMakananScore($jenis_makanan) {
    switch ($jenis_makanan) {
        case 'sayuran':
            return 3;
        case 'buah non sitrus':
            return 3;
        case 'buah sitrus':
            return 1;
        case 'telur':
            return 2;
        case 'makanan pedas':
            return 1;
        case 'gorengan':
            return 1;
        case 'makanan berlemak':
            return 1;
        default:
            return 0;
    }
}

function getJenisMinumanScore($jenis_minuman) {
    switch ($jenis_minuman) {
        case 'air_mineral':
            return 3;
        case 'jus buah':
            return 3;
        case 'kopi':
            return 1;
        case 'teh':
            return 2;
        case 'soda':
            return 1;
        default:
            return 0;
    }
}
?>
