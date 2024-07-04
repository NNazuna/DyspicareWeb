<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recap 7 Days Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: "Mulish", sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }

        .chart-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .chart-box {
            width: 100%;
            max-width: 800px;
            margin: 20px;
        }

        h1 {
            text-align: center;
            color: #343a40;
            margin-bottom: 20px;
        }

        .back-btn {
            position: absolute;
            top: 20px;
            left: 20px;
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

        .summary-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 20px;
            box-shadow: 5px 5px 10px gray;
            text-align: center;
            max-width: 800px;
            margin: 20px auto;
        }

        .chart-footer {
            text-align: center;
            margin-top: 20px;
        }

        .summary-container p {
            margin: 5px 0;
            font-size: 1.1em;
            color: #343a40;
        }
    </style>
</head>
<body>
    <!-- <div class="back-btn">
        <a href="gauge.php">Kembali</a>
    </div> -->
    <h1>Recap 7 Days Chart</h1>
    <div class="chart-container">
        <div class="chart-box">
            <canvas id="lineChart"></canvas>
        </div>
        <div id="summaryContainer" class="summary-container"></div>
    </div>

    <script>
async function fetchData() {
    const response = await fetch('recap_data.php');
    const data = await response.json();

    if (data.error) {
        document.getElementById('summaryContainer').innerText = data.error;
        return;
    }

    const labels = [];
    const averageScores = [];

    data.forEach(record => {
        labels.push(record.tanggal);
        averageScores.push(record.average_score);
    });

    const ctx = document.getElementById('lineChart').getContext('2d');
    const lineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels.slice(-7), // Show only the last 7 dates
            datasets: [
                {
                    label: 'Average Score',
                    data: averageScores.slice(-7), // Show only the last 7 scores
                    borderColor: 'rgba(75, 192, 192, 1)',
                    fill: false
                }
            ]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    max: 3,
                    ticks: {
                        callback: function(value, index, values) {
                            if (value === 3) {
                                return 'Sangat Baik';
                            } else if (value === 2) {
                                return 'Baik';
                            } else if (value === 1) {
                                return 'Kurang';
                            } else {
                                return ''; // Empty string for other values
                            }
                        },
                        stepSize: 1  // Adjusted to show only "Sangat Baik", "Baik", and "Kurang"
                    }
                }
            },
            elements: {
                point: {
                    radius: 3,
                    hoverRadius: 5
                }
            }
        }
    });

    const avg = arr => arr.reduce((a, b) => a + b, 0) / arr.length;
    const avgOverall = avg(averageScores.slice(-7)).toFixed(2);

    let healthIndicator;
    if (avgOverall > 2.5) {
        healthIndicator = "sangat baik";
    } else if (avgOverall >= 2) {
        healthIndicator = "baik";
    } else {
        healthIndicator = "kurang baik";
    }

    const summaryText = `
        <p>Rekapitulasi 7 hari terakhir menunjukkan bahwa skor rata-rata Anda adalah ${healthIndicator}. 
        
    `;
    document.getElementById('summaryContainer').innerHTML = summaryText;
}

fetchData();



    </script>

    <div class="chart-footer">
        <p>Jika Anda masih memiliki pertanyaan terkait maag,Silahkan klik <a href="chatbotfend.php">disini</a></p>
    </div>
</body>
</html>
