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

// Debug: Periksa apakah users_id dan date ada
error_log("User ID: " . $users_id);
error_log("Date: " . $date);

// Query untuk mengambil data berdasarkan tanggal dan user_id
$sql = "SELECT pola_makan, jenis_makanan, jenis_minuman FROM daily_records WHERE users_id = :users_id AND tanggal = :tanggal";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':users_id', $users_id);
$stmt->bindParam(':tanggal', $date);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    // Menentukan skor berdasarkan pola makan
    $pola_makan_score = $row['pola_makan'];
    $pola_makan_summary = '';
    if ($pola_makan_score == 3) {
        $pola_makan_summary = 'Pola Makan sangat baik';
    } elseif ($pola_makan_score == 2) {
        $pola_makan_summary = 'Pola Makan cukup';
    } else {
        $pola_makan_summary = 'Pola Makan buruk';
    }

    // Menentukan skor berdasarkan jenis makanan
    $jenis_makanan = $row['jenis_makanan'];
    $jenis_makanan_score = 0;
    $jenis_makanan_summary = '';
    switch ($jenis_makanan) {
        case 'pedas':
        case 'gorengan':
        case 'berlemak':
            $jenis_makanan_score = 1;
            $jenis_makanan_summary = 'Jenis Makanan buruk';
            break;
        case 'sitrus':
        case 'non_sitrus':
        case 'sayuran':
            $jenis_makanan_score = 3;
            $jenis_makanan_summary = 'Jenis Makanan sangat baik';
            break;
        case 'telur':
            $jenis_makanan_score = 2;
            $jenis_makanan_summary = 'Jenis Makanan cukup';
            break;
    }

    // Menentukan skor berdasarkan jenis minuman
    $jenis_minuman = $row['jenis_minuman'];
    $jenis_minuman_score = 0;
    $jenis_minuman_summary = '';
    switch ($jenis_minuman) {
        case 'soda':
        case 'alkohol':
            $jenis_minuman_score = 1;
            $jenis_minuman_summary = 'Jenis Minuman buruk';
            break;
        case 'kopi':
        case 'teh':
        case 'lainnya':
            $jenis_minuman_score = 2;
            $jenis_minuman_summary = 'Jenis Minuman cukup';
            break;
        case 'jus':
        case 'air_mineral':
            $jenis_minuman_score = 3;
            $jenis_minuman_summary = 'Jenis Minuman sangat baik';
            break;
    }

    echo json_encode([
        'pola_makan_score' => $pola_makan_score,
        'jenis_makanan_score' => $jenis_makanan_score,
        'jenis_minuman_score' => $jenis_minuman_score,
        'pola_makan_summary' => $pola_makan_summary,
        'jenis_makanan_summary' => $jenis_makanan_summary,
        'jenis_minuman_summary' => $jenis_minuman_summary
    ]);
} else {
    echo json_encode(['error' => 'No data found for selected date']);
}
?>
