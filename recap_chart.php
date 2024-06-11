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
            width: 80%;
            max-width: 1000px;
            margin: 20px;
        }

        h1 {
            text-align: center;
            color: #343a40;
            margin-bottom: 20px;
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

        .chart-box canvas {
            max-width: 100%;
        }
    </style>
</head>
<body>
<div class="back-btn">
        <a href="landingpage.php">Kembali</a>
    </div>
    <h1>Recap 7 Days Chart</h1>
    <div class="chart-container">
        <div class="chart-box">
            <canvas id="lineChart"></canvas>
        </div>
        <div id="summaryContainer" class="summary-container"></div>
    </div>

    <script>
        fetch('recap_data.php')
            .then(response => response.json())
            .then(data => {
                console.log(data); // Debug: Periksa data yang diterima dari backend

                if (data.error) {
                    document.getElementById('summaryContainer').innerText = data.error;
                    return;
                }

                const labels = data.map(record => record.tanggal);
                const datasets = [
                    {
                        label: 'Pola Makan',
                        data: data.map(record => record.pola_makan),
                        borderColor: 'rgba(75, 192, 192, 1)',
                        fill: false
                    },
                    {
                        label: 'Pola Tidur',
                        data: data.map(record => record.pola_tidur),
                        borderColor: 'rgba(54, 162, 235, 1)',
                        fill: false
                    },
                    {
                        label: 'Pola Minum Obat',
                        data: data.map(record => record.pola_minum_obat),
                        borderColor: 'rgba(255, 206, 86, 1)',
                        fill: false
                    },
                    {
                        label: 'Jenis Makanan',
                        data: data.map(record => record.jenis_makanan),
                        borderColor: 'rgba(153, 102, 255, 1)',
                        fill: false
                    },
                    {
                        label: 'Jenis Minuman',
                        data: data.map(record => record.jenis_minuman),
                        borderColor: 'rgba(255, 159, 64, 1)',
                        fill: false
                    },
                    {
                        label: 'Tingkat Stress',
                        data: data.map(record => record.tingkat_stress),
                        borderColor: 'rgba(255, 99, 132, 1)',
                        fill: false
                    },
                    {
                        label: 'Kebersihan Pribadi',
                        data: data.map(record => record.kebersihan_pribadi),
                        borderColor: 'rgba(75, 192, 192, 0.7)',
                        fill: false
                    },
                    {
                        label: 'Kebersihan Lingkungan',
                        data: data.map(record => record.kebersihan_lingkungan),
                        borderColor: 'rgba(54, 162, 235, 0.7)',
                        fill: false
                    }
                ];

                const ctx = document.getElementById('lineChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: datasets
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 3
                            }
                        }
                    }
                });

                // Generate summary text based on the values
                let summaryMessage = "Rekapitulasi 7 hari terakhir menunjukkan bahwa ";
                const indicators = ['Pola Makan', 'Pola Tidur', 'Pola Minum Obat', 'Jenis Makanan', 'Jenis Minuman', 'Tingkat Stress', 'Kebersihan Pribadi', 'Kebersihan Lingkungan'];
                let overallGood = true;

                indicators.forEach((indicator, index) => {
                    const avgScore = data.reduce((sum, record) => sum + record[datasets[index].label.toLowerCase().replace(' ', '_')], 0) / data.length;
                    summaryMessage += `${indicator} rata-rata adalah ${avgScore.toFixed(2)}. `;
                    if (avgScore < 2.5) {
                        overallGood = false;
                    }
                });

                summaryMessage += overallGood ? "Secara keseluruhan, indikator kesehatan Anda sangat baik." : "Anda perlu memperbaiki beberapa aspek kesehatan Anda.";

                document.getElementById('summaryContainer').innerHTML = `<p><strong>${summaryMessage}</strong></p>`;
            })
            .catch(error => console.error('Error:', error));
    </script>

    <div class="chart-footer">
        <p>Data Source: <a href="landing_page.html">Dyspicare</a></p>
    </div>
</body>
</html>
