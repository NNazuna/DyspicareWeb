<?php
session_start();
include 'dbCon.php';

if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear'])) {
    $stmt = $conn->prepare("DELETE FROM conversations WHERE user_id = :user_id");
    $stmt->execute([':user_id' => $user_id]);
    echo 'History cleared';
} else {
    echo 'Invalid request';
}
?>
