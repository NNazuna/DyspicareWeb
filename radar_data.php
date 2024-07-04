<?php
session_start();
include 'dbCon.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {  // Mengubah 'users_id' menjadi 'user_id'
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];  // Mengubah 'users_id' menjadi 'user_id'
$date = $_GET['date'];

// Query untuk mengambil data berdasarkan tanggal dan user_id
$sql = "SELECT pola_makan, pola_tidur, pola_minum_obat, jenis_makanan, jenis_minuman, tingkat_stress, kebersihan_pribadi, kebersihan_lingkungan FROM daily_records WHERE user_id = :user_id AND tanggal = :tanggal";  // Mengubah 'users_id' menjadi 'user_id'
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $user_id);  // Mengubah 'users_id' menjadi 'user_id'
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
        case 'non_sitrus':
            return 3;
        case 'sitrus':
            return 1;
        case 'telur':
            return 2;
        case 'pedas':
            return 1;
        case 'gorengan':
            return 1;
        case 'berlemak':
            return 1;
        default:
            return 0;
    }
}

function getJenisMinumanScore($jenis_minuman) {
    switch ($jenis_minuman) {
        case 'air_mineral':
            return 3;
        case 'jus':
            return 3;
        case 'kopi':
            return 1;
        case 'teh':
            return 2;
        case 'soda':
            return 1;
        case 'alkohol':
            return 1;
        default:
            return 0;
    }
}

