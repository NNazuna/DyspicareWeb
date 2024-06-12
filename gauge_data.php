<?php
include 'dbCon.php';

$sql = "SELECT * FROM daily_records ORDER BY id DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->execute();
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if ($data) {
    echo json_encode($data);
} else {
    echo json_encode(["error" => "No data found"]);
}
?>
