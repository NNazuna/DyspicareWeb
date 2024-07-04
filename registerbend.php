<?php
include 'dbCon.php';

// Ambil data dari form pendaftaran
$nama = $_POST['nama'];
$email = $_POST['email'];
$password = $_POST['password'];

try {
    // Simpan pengguna ke database
    $sql = "INSERT INTO users (nama, email, password) VALUES (:nama, :email, :password)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nama', $nama);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->execute();
    echo "Registrasi berhasil. <a href='login.php'>Klik di sini untuk login</a>.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null;
?>
