<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail</title>
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

        .back-btn a, .additional-links a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
            border: 2px solid #007bff;
            padding: 10px 20px;
            border-radius: 10px;
            transition: background-color 0.3s, color 0.3s;
            margin-right: 10px;
        }

        .back-btn a:hover, .additional-links a:hover {
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

        .additional-links {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <h1>Detail</h1>
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
            const allIndicatorsGood = values.every(value => value === 3);
            if (allIndicatorsGood) {
                return "Seluruh data Anda sudah sangat baik, pertahankan!";
            } else {
                let suggestions = [];
                if (values[0] < 3) suggestions.push("Cobalah lebih sering makan makanan sehat.");
                if (values[1] < 3) suggestions.push("Cobalah terapkan tidur 7-8 jam untuk hasil lebih maksimal.");
                if (values[2] < 3) suggestions.push("Cobalah lebih sering minum obat sesuai aturan.");
                if (values[3] < 3) suggestions.push("Cobalah makan makanan yang lebih bergizi.");
                if (values[4] < 3) suggestions.push("Cobalah lebih sering minum air putih.");
                if (values[5] < 3) suggestions.push("Kurangi tingkat stress dengan aktivitas menyenangkan.");
                if (values[6] < 3) suggestions.push("Tingkatkan kebersihan pribadi.");
                if (values[7] < 3) suggestions.push("Jaga kebersihan lingkungan sekitar.");

                return suggestions.join(" ");
            }
        }

        function convertScoreToText(score) {
            switch (score) {
                case 3:
                    return 'sangat baik';
                case 2:
                    return 'baik';
                case 1:
                    return 'kurang';
                default:
                    return 'tidak diketahui';
            }
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
                            scales: {
                                r: {
                                    beginAtZero: true,
                                    min: 0,
                                    max: 3,
                                    ticks: {
                                        stepSize: 1,
                                        callback: function(value) {
                                            if (value === 1) return 'Kurang';
                                            if (value === 2) return 'Baik';
                                            if (value === 3) return 'Sangat Baik';
                                            return value;
                                        }
                                    }
                                }
                            }
                        }
                    });

                    // Generate summary text based on the values
                    const summaryMessage = getSummaryMessage(values);
                    const summaryText = `
                        <p>Tanggal: ${selectedDate}</p>
                        <p><strong>${summaryMessage}</strong></p>
                    `;
                    document.getElementById('summaryContainer').innerHTML = summaryText;
                })
                .catch(error => console.error('Error:', error));
        });

        // Trigger initial data load
        document.getElementById('selectDate').dispatchEvent(new Event('change'));
    </script>
</body>
</html>
