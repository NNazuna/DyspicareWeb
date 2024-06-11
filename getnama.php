<?php
// Langkah 2: Fungsi untuk mengambil nama pengguna dari database berdasarkan email
function getnama($koneksi, $email_pengguna) {
    $sql = "SELECT nama FROM user WHERE email = :email_pengguna"; // Menggunakan kolom email sebagai referensi
    $stmt = $koneksi->prepare($sql);
    $stmt->bindParam(':email_pengguna', $email_pengguna);
    $stmt->execute();

    // Ambil nama pengguna dari hasil query
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        return $result['nama'];
    } else {
        return "Pengguna";
    }
}
?>
