<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi</title>
    <style>
        body {
            font-family: "Mulish", sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .notification-box {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 20px;
            box-shadow: 5px 5px 10px gray;
            max-width: 600px;
            text-align: center;
        }
        .notification-box h1 {
            color: #343a40;
        }
        .notification-box p {
            font-size: 1.2em;
            color: #343a40;
        }
        .back-btn {
            margin-top: 20px;
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
    </style>
</head>
<body>
    <div class="notification-box">
        <h1>Notifikasi 30 Hari</h1>
        <p><?php
            session_start();
            if (isset($_SESSION['notification'])) {
                echo $_SESSION['notification'];
                unset($_SESSION['notification']);
            } else {
                echo "Tidak ada notifikasi.";
            }
        ?></p>
        <div class="back-btn">
            <a href="landingpage.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
