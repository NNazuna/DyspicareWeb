<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'dbCon.php'; // Include the database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

$user_id = $_SESSION['user_id'];
$order_by = 'tanggal'; // Default order
$order_dir = 'ASC'; // Default order direction

if (isset($_GET['order_by'])) {
    $order_by = in_array($_GET['order_by'], ['tanggal']) ? $_GET['order_by'] : 'tanggal';
}

if (isset($_GET['order_dir'])) {
    $order_dir = $_GET['order_dir'] == 'DESC' ? 'DESC' : 'ASC';
}

function getJenisMakananScore($jenis_makanan) {
    switch ($jenis_makanan) {
        case 'sayuran':
        case 'non_sitrus':
            return 3;
        case 'telur':
            return 2;
        case 'sitrus':
        case 'pedas':
        case 'gorengan':
        case 'berlemak':
            return 1;
        default:
            return 0;
    }
}

function getJenisMinumanScore($jenis_minuman) {
    switch ($jenis_minuman) {
        case 'air_mineral':
        case 'jus':
            return 3;
        case 'teh':
            return 2;
        case 'kopi':
        case 'soda':
        case 'alkohol':
            return 1;
        default:
            return 0;
    }
}

function convertPolaMakan($value) {
    switch ($value) {
        case 3:
            return "3x sehari";
        case 2:
            return "2x sehari";
        case 1:
            return "1x sehari";
        default:
            return "Tidak diketahui";
    }
}

function convertPolaTidur($value) {
    switch ($value) {
        case 3:
            return "7-8 jam";
        case 2:
            return "4-6 jam";
        case 1:
            return "kurang dari 3 jam";
        default:
            return "Tidak diketahui";
    }
}

function convertPolaMinumObat($value) {
    switch ($value) {
        case 3:
            return "Rutin";
        case 2:
            return "Kurang rutin";
        case 1:
            return "Tidak minum obat";
        default:
            return "Tidak diketahui";
    }
}

function convertTingkatStress($value) {
    switch ($value) {
        case 3:
            return "Rendah";
        case 2:
            return "Sedang";
        case 1:
            return "Tinggi";
        default:
            return "Tidak diketahui";
    }
}

function convertKebersihanPribadi($value) {
    switch ($value) {
        case 3:
            return "Baik";
        case 2:
            return "Cukup";
        case 1:
            return "Buruk";
        default:
            return "Tidak diketahui";
    }
}

