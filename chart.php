<?php
// Include database connection
include 'dbCon.php';

try {
    // Query untuk mengambil semua data dari tabel daily_records
    $sql = "SELECT * FROM daily_records";

    // Eksekusi query
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Mengirimkan data dalam format JSON
    echo json_encode($rows);
} catch (PDOException $e) {
    // Menangani kesalahan jika ada
    echo "Error: " . $e->getMessage();
}

// Tutup koneksi
$conn = null;
?>
