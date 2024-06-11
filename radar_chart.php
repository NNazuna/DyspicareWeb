<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Radar Chart</title>
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

        .date-selector {
            margin-bottom: 20px;
        }

        .summary-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 20px;
            box-shadow: 5px 5px 10px gray;
            text-align: center;
            max-width: 600px;
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
    <h1>Radar Chart of Health Indicators</h1>
    <div class="chart-container">
        <div class="date-selector">
            <label for="selectDate">Pilih Tanggal: </label>
            <input type="date" id="selectDate" name="selectDate">
        </div>
        <div class="chart-box">
            <canvas id="radarChart"></canvas>
        </div>
        <div id="summaryContainer" class="summary-container"></div>
    </div>

    <script>
        let radarChart;

        function getSummaryMessage(values) {
            let messages = [];
            if (values.every(value => value === 3)) {
                messages.push("Semua indikator kesehatan Anda sangat baik. Terus pertahankan!");
            } else {
                if (values[0] === 3) messages.push("Pola makan Anda sangat baik.");
                else messages.push("Cobalah untuk memperbaiki pola makan Anda.");

                if (values[1] === 3) messages.push("Pola tidur Anda sangat baik.");
                else messages.push("Cobalah untuk memperbaiki pola tidur Anda.");

                if (values[2] === 3) messages.push("Anda rutin minum obat dengan baik.");
                else messages.push("Cobalah untuk lebih rutin dalam minum obat.");

                if (values[3] === 3) messages.push("Jenis makanan yang Anda konsumsi sangat baik.");
                else messages.push("Cobalah untuk mengkonsumsi makanan yang lebih sehat.");

                if (values[4] === 3) messages.push("Jenis minuman yang Anda konsumsi sangat baik.");
                else messages.push("Cobalah untuk mengkonsumsi minuman yang lebih sehat.");

                if (values[5] === 3) messages.push("Tingkat stress Anda sangat baik.");
                else messages.push("Cobalah untuk mengurangi tingkat stress Anda.");

                if (values[6] === 3) messages.push("Kebersihan pribadi Anda sangat baik.");
                else messages.push("Cobalah untuk memperbaiki kebersihan pribadi Anda.");

                if (values[7] === 3) messages.push("Kebersihan lingkungan Anda sangat baik.");
                else messages.push("Cobalah untuk memperbaiki kebersihan lingkungan Anda.");
            }
            return messages.join(" ");
        }

        document.getElementById('selectDate').addEventListener('change', function() {
            const selectedDate = this.value;
            fetch('radar_data.php?date=' + selectedDate)
                .then(response => response.json())
                .then(data => {
                    console.log(data); // Debug: Periksa data yang diterima dari backend

                    if (data.error) {
                        document.getElementById('summaryContainer').innerText = data.error;
                        return;
                    }

                    const labels = ['Pola Makan', 'Pola Tidur', 'Pola Minum Obat', 'Jenis Makanan', 'Jenis Minuman', 'Tingkat Stress', 'Kebersihan Pribadi', 'Kebersihan Lingkungan'];
                    const values = [
                        data.pola_makan,
                        data.pola_tidur,
                        data.pola_minum_obat,
                        data.jenis_makanan,
                        data.jenis_minuman,
                        data.tingkat_stress,
                        data.kebersihan_pribadi,
                        data.kebersihan_lingkungan
                    ];

                    // Hancurkan chart lama jika ada
                    if (radarChart) {
                        radarChart.destroy();
                    }

                    const ctx = document.getElementById('radarChart').getContext('2d');
                    radarChart = new Chart(ctx, {
                        type: 'radar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Health Indicators',
                                data: values,
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 2
                            }]
                        },
                        options: {
                            scale: {
                                ticks: {
                                    beginAtZero: true,
                                    max: 3
                                }
                            }
                        }
                    });

                    // Generate summary text based on the values
                    const summaryMessage = getSummaryMessage(values);
                    const summaryText = `
                        <p>Tanggal: ${selectedDate}</p>
                        <p>Pola Makan: ${data.pola_makan}</p>
                        <p>Pola Tidur: ${data.pola_tidur}</p>
                        <p>Pola Minum Obat: ${data.pola_minum_obat}</p>
                        <p>Jenis Makanan: ${data.jenis_makanan}</p>
                        <p>Jenis Minuman: ${data.jenis_minuman}</p>
                        <p>Tingkat Stress: ${data.tingkat_stress}</p>
                        <p>Kebersihan Pribadi: ${data.kebersihan_pribadi}</p>
                        <p>Kebersihan Lingkungan: ${data.kebersihan_lingkungan}</p>
                        <p><strong>${summaryMessage}</strong></p>
                    `;
                    document.getElementById('summaryContainer').innerHTML = summaryText;
                })
                .catch(error => console.error('Error:', error));
        });

        // Trigger initial data load
        document.getElementById('selectDate').dispatchEvent(new Event('change'));
    </script>

    <div class="chart-footer">
        <p>Data Source: <a href="recap_chart.php">Dyspicare</a></p>
    </div>
</body>
</html>
