<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'dbCon.php'; // Include the database connection

if (!isset($_GET['user_id'])) {
    echo "No user ID specified.";
    exit();
}

$user_id = $_GET['user_id'];
$user_name = "";
$user_data = [];

try {
    // Get user name
    $stmt = $conn->prepare("SELECT nama FROM users WHERE id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $user_name = $stmt->fetch(PDO::FETCH_ASSOC)['nama'];

    // Get user data
    $stmt = $conn->prepare("
        SELECT tanggal, pola_makan, pola_tidur, pola_minum_obat, jenis_makanan, jenis_minuman, tingkat_stress, kebersihan_pribadi, kebersihan_lingkungan 
        FROM daily_records 
        WHERE user_id = :user_id
        ORDER BY tanggal ASC
    ");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $user_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Report</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h2 {
            margin-top: 20px;
            color: #343a40;
        }

        .chart-container {
            width: 80%;
            margin-top: 20px;
        }

        .back-btn {
            margin-top: 20px;
        }

        .back-btn a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
            border: 2px solid #007bff;
            padding: 10px 20px;
            border-radius: 10px;
            transition: background-color 0.3s, color 0.3s;
        }

        .back-btn a:hover {
            background-color: #007bff;
            color: #ffffff;
        }
    </style>
</head>

<body>
    <h2>User Report for <?php echo htmlspecialchars($user_name); ?></h2>
    <div class="chart-container">
        <canvas id="userChart"></canvas>
    </div>
    <div class="back-btn">
        <a href="admin_view_reports.php">Back to Reports Overview</a>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const userData = <?php echo json_encode($user_data); ?>;
            const labels = userData.map(record => record.tanggal);
            const dataPolaMakan = userData.map(record => record.pola_makan);
            const dataPolaTidur = userData.map(record => record.pola_tidur);
            const dataPolaMinumObat = userData.map(record => record.pola_minum_obat);
            const dataJenisMakanan = userData.map(record => record.jenis_makanan);
            const dataJenisMinuman = userData.map(record => record.jenis_minuman);
            const dataTingkatStress = userData.map(record => record.tingkat_stress);
            const dataKebersihanPribadi = userData.map(record => record.kebersihan_pribadi);
            const dataKebersihanLingkungan = userData.map(record => record.kebersihan_lingkungan);

            var ctx = document.getElementById('userChart').getContext('2d');
            var userChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        { label: 'Pola Makan', data: dataPolaMakan, backgroundColor: 'rgba(75, 192, 192, 0.8)' },
                        { label: 'Pola Tidur', data: dataPolaTidur, backgroundColor: 'rgba(54, 162, 235, 0.8)' },
                        { label: 'Pola Minum Obat', data: dataPolaMinumObat, backgroundColor: 'rgba(255, 206, 86, 0.8)' },
                        { label: 'Jenis Makanan', data: dataJenisMakanan, backgroundColor: 'rgba(153, 102, 255, 0.8)' },
                        { label: 'Jenis Minuman', data: dataJenisMinuman, backgroundColor: 'rgba(255, 159, 64, 0.8)' },
                        { label: 'Tingkat Stress', data: dataTingkatStress, backgroundColor: 'rgba(255, 99, 132, 0.8)' },
                        { label: 'Kebersihan Pribadi', data: dataKebersihanPribadi, backgroundColor: 'rgba(75, 192, 192, 0.5)' },
                        { label: 'Kebersihan Lingkungan', data: dataKebersihanLingkungan, backgroundColor: 'rgba(153, 102, 255, 0.5)' }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 3
                        }
                    }
                }
            });
        });
    </script>
</body>

</html>
