<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@700&display=swap');

        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            display: flex;
        }

        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: #fff;
            display: flex;
            flex-direction: column;
            padding: 20px;
            position: fixed;
            top: 0;
            bottom: 0;
            transition: width 0.3s;
            overflow-x: hidden;
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar h2 {
            display: flex;
            align-items: center;
            transition: opacity 0.3s;
        }

        .sidebar.collapsed h2 {
            opacity: 0;
        }

        .sidebar a {
            text-decoration: none;
            color: #fff;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            transition: background-color 0.3s, padding-left 0.3s;
        }

        .sidebar a:hover {
            background-color: #007bff;
        }

        .sidebar.collapsed a {
            padding-left: 20px;
        }

        .main-content {
            flex-grow: 1;
            padding: 30px;
            margin-left: 250px;
            transition: margin-left 0.3s;
        }

        .main-content.collapsed {
            margin-left: 80px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            margin-left: 20px;
            transition: margin-left 0.3s;
        }

        .header a {
            text-decoration: none;
            color: #fff;
            padding: 10px;
            border: 2px solid #fff;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .header a:hover {
            background-color: #0056b3;
        }

        h1 {
            margin-top: 20px;
            color: #fff;
            font-family: 'Roboto', sans-serif;
            font-size: 36px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .iframe-container {
            margin-top: 20px;
        }

        .iframe-container iframe {
            width: 100%;
            height: 600px;
            border: none;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .toggle-btn {
            background-color: #007bff;
            border: none;
            color: #fff;
            cursor: pointer;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s;
            position: absolute;
            top: 40px;
            right: -18px;
        }

        .toggle-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="sidebar" id="sidebar">
        <h2>Menu</h2>
        <a href="gauge.php" target="contentFrame">Data Pola Hidup Anda</a>
        <a href="radar_chart.php" target="contentFrame">Detail</a>
        <a href="recap_chart.php" target="contentFrame">Statistik dalam 7 Hari</a>
        
    </div>

    <div class="main-content" id="mainContent">
        <div class="header" id="header">
            <h1>Dashboard</h1>
            <a href="landingpage.php">Home</a>
        </div>

        <div class="iframe-container">
            <iframe name="contentFrame" src="gauge.php"></iframe>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const header = document.getElementById('header');
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('collapsed');
            if (sidebar.classList.contains('collapsed')) {
                header.style.marginLeft = '25px';
            } else {
                header.style.marginLeft = '25px';
            }
        }
    </script>
</body>
</html>
