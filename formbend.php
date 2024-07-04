<?php
session_start();
include 'dbCon.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    echo "Error: User ID not found.";
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil data dari form
$tanggal = $_POST['tanggal'];
$umur = $_POST['umur'];
$pola_makan = $_POST['pola_makan'];
$jenis_makanan = $_POST['jenis_makanan'];
$pola_tidur = $_POST['pola_tidur'];
$pola_minum_obat = $_POST['pola_minum_obat'];
$jenis_minuman = $_POST['jenis_minuman'];
$tingkat_stress = $_POST['tingkat_stress'];
$kebersihan_pribadi = $_POST['kebersihan_pribadi'];
$kebersihan_lingkungan = $_POST['kebersihan_lingkungan'];

try {
    // Cek apakah data untuk tanggal dan user_id sudah ada
    $sql_check = "SELECT COUNT(*) FROM daily_records WHERE user_id = :user_id AND tanggal = :tanggal";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bindParam(':user_id', $user_id);
    $stmt_check->bindParam(':tanggal', $tanggal);
    $stmt_check->execute();
    $count = $stmt_check->fetchColumn();

    if ($count > 0) {
        // Jika data sudah ada, tampilkan pesan kesalahan
        echo "Anda sudah mengisi data di tanggal $tanggal. <a href='form.php'>Kembali ke form</a>.";
    } else {
        // Simpan data ke database
        $sql = "INSERT INTO daily_records (user_id, tanggal, umur, pola_makan, jenis_makanan, pola_tidur, pola_minum_obat, jenis_minuman, tingkat_stress, kebersihan_pribadi, kebersihan_lingkungan) VALUES (:user_id, :tanggal, :umur, :pola_makan, :jenis_makanan, :pola_tidur, :pola_minum_obat, :jenis_minuman, :tingkat_stress, :kebersihan_pribadi, :kebersihan_lingkungan)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':tanggal', $tanggal);
        $stmt->bindParam(':umur', $umur);
        $stmt->bindParam(':pola_makan', $pola_makan);
        $stmt->bindParam(':jenis_makanan', $jenis_makanan);
        $stmt->bindParam(':pola_tidur', $pola_tidur);
        $stmt->bindParam(':pola_minum_obat', $pola_minum_obat);
        $stmt->bindParam(':jenis_minuman', $jenis_minuman);
        $stmt->bindParam(':tingkat_stress', $tingkat_stress);
        $stmt->bindParam(':kebersihan_pribadi', $kebersihan_pribadi);
        $stmt->bindParam(':kebersihan_lingkungan', $kebersihan_lingkungan);
        $stmt->execute();
        
        // Alihkan ke dashboard setelah menyimpan data
        header("Location: dashboard.php");
        exit();
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null;
?>
