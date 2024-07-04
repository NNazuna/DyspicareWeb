
<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'dbCon.php'; // Include the database connection

$order_by = 'tanggal'; // Default order
$order_dir = 'ASC'; // Default order direction

if (isset($_GET['order_by'])) {
    $order_by = in_array($_GET['order_by'], ['tanggal', 'user_id']) ? $_GET['order_by'] : 'tanggal';
}

if (isset($_GET['order_dir'])) {
    $order_dir = $_GET['order_dir'] == 'DESC' ? 'DESC' : 'ASC';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    try {
        $deleteStmt = $conn->prepare("DELETE FROM daily_records WHERE id = :id");
        $deleteStmt->bindParam(':id', $delete_id, PDO::PARAM_INT);
        $deleteStmt->execute();
        header("Location: admin_view_daily_records.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

try {
    $stmt = $conn->prepare("SELECT * FROM daily_records ORDER BY $order_by $order_dir");
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Daily Records</title>
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

        h2 {
            margin-top: 20px;
            color: #343a40;
        }

        table {
            border-collapse: collapse;
            width: 80%;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
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

        .delete-btn {
            color: red;
            cursor: pointer;
            border: none;
            background: none;
            padding: 0;
            text-decoration: underline;
        }

        .sortable {
            cursor: pointer;
        }

        .sortable:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <h2>Daily Records List</h2>
    <table>
        <tr>
            <th><a href="?order_by=user_id&order_dir=<?php echo $order_dir == 'ASC' ? 'DESC' : 'ASC'; ?>" class="sortable">User ID</a></th>
            <th><a href="?order_by=tanggal&order_dir=<?php echo $order_dir == 'ASC' ? 'DESC' : 'ASC'; ?>" class="sortable">Tanggal</a></th>
            <th>Umur</th>
            <th>Pola Makan</th>
            <th>Jenis Makanan</th>
            <th>Pola Tidur</th>
            <th>Pola Minum Obat</th>
            <th>Jenis Minuman</th>
            <th>Tingkat Stress</th>
            <th>Kebersihan Pribadi</th>
            <th>Kebersihan Lingkungan</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($records as $record): ?>
            <tr>
                <td><?php echo htmlspecialchars($record['user_id']); ?></td>
                <td><?php echo htmlspecialchars($record['tanggal']); ?></td>
                <td><?php echo htmlspecialchars($record['umur']); ?></td>
                <td><?php echo htmlspecialchars($record['pola_makan']); ?></td>
                <td><?php echo htmlspecialchars($record['jenis_makanan']); ?></td>
                <td><?php echo htmlspecialchars($record['pola_tidur']); ?></td>
                <td><?php echo htmlspecialchars($record['pola_minum_obat']); ?></td>
                <td><?php echo htmlspecialchars($record['jenis_minuman']); ?></td>
                <td><?php echo htmlspecialchars($record['tingkat_stress']); ?></td>
                <td><?php echo htmlspecialchars($record['kebersihan_pribadi']); ?></td>
                <td><?php echo htmlspecialchars($record['kebersihan_lingkungan']); ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="delete_id" value="<?php echo $record['id']; ?>">
                        <button type="submit" class="delete-btn">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <div class="back-btn">
        <a href="admin_dashboard.php">Back to Dashboard</a>
    </div>
</body>

</html>
