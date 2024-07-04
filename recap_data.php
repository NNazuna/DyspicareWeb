<?php
session_start();
include 'dbCon.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Fungsi untuk mengonversi jenis makanan ke skor
function getJenisMakananScore($jenis_makanan) {
    switch ($jenis_makanan) {
        case 'sayuran':
        case 'non_sitrus':
            return 3;
        case 'telur':
            return 2;
        case 'sitrus':
        case 'pedas':
        case 'gorengan':
        case 'berlemak':
            return 1;
        default:
            return 0;
    }
}

// Fungsi untuk mengonversi jenis minuman ke skor
function getJenisMinumanScore($jenis_minuman) {
    switch ($jenis_minuman) {
        case 'air_mineral':
        case 'jus':
            return 3;
        case 'teh':
            return 2;
        case 'kopi':
        case 'soda':
        case 'alkohol':
            return 1;
        default:
            return 0;
    }
}

// Ambil data 7 hari terakhir untuk pengguna yang sedang login
$sql = "SELECT tanggal, pola_makan, pola_tidur, pola_minum_obat, jenis_makanan, jenis_minuman, tingkat_stress, kebersihan_pribadi, kebersihan_lingkungan 
        FROM daily_records 
        WHERE user_id = :user_id AND tanggal >= CURDATE() - INTERVAL 7 DAY
        ORDER BY tanggal ASC";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Jika data tidak ada, berikan nilai default
if (empty($data)) {
    echo json_encode(['error' => 'No data found for the past 7 days']);
    exit();
}

// Konversi jenis makanan dan jenis minuman ke nilai numerik dan hitung rata-rata
foreach ($data as &$record) {
    $record['jenis_makanan'] = getJenisMakananScore($record['jenis_makanan']);
    $record['jenis_minuman'] = getJenisMinumanScore($record['jenis_minuman']);
    $record['average_score'] = (
        $record['pola_makan'] + $record['pola_tidur'] + $record['pola_minum_obat'] + 
        $record['jenis_makanan'] + $record['jenis_minuman'] + 
        $record['tingkat_stress'] + $record['kebersihan_pribadi'] + 
        $record['kebersihan_lingkungan']
    ) / 8;
}

echo json_encode($data);

