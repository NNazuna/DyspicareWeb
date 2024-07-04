<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'dbCon.php';

if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User';
$user_id = $_SESSION['user_id'];



function getDailyRecords($user_id, $date, $conn) {
    $stmt = $conn->prepare("SELECT * FROM daily_records WHERE user_id = :user_id AND tanggal = :tanggal LIMIT 1");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':tanggal', $date, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function convertValueToText($key, $value) {
    $conversion = [
        'pola_makan' => ['3' => '3x sehari', '2' => '2x sehari', '1' => '1x sehari'],
        'pola_tidur' => ['3' => '7-8 jam', '2' => '4-6 jam', '1' => 'kurang dari 3 jam'],
        'pola_minum_obat' => ['3' => 'Rutin', '2' => 'Kurang rutin', '1' => 'Tidak minum obat'],
        'tingkat_stress' => ['3' => 'Rendah', '2' => 'Sedang', '1' => 'Tinggi'],
        'kebersihan_pribadi' => ['3' => 'Baik', '2' => 'Cukup', '1' => 'Buruk'],
        'kebersihan_lingkungan' => ['3' => 'Baik', '2' => 'Cukup', '1' => 'Buruk']
    ];
    return $conversion[$key][$value] ?? $value;
}

function extractDate($text) {
    // Cek format tanggal dalam teks: d-m-Y atau d F Y
    if (preg_match('/(\d{1,2}[-\/\s](\d{1,2}|\w+)[-\/\s]\d{4})/', $text, $matches)) {
        $date = DateTime::createFromFormat('d-m-Y', $matches[0]) ?: DateTime::createFromFormat('d F Y', $matches[0]);
        if ($date) {
            return $date->format('Y-m-d');
        }
    }
    return null;
}

$response = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userPrompt = trim($_POST['prompt']);
    $date = extractDate($userPrompt) ?: date('Y-m-d'); // Ekstraksi tanggal dari prompt, default ke hari ini

    if (!empty($userPrompt)) {
        $daily_record = getDailyRecords($user_id, $date, $conn);
        $record_text = '';

        if ($daily_record && is_array($daily_record)) {
            foreach ($daily_record as $key => $value) {
                if ($key != 'id' && $key != 'user_id' && $key != 'tanggal' && $key != 'umur') {
                    $record_text .= ucfirst(str_replace('_', ' ', $key)) . ": " . convertValueToText($key, $value) . "\n";
                }
            }
        }

        if (!empty($record_text)) {
            $prompt = "You are a helpful assistant that provides guidance and information about stomach ulcers (maag) and their treatment. Please answer the user's questions clearly and helpfully. If the user asks for a list, provide it in bullet points.\n\nUser data for $date:\n$record_text\n\n$user_name: $userPrompt\nAI:";
        } else {
            $prompt = "You are a helpful assistant that provides guidance and information about stomach ulcers (maag) and their treatment. Please answer the user's questions clearly and helpfully. If the user asks for a list, provide it in bullet points.\n\n$user_name: $userPrompt\nAI:";
        }

        $ch = curl_init('https://api.openai.com/v1/chat/completions');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful assistant that provides guidance and information about stomach ulcers (maag) and their treatment.'],
                    ['role' => 'user', 'content' => $userPrompt],
                    ['role' => 'system', 'content' => !empty($record_text) ? "User data:\n$record_text" : ""]
                ],
                'max_tokens' => 700,
                'temperature' => 0.7,
                'top_p' => 1.0,
                'frequency_penalty' => 0.0,
                'presence_penalty' => 0.0,
                'stop' => null
            ]),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: ' . 'Bearer ' . $OPENAI_API_KEY
            ]
        ]);

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            $response = 'Error: ' . curl_error($ch);
        } else {
            $decoded_json = json_decode($result, true);
            if (isset($decoded_json['choices'][0]['message']['content'])) {
                $response = trim($decoded_json['choices'][0]['message']['content']);
            } else {
                $response = 'Oops! terdapat kesalahan';
            }
        }

        curl_close($ch);

        $stmt = $conn->prepare("INSERT INTO conversations (user_id, user_message, bot_response) VALUES (:user_id, :user_message, :bot_response)");
        $stmt->execute([
            ':user_id' => $user_id,
            ':user_message' => $userPrompt,
            ':bot_response' => $response
        ]);
    } else {
        $response = 'Prompt is empty. Please enter a message.';
    }
}

