<?php
function getnama($koneksi, $user_id) {
    $stmt = $koneksi->prepare("SELECT nama FROM user WHERE id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['nama'] : 'Pengguna';
}
?>
