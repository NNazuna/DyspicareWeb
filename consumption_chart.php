<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consumption Chart</title>
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
            width: 600px;
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

        .chart-footer {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="back-btn">
        <a href="landingpage.php">Kembali</a>
    </div>
    <h1>Consumption Chart of Health Indicators</h1>
    <div class="chart-container">
        <div>
            <label for="selectDate">Pilih Tanggal: </label>
            <input type="date" id="selectDate" name="selectDate">
        </div>
        <div class="chart-box">
            <canvas id="consumptionChart"></canvas>
        </div>
        <p id="summaryText"></p>
    </div>

    <script>
        document.getElementById('selectDate').addEventListener('change', function() {
            const selectedDate = this.value;
            fetch('consumption_data.php?date=' + selectedDate)
                .then(response => response.json())
                .then(data => {
                    console.log(data); // Debug: Periksa data yang diterima dari backend

                    if (data.error) {
                        document.getElementById('summaryText').innerText = data.error;
                        return;
                    }

                    const labels = ['Pola Makan', 'Jenis Makanan', 'Jenis Minuman'];
                    const values = [data.pola_makan_score, data.jenis_makanan_score, data.jenis_minuman_score];

                    const ctx = document.getElementById('consumptionChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Consumption Indicators',
                                data: values,
                                backgroundColor: [
                                    'rgba(54, 162, 235, 0.2)',
                                    'rgba(75, 192, 192, 0.2)',
                                    'rgba(255, 206, 86, 0.2)'
                                ],
                                borderColor: [
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(75, 192, 192, 1)',
                                    'rgba(255, 206, 86, 1)'
                                ],
                                borderWidth: 1
                            }]
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

                    // Generate summary text based on the scores
                    const summaryText = `
                        Tanggal: ${selectedDate}\n
                        Pola Makan: ${data.pola_makan_summary}\n
                        Jenis Makanan: ${data.jenis_makanan_summary}\n
                        Jenis Minuman: ${data.jenis_minuman_summary}
                    `;
                    document.getElementById('summaryText').innerText = summaryText;
                })
                .catch(error => console.error('Error:', error));
        });
    </script>

    <div class="chart-footer">
        <p>Data Source: <a href="landing_page.html">Dyspicare</a></p>
    </div>
</body>
</html>
