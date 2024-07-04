<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'dbCon.php'; // Include the database connection

$userId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 8;
$offset = ($page - 1) * $limit;

$user = [];
$dailyRecords = [];
$overallAverageScore = 0;
$healthStatus = '';

function getJenisMakananScore($jenis_makanan) {
    switch ($jenis_makanan) {
        case 'sayuran':
        case 'non_sitrus':
            return 3;
        case 'telur':
            return 2;
        case 'pedas':
        case 'sitrus':
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
            return '3x sehari';
        case 2:
            return '2x sehari';
        case 1:
            return '1x sehari';
        default:
            return '';
    }
}

function convertPolaTidur($value) {
    switch ($value) {
        case 3:
            return '7-8 jam';
        case 2:
            return '4-6 jam';
        case 1:
            return 'Kurang dari 3 jam';
        default:
            return '';
    }
}

function convertPolaMinumObat($value) {
    switch ($value) {
        case 3:
            return 'Rutin';
        case 2:
            return 'Kurang rutin';
        case 1:
            return 'Tidak minum obat';
        default:
            return '';
    }
}

function convertTingkatStress($value) {
    switch ($value) {
        case 3:
            return 'Rendah';
        case 2:
            return 'Sedang';
        case 1:
            return 'Tinggi';
        default:
            return '';
    }
}

function convertKebersihanPribadi($value) {
    switch ($value) {
        case 3:
            return 'Baik';
        case 2:
            return 'Cukup';
        case 1:
            return 'Buruk';
        default:
            return '';
    }
}

function convertKebersihanLingkungan($value) {
    switch ($value) {
        case 3:
            return 'Baik';
        case 2:
            return 'Cukup';
        case 1:
            return 'Buruk';
        default:
            return '';
    }
}

try {
    // Get user information
    $stmt = $conn->prepare("SELECT nama, email FROM users WHERE id = :id");
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get total records count for pagination
    $stmtCount = $conn->prepare("SELECT COUNT(*) FROM daily_records WHERE user_id = :user_id");
    $stmtCount->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmtCount->execute();
    $totalRecords = $stmtCount->fetchColumn();
    $totalPages = ceil($totalRecords / $limit);

    // Get daily records with pagination
    $stmt = $conn->prepare("SELECT * FROM daily_records WHERE user_id = :user_id ORDER BY tanggal DESC LIMIT :limit OFFSET :offset");
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $dailyRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $totalScore = 0;
    $recordCount = count($dailyRecords);

    foreach ($dailyRecords as $key => $record) {
        $jenisMakananScore = getJenisMakananScore($record['jenis_makanan']);
        $jenisMinumanScore = getJenisMinumanScore($record['jenis_minuman']);
        $dailyAverage = ($record['pola_makan'] + $record['pola_tidur'] + $record['pola_minum_obat'] + $record['tingkat_stress'] + $record['kebersihan_pribadi'] + $record['kebersihan_lingkungan'] + $jenisMakananScore + $jenisMinumanScore) / 8;
        $totalScore += $dailyAverage;

        // Add the average score to the record for display
        $dailyRecords[$key]['average_score'] = $dailyAverage;
    }

    if ($recordCount > 0) {
        $overallAverageScore = $totalScore / $recordCount;
    }

    // Determine health status based on overall average score
    if ($overallAverageScore >= 2.75) {
        $healthStatus = "Sangat baik";
    } elseif ($overallAverageScore >= 2) {
        $healthStatus = "Baik";
    } else {
        $healthStatus = "Kurang baik";
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
    <title>User Stats</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            height: 100vh;
            background-color: #343a40;
            color: white;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
        }
        .table-container {
            margin-top: 20px;
        }
        .card {
            margin: 10px 0;
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
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .pagination a {
            margin: 0 5px;
            padding: 10px 15px;
            border: 1px solid #007bff;
            color: #007bff;
            text-decoration: none;
            border-radius: 5px;
        }
        .pagination a:hover {
            background-color: #007bff;
            color: #ffffff;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-2 d-none d-md-block sidebar">
                <div class="sidebar-sticky">
                    <h5 class="pt-3">Dyspicare</h5>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="admin_dashboard.php">
                                Dashboards
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="admin_view_users.php">
                                View Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="admin_logout.php">
                                Logout
                            </a>
                        </li>
                </div>
            </nav>
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">User Stats: <?php echo htmlspecialchars($user['nama']); ?></h1>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">User Information</h5>
                        <p>Name: <?php echo htmlspecialchars($user['nama']); ?></p>
                        <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-body">
                        <h5 class="card-title">Average Score</h5>
                        <p>Overall Average Score: <?php echo $healthStatus; ?></p>
                    </div>
                </div>
                <div class="table-container mt-4">
                    <h5 class="card-title">Daily Records</h5>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><a href="?id=<?php echo $userId; ?>&page=<?php echo $page; ?>&order_by=tanggal&order_dir=<?php echo $order_dir == 'ASC' ? 'DESC' : 'ASC'; ?>">Date</a></th>
                                <th>Pola Makan</th>
                                <th>Pola Tidur</th>
                                <th>Pola Minum Obat</th>
                                <th>Tingkat Stress</th>
                                <th>Kebersihan Pribadi</th>
                                <th>Kebersihan Lingkungan</th>
                                <th>Jenis Makanan</th>
                                <th>Jenis Minuman</th>
                                <th>Average Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dailyRecords as $record): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($record['tanggal']); ?></td>
                                    <td><?php echo convertPolaMakan($record['pola_makan']); ?></td>
                                    <td><?php echo convertPolaTidur($record['pola_tidur']); ?></td>
                                    <td><?php echo convertPolaMinumObat($record['pola_minum_obat']); ?></td>
                                    <td><?php echo convertTingkatStress($record['tingkat_stress']); ?></td>
                                    <td><?php echo convertKebersihanPribadi($record['kebersihan_pribadi']); ?></td>
                                    <td><?php echo convertKebersihanLingkungan($record['kebersihan_lingkungan']); ?></td>
                                    <td><?php echo htmlspecialchars($record['jenis_makanan']); ?></td>
                                    <td><?php echo htmlspecialchars($record['jenis_minuman']); ?></td>
                                    <td><?php echo number_format($record['average_score'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?id=<?php echo $userId; ?>&page=<?php echo $page - 1; ?>">Previous</a>
                    <?php endif; ?>

                    <?php if ($page < $totalPages): ?>
                        <a href="?id=<?php echo $userId; ?>&page=<?php echo $page + 1; ?>">Next</a>
                    <?php endif; ?>
                </div>

  

            </main>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById('lineChart').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [<?php foreach ($dailyRecords as $record) { echo "'" . $record['tanggal'] . "',"; } ?>],
                    datasets: [{
                        label: 'Average Score',
                        data: [<?php foreach ($dailyRecords as $record) { echo $record['average_score'] . ","; } ?>],
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        fill: false,
                        tension: 0.1
                    }]
                },
                options: {
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'day',
                                displayFormats: {
                                    day: 'MMM D'
                                }
                            }
                        },
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
