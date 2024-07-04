<?php
session_start();
include 'dbCon.php'; // Sesuaikan dengan path file koneksi database Anda

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query untuk memeriksa email dan password admin
    $stmt = $conn->prepare("SELECT * FROM admins WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_email'] = $admin['email'];
        header('Location: admin_dashboard.php');
        exit();
    } else {
        echo "<script>alert('Email atau password salah'); window.location.href='admin_login.php';</script>";
    }
}
?>
