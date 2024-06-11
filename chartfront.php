<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Chart</title>
    <!-- Tambahkan script untuk memuat Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <canvas id="dataChart" width="800" height="400"></canvas>

    <script>
        // Ambil data dari chart_data.php menggunakan AJAX
        fetch('chart.php')
        .then(response => response.json())
        .then(data => {
            // Data berhasil diambil, buat grafik
            const labels = data.map(item => item.label);
            const datasets = Object.keys(data[0]) // Ambil nama kolom
                .filter(key => key !== 'label') // Hapus kolom label
                .map(key => ({
                    label: key,
                    data: data.map(item => item[key]),
                    backgroundColor: getRandomColor(),
                    borderColor: '#fff',
                    borderWidth: 1
                }));

            const ctx = document.getElementById('dataChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar', // Ganti dengan jenis chart yang diinginkan
                data: {
                    labels: labels,
                    datasets: datasets
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        })
        .catch(error => console.error('Error:', error));

        // Fungsi untuk menghasilkan warna acak
        function getRandomColor() {
            const letters = '0123456789ABCDEF';
            let color = '#';
            for (let i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }
    </script>
</body>
</html>
