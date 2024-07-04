<!DOCTYPE html>
<html lang="en-US">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=Edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Gauge Chart</title>
  <script src="https://unpkg.com/chart.js@2.8.0/dist/Chart.bundle.js"></script>
  <script src="https://unpkg.com/chartjs-gauge@0.3.0/dist/chartjs-gauge.js"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f8f9fa;
      margin: 0;
      padding: 20px;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    canvas {
      -moz-user-select: none;
      -webkit-user-select: none;
      -ms-user-select: none;
    }
    .container {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-top: 20px;
    }
    .chart-container {
      width: 300px;
      height: 350px;
      background: #ffffff;
      border-radius: 15px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      padding: 20px;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    h2 {
      margin-top: 20px;
      color: #343a40;
    }
    label {
      margin-top: 20px;
      font-size: 16px;
      color: #495057;
    }
    input[type="date"] {
      padding: 5px;
      margin-top: 10px;
      border: 1px solid #ced4da;
      border-radius: 5px;
      font-size: 16px;
      color: #495057;
    }
    .summary-text {
      margin-top: 10px;
      font-size: 14px;
      color: #495057;
      text-align: center;
    }
    .back-btn, .nav-links {
      position: absolute;
      top: 20px;
      display: flex;
      gap: 10px;
    }
    .back-btn {
      left: 20px;
    }
    .nav-links {
      right: 20px;
    }
    .nav-links a, .back-btn a {
      text-decoration: none;
      color: #007bff;
      font-weight: bold;
      border: 2px solid #007bff;
      padding: 10px 20px;
      border-radius: 10px;
      transition: background-color 0.3s, color 0.3s;
    }
    .nav-links a:hover, .back-btn a:hover {
      background-color: #007bff;
      color: #ffffff;
    }
  </style>
</head>

<body>


  <h2>Data Pola Hidup</h2>
  <label for="datePicker">Pilih Tanggal: </label>
  <input type="date" id="datePicker" name="datePicker" onchange="fetchData()">
  <div class="container">
    <div class="chart-container">
      <canvas id="gaugeChartPolaMakan"></canvas>
      <h3>Data Pola Makan Anda</h3>
      <div id="polaMakanSummary" class="summary-text"></div>
    </div>
    <div class="chart-container">
      <canvas id="gaugeChartPolaMinumObat"></canvas>
      <h3>Data Pola Minum Obat Anda</h3>
      <div id="polaMinumObatSummary" class="summary-text"></div>
    </div>
    <div class="chart-container">
      <canvas id="gaugeChartPolaTidur"></canvas>
      <h3>Data Pola Tidur Anda</h3>
      <div id="polaTidurSummary" class="summary-text"></div>
    </div>
  </div>
  <script>
    var gaugeChartPolaMakan, gaugeChartPolaMinumObat, gaugeChartPolaTidur;

    function createGaugeChart(ctx, value) {
      return new Chart(ctx, {
        type: 'gauge',
        data: {
          datasets: [{
            data: [1, 2, 3],
            value: value,
            backgroundColor: ['#ff0000', '#ffff00', '#00ff00'],
            borderWidth: 2
          }]
        },
        options: {
          responsive: true,
          needle: {
            radiusPercentage: 2,
            widthPercentage: 3.2,
            lengthPercentage: 80,
            color: 'rgba(0, 0, 0, 1)'
          },
          valueLabel: {
            formatter: function (value) {
              if (value === 3) return 'Sangat Baik';
              if (value === 2.5) return 'Sangat Baik';
              if (value === 2) return 'Baik';
              if (value === 1.5) return 'Kurang baik';
              if (value === 1) return 'Kurang';
              return Math.round(value);
            }
          },
          title: {
            display: true,
            text: 'Gauge Chart'
          },
          layout: {
            padding: {
              bottom: 30
            }
          },
          animation: {
            animateRotate: true,
            animateScale: false
          }
        }
      });
    }

    function fetchData() {
      var date = document.getElementById('datePicker').value;
      if (!date) return;
      fetch(`gauge_data.php?date=${date}`)
        .then(response => response.json())
        .then(data => {
          if (data.error) {
            alert(data.error);
          } else {
            if (gaugeChartPolaMakan) gaugeChartPolaMakan.destroy();
            if (gaugeChartPolaMinumObat) gaugeChartPolaMinumObat.destroy();
            if (gaugeChartPolaTidur) gaugeChartPolaTidur.destroy();

            var ctxPolaMakan = document.getElementById('gaugeChartPolaMakan').getContext('2d');
            var ctxPolaMinumObat = document.getElementById('gaugeChartPolaMinumObat').getContext('2d');
            var ctxPolaTidur = document.getElementById('gaugeChartPolaTidur').getContext('2d');

            gaugeChartPolaMakan = createGaugeChart(ctxPolaMakan, data.pola_makan);
            gaugeChartPolaMinumObat = createGaugeChart(ctxPolaMinumObat, data.pola_minum_obat);
            gaugeChartPolaTidur = createGaugeChart(ctxPolaTidur, data.pola_tidur);

            updateSummaryText('polaMakanSummary', data.pola_makan, 'Pola makan');
            updateSummaryText('polaMinumObatSummary', data.pola_minum_obat, 'Pola minum obat');
            updateSummaryText('polaTidurSummary', data.pola_tidur, 'Pola tidur');
          }
        });
    }

    function updateSummaryText(elementId, value, type) {
      let summaryText = '';
      if (type === 'Pola tidur') {
        summaryText = value === 3 
          ? `${type} Anda sudah sangat baik, pertahankan.` 
          : value === 2 
            ? 'Pola tidur Anda sudah baik, namun supaya lebih optimal tingkatkan lagi jam tidur Anda.'
            : 'Anda memerlukan tidur setidaknya 6-7 jam.';
      } else if (type === 'Pola makan') {
        summaryText = value === 3 
          ? 'Pola makan Anda sudah baik, pertahankan.' 
          : value < 2 
            ? 'Pola makan Anda kurang baik, cobalah lebih sering mengkonsumsi makanan yang sehat seperti sayuran.' 
            : 'Pola makan Anda perlu diperbaiki.Cobalah lebih sering mengkonsumsi makanan seperti sayuran atau buah non asam';
      } else if (type === 'Pola minum obat') {
        summaryText = value === 3 
          ? 'Pola minum obat Anda sudah baik, pertahankan.' 
          : value < 2 
            ? 'Pola minum obat Anda masih kurang, Anda perlu lebih rutin minum obat.' 
            : 'Anda harus meminum obat sesuai saran dokter.';
      }
      document.getElementById(elementId).textContent = summaryText;
    }

    window.onload = function() {
      var ctxPolaMakan = document.getElementById('gaugeChartPolaMakan').getContext('2d');
      var ctxPolaMinumObat = document.getElementById('gaugeChartPolaMinumObat').getContext('2d');
      var ctxPolaTidur = document.getElementById('gaugeChartPolaTidur').getContext('2d');
      gaugeChartPolaMakan = createGaugeChart(ctxPolaMakan, 2);
      gaugeChartPolaMinumObat = createGaugeChart(ctxPolaMinumObat, 2);
      gaugeChartPolaTidur = createGaugeChart(ctxPolaTidur, 2);
    }
  </script>
</body>
</html>