function convertKebersihanLingkungan($value) {
    switch ($value) {
        case 3:
            return "Baik";
        case 2:
            return "Cukup";
        case 1:
            return "Buruk";
        default:
            return "Tidak diketahui";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_data'])) {
    // Delete all daily records for the user
    $stmtDelete = $conn->prepare("DELETE FROM daily_records WHERE user_id = :user_id");
    $stmtDelete->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmtDelete->execute();
}

try {
    // Fetch user information
    $stmtUser = $conn->prepare("SELECT nama, email FROM users WHERE id = :user_id");
    $stmtUser->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmtUser->execute();
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);
    
    // Fetch daily records
    $stmt = $conn->prepare("SELECT * FROM daily_records WHERE user_id = :user_id ORDER BY $order_by $order_dir");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Calculate average score including jenis_makanan and jenis_minuman
    $stmtAvg = $conn->prepare("
        SELECT AVG((pola_makan + pola_tidur + pola_minum_obat + tingkat_stress + kebersihan_pribadi + kebersihan_lingkungan + 
        CASE jenis_makanan
            WHEN 'sayuran' THEN 3
            WHEN 'non_sitrus' THEN 3
            WHEN 'telur' THEN 2
            WHEN 'sitrus' THEN 1
            WHEN 'pedas' THEN 1
            WHEN 'gorengan' THEN 1
            WHEN 'berlemak' THEN 1
            ELSE 0
        END +
        CASE jenis_minuman
            WHEN 'air_mineral' THEN 3
            WHEN 'jus' THEN 3
            WHEN 'teh' THEN 2
            WHEN 'kopi' THEN 1
            WHEN 'soda' THEN 1
            WHEN 'alkohol' THEN 1
            ELSE 0
        END) / 8) AS average_score
        FROM daily_records 
        WHERE user_id = :user_id
    ");
    $stmtAvg->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmtAvg->execute();
    $averageScoreResult = $stmtAvg->fetch(PDO::FETCH_ASSOC);
    $averageScore = $averageScoreResult['average_score'];

    // Determine health status based on average score
    if ($averageScore >= 2.75) {
        $healthStatus = "Anda terindikasi sangat sehat, pertahankan pola hidup anda";
    } elseif ($averageScore >= 2) {
        $healthStatus = "Anda terindikasi sehat, namun jika masih ada yang dikeluhkan terkait gejala maag silahkan gunakan <a href='chatbotfend.php'>Fitur chatbot</a> kami atau anda bisa mengunjungi <a href='https://www.google.com/maps/search/rumah+sakit+terdekat' target='_blank'>Rumah sakit terdekat</a> untuk konsultasi lebih detail  ";
    } else {
        $healthStatus = "Anda terindikasi kurang sehat, perbaiki pola hidup anda. Silahkan kunjungi <a href='https://www.google.com/maps/search/rumah+sakit+terdekat' target='_blank'>Rumah sakit terdekat</a> untuk konsultasi lebih detail, atau anda bisa gunakan <a href='chatbotfend.php'>Fitur chatbot</a> jika memiliki pertanyaan seputar penyakit maag";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User History</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        h2 {
            margin-top: 20px;
            color: #343a40;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .sortable {
            cursor: pointer;
        }
        .sortable:hover {
            text-decoration: underline;
        }
        .back-btn, .reset-btn {
            margin-bottom: 20px;
        }
        .back-btn a, .reset-btn form button {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
            border: 2px solid #007bff;
            padding: 10px 20px;
            border-radius: 10px;
            transition: background-color 0.3s, color 0.3s;
        }
        .back-btn a:hover, .reset-btn form button:hover {
            background-color: #007bff;
            color: #ffffff;
        }
    </style>
</head>

<body>
    <div class="back-btn">
        <a href="landingpage.php">Back</a>
    </div>
    
    <div class="reset-btn">
        <form method="post">
            <button type="submit" name="reset_data">Reset Data</button>
        </form>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">User Information</h5>
            <p class="card-text"><strong>Name:</strong> <?php echo htmlspecialchars($user['nama']); ?></p>
            <p class="card-text"><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Status</h5>
            
            <p class="card-text"><?php echo $healthStatus; ?></p>
        </div>
    </div>
    <h2>Daily Records</h2>
    <table>
        <tr>
            <th><a href="?order_by=tanggal&order_dir=<?php echo $order_dir == 'ASC' ? 'DESC' : 'ASC'; ?>" class="sortable">Tanggal</a></th>
            <th>Pola Makan</th>
            <th>Jenis Makanan</th>
            <th>Pola Tidur</th>
            <th>Pola Minum Obat</th>
            <th>Jenis Minuman</th>
            <th>Tingkat Stress</th>
            <th>Kebersihan Pribadi</th>
            <th>Kebersihan Lingkungan</th>
        </tr>
        <?php foreach ($records as $record): ?>
            <tr>
                <td><?php echo htmlspecialchars($record['tanggal']); ?></td>
                <td><?php echo htmlspecialchars(convertPolaMakan($record['pola_makan'])); ?></td>
                <td><?php echo htmlspecialchars($record['jenis_makanan']); ?></td>
                <td><?php echo htmlspecialchars(convertPolaTidur($record['pola_tidur'])); ?></td>
                <td><?php echo htmlspecialchars(convertPolaMinumObat($record['pola_minum_obat'])); ?></td>
                <td><?php echo htmlspecialchars($record['jenis_minuman']); ?></td>
                <td><?php echo htmlspecialchars(convertTingkatStress($record['tingkat_stress'])); ?></td>
                <td><?php echo htmlspecialchars(convertKebersihanPribadi($record['kebersihan_pribadi'])); ?></td>
                <td><?php echo htmlspecialchars(convertKebersihanLingkungan($record['kebersihan_lingkungan'])); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>
