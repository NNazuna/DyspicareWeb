<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'dbCon.php'; // Include the database connection

$above_or_equal_2 = 0;
$below_2 = 0;
$not_submitted = 0;

try {
    // Query to get users who have submitted forms and their average scores
    $stmt = $conn->prepare("
        SELECT 
            users.id,
            users.nama,
            AVG((pola_makan + pola_tidur + pola_minum_obat + tingkat_stress + kebersihan_pribadi + kebersihan_lingkungan) / 6) AS average_score
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Pie Chart</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Data Form User</h2>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <canvas id="pieChart"></canvas>
            </div>
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
                }
            });
        });
    </script>
</body>
</html>
