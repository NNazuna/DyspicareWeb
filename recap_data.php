<?php
session_start();
include 'dbCon.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['users_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

$users_id = $_SESSION['users_id'];

// Query untuk mengambil data 7 hari terakhir berdasarkan user_id
$sql = "SELECT tanggal, pola_makan, pola_tidur, pola_minum_obat, jenis_makanan, jenis_minuman, tingkat_stress, kebersihan_pribadi, kebersihan_lingkungan 
        FROM daily_records 
        WHERE users_id = :users_id 
        ORDER BY tanggal DESC 
        LIMIT 7";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':users_id', $users_id);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($data);
?>
