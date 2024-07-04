<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'dbCon.php'; // Include the database connection

$above_or_equal_2 = 0;
$below_2 = 0;
$not_submitted = 0;

function getJenisMakananScore($jenis_makanan) {
    switch ($jenis_makanan) {
        case 'sayuran':
        case 'non_sitrus':
            return 3;
        case 'sitrus':
        case 'telur':
            return 2;
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

try {
    // Query to get users who have submitted forms and their average scores
    $stmt = $conn->prepare("
        SELECT 
            users.id,
            users.nama,
            (
                AVG((pola_makan + pola_tidur + pola_minum_obat + tingkat_stress + kebersihan_pribadi + kebersihan_lingkungan) / 6) +
                AVG((CASE jenis_makanan
                    WHEN 'sayuran' THEN 3
                    WHEN 'non_sitrus' THEN 3
                    WHEN 'sitrus' THEN 2
                    WHEN 'telur' THEN 2
                    WHEN 'pedas' THEN 1
                    WHEN 'gorengan' THEN 1
                    WHEN 'berlemak' THEN 1
                    ELSE 0
                END) / 1) +
                AVG((CASE jenis_minuman
                    WHEN 'air_mineral' THEN 3
                    WHEN 'jus' THEN 3
                    WHEN 'teh' THEN 2
                    WHEN 'kopi' THEN 1
                    WHEN 'soda' THEN 1
                    WHEN 'alkohol' THEN 1
                    ELSE 0
                END) / 1)
            ) / 3 AS average_score
        FROM 
            users
        LEFT JOIN 
            daily_records ON users.id = daily_records.user_id
        GROUP BY 
            users.id
    ");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($users as $user) {
        if ($user['average_score'] !== null) {
            if ($user['average_score'] >= 2) {
                $above_or_equal_2++;
            } else {
                $below_2++;
            }
        } else {
            $not_submitted++;
        }
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
    <title>Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
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
        .card {
            margin: 10px 0;
        }
        #pieChartContainer {
            max-width: 600px;
            margin: 0 auto;
        }
    </style>
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
                    <h1 class="h2">Dashboard</h1>
                </div>
                <div id="pieChartContainer">
                    <div class="card mt-4">
                        <div class="card-body">
                            <h5 class="card-title">User Form Submission Overview</h5>
                            <canvas id="pieChart"></canvas>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            var ctx = document.getElementById('pieChart').getContext('2d');
            var pieChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Sehat', 'Kurang Sehat', 'Tidak ada data'],
                    datasets: [{
                        data: [<?php echo $above_or_equal_2; ?>, <?php echo $below_2; ?>, <?php echo $not_submitted; ?>],
                        backgroundColor: ['#4caf50', '#ffeb3b', '#f44336'],
                        borderColor: ['#388e3c', '#fbc02d', '#d32f2f'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        datalabels: {
                            formatter: (value, context) => {
                                let sum = 0;
                                let dataArr = context.chart.data.datasets[0].data;
                                dataArr.map(data => {
                                    sum += data;
                                });
                                let percentage = (value*100 / sum).toFixed(2)+"%";
                                return percentage;
                            },
                            color: '#fff',
                            font: {
                                weight: 'bold'
                            }
                        },
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    label += context.raw;
                                    return label;
                                }
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });
        });
    </script>
</body>
</html>
