<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Gauge Chart Example</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['gauge']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            fetch('gauge_data.php')
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    var score = calculateHealthScore(data.pola_makan, data.pola_tidur, data.pola_minum_obat, data.jenis_makanan, data.jenis_minuman, data.tingkat_stress, data.kebersihan_pribadi, data.kebersihan_lingkungan);

                    var data = google.visualization.arrayToDataTable([
                        ['Label', 'Value'],
                        ['Health', score]
                    ]);

                    var options = {
                        width: 400, height: 120,
                        redFrom: 0, redTo: 40,
                        yellowFrom: 40, yellowTo: 75,
                        greenFrom: 75, greenTo: 100,
                        minorTicks: 5
                    };

                    var chart = new google.visualization.Gauge(document.getElementById('gauge_chart'));
                    chart.draw(data, options);
                })
                .catch(error => console.error('Error:', error));
        }

        function calculateHealthScore(pola_makan, pola_tidur, pola_minum_obat, jenis_makanan, jenis_minuman, tingkat_stress, kebersihan_pribadi, kebersihan_lingkungan) {
            // Convert form values to scores (simplified example)
            var score = 0;
            score += parseInt(pola_makan);
            score += parseInt(pola_tidur);
            score += parseInt(pola_minum_obat);
            score += parseInt(jenis_makanan);
            score += parseInt(jenis_minuman);
            score += parseInt(tingkat_stress);
            score += parseInt(kebersihan_pribadi);
            score += parseInt(kebersihan_lingkungan);

            // Normalize score to percentage (0-100)
            return (score / 24) * 100;
        }
    </script>
</head>
<body>
    <h1>Google Gauge Chart</h1>
    <div id="gauge_chart" style="width: 400px; height: 120px;"></div>
</body>
</html>
