<?php
session_start();
include 'dbCon.php'; // Include the database connection

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];

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

if (isset($_GET['date'])) {
    $date = $_GET['date'];

    // Fetch the data for the given date
    $stmt = $conn->prepare("SELECT pola_makan, jenis_makanan, pola_minum_obat, pola_tidur FROM daily_records WHERE user_id = :user_id AND tanggal = :date");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':date', $date);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $jenis_makanan_score = getJenisMakananScore($result['jenis_makanan']);
        $total_score_pola_makan = ($result['pola_makan'] + $jenis_makanan_score) / 2;

        echo json_encode([
            'pola_makan' => $total_score_pola_makan,
            'pola_minum_obat' => $result['pola_minum_obat'],
            'pola_tidur' => $result['pola_tidur']
        ]);
    } else {
        echo json_encode(['error' => 'Data not found for the selected date']);
    }
} else {
    echo json_encode(['error' => 'Date not provided']);
}
