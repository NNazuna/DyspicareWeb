<?php
session_start();
include 'dbCon.php';

if (!isset($_SESSION['user_id'])) {
    echo "Error: User ID not found.";
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    $sql = "SELECT AVG((pola_makan + pola_tidur + pola_minum_obat + tingkat_stress + kebersihan_pribadi + kebersihan_lingkungan) / 6) as avg_score 
            FROM daily_records 
            WHERE user_id = :user_id 
            AND tanggal >= CURDATE() - INTERVAL 30 DAY";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        $avg_score = $result['avg_score'];
        if ($avg_score >= 2.5) {
            $message = "Performa Anda selama 30 hari ini sudah sangat baik.";
        } elseif ($avg_score >= 1.5) {
            $message = "Performa Anda selama 30 hari ini baik.";
        } else {
            $message = "Performa Anda selama 30 hari ini sangat buruk, Anda perlu mengunjungi rumah sakit terdekat untuk penanganan lebih lanjut.";
        }

        // Simpan notifikasi ke database
        $sql = "INSERT INTO notifications (user_id, message) VALUES (:user_id, :message)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':message', $message);
        $stmt->execute();
    } else {
        $message = "Data tidak mencukupi untuk penilaian selama 30 hari.";
    }

    $_SESSION['notification'] = $message;
    header("Location: notification_view.php");
    exit();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null;
?>
