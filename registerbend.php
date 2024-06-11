<?php
include 'dbCon.php';

// Ambil data dari form pendaftaran
$email = $_POST['email'];
$password = $_POST['password'];
$hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash password

try {
    // Simpan pengguna ke database
    $sql = "INSERT INTO users (email, password) VALUES (:email, :password)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->execute();
    echo "Registrasi berhasil. <a href='login.php'>Klik di sini untuk login</a>.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null;
?>