$stmt = $conn->prepare("SELECT user_message, bot_response FROM conversations WHERE user_id = :user_id ORDER BY id ASC");
$stmt->execute([':user_id' => $user_id]);
$conversations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot for Stomach Ulcer</title>
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Mulish', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        h1 {
            text-align: center;
            color: #343a40;
            margin-top: 20px;
            font-size: 2rem;
        }

        .chat {
            width: 80%; /* Lebar chatbox lebih besar */
            max-width: 1000px; /* Lebar maksimal lebih besar */
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            max-height: 60vh;  scrollbar-width: thin;
            scrollbar-color: #007bff #f0f2f5; /* Warna scrollbar */
        }

        .user-message, .assistant-message {
            margin: 10px 0;
            padding: 10px;
            border-radius: 8px;
        }

        .user-message {
            background-color: #e9ecef;
            text-align: left;
        }

        .user-message p {
            color: #495057;
            margin: 0;
        }

        .assistant-message {
            background-color: #007bff;
            color: #ffffff;
            text-align: left;
        }

        .assistant-message p {
            color: #ffffff;
            margin: 0;
        }

        form {
            width: 80%; /* Lebar form lebih besar */
            max-width: 1000px; /* Lebar maksimal lebih besar */
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: #495057;
        }

        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            resize: vertical;
        }

        input[type="submit"], #clear-history {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover, #clear-history:hover {
            background-color: #0056b3;
        }

        #clear-history {
            background-color: #6c757d;
            margin-top: 10px;
        }
      

        .back-button {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 1rem;
    cursor: pointer;
    position: absolute;
    top: 20px; /* Atur jarak dari atas */
    right: 20px; /* Atur jarak dari kanan */
}

.back-button:hover {
    background-color: #0056b3;
}

    </style>
</head>
<body>
    <h1>Tanya Dokter</h1>

    <div class="chat">
        <?php foreach ($conversations as $conversation): ?>
            <div class="user-message">
                <p><strong>You:</strong> <?= htmlspecialchars($conversation['user_message']) ?></p>
            </div>
            <div class="assistant-message">
                <p><?= nl2br(htmlspecialchars($conversation['bot_response'])) ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <form id="chat-form" method="post" action="">
        <label for="prompt">Kirim Pesan:</label>
        <textarea name="prompt" id="prompt" rows="4" cols="50"></textarea>
        <input type="submit" name="submit" value="Send">
        <button type="button" id="clear-history">Clear History</button>
        <a href="landingpage.php" class="back-button">Home</a>
    
    
    </form>


    <script>
    // Fungsi untuk mengatur scroll ke bagian bawah
    function scrollToBottom() {
        var chatContainer = document.querySelector('.chat');
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    // Panggil fungsi scrollToBottom saat halaman dimuat
    window.onload = function() {
        scrollToBottom();
    };

    // Panggil fungsi scrollToBottom setelah mengirim pesan
    document.getElementById('chat-form').addEventListener('submit', function() {
        setTimeout(scrollToBottom, 100); // Delay agar scroll berfungsi setelah pesan diproses
    });

    // Tambahkan event listener untuk tombol clear history
    document.getElementById('clear-history').addEventListener('click', function() {
        if (confirm("Apakah Anda yakin ingin menghapus riwayat chat?")) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'clear_history.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    // Bersihkan elemen chat setelah penghapusan
                    document.querySelector('.chat').innerHTML = '';
                }
            };
            xhr.send('clear=1');
        }
    });
</script>
</body>
</html>
