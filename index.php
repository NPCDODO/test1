<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Temperature and Humidity Chart</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .top-bar {
            background-color: #000;
            color: #fff;
            padding: 15px 20px;
            text-align: left;
            font-size: 24px;
            font-weight: bold;
        }
        #data-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            margin: 4%;
        }
        .device-container {
            background-color: #fff;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 20px;
            width: 230px;
            margin: 10px;
        }
        .data-item {
            margin-top: 5px;
            padding-top: 5px;
            border-top: 1px solid #ccc;
        }
        .data-item:first-child {
            border-top: none;
            padding-top: 0;
        }
        .temperature-high {
            color: red;
        }
        .table-container {
            margin: 20px 30px;
        }
        .table-container table {
            width: 100%;
        }
    </style>
</head>
<body>

<div class="top-bar">Temperature and Humidity Chart</div>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_connect.php';

$sql = "SELECT * FROM dataset ORDER BY time DESC";
$result = $con->query($sql);

$dataGrouped = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $device_id = $row['device_id'];
        if (!isset($dataGrouped[$device_id])) {
            $dataGrouped[$device_id] = [];
        }
        $dataGrouped[$device_id][] = $row;
    }
}

echo "<div id='data-container'>";
foreach ($dataGrouped as $device_id => $deviceData) {
    echo "<div class='device-container'>";
    echo "<h2 style='margin-top: 0;'>Device ID: $device_id</h2>";
    foreach ($deviceData as $data) {
        if ($data['key'] === 'Temperature') {
            $temperature = $data['data'];
            $class = $temperature > 30 ? 'temperature-high' : '';
            echo "<div class='data-item $class'>";
            echo "<div>Time: {$data['time']}</div>";
            echo "<div>Temperature: $temperature</div>";
            echo "</div>";
        } elseif ($data['key'] === 'Humidity') {
            echo "<div class='data-item'>";
            echo "<div>Time: {$data['time']}</div>";
            echo "<div>Humidity: {$data['data']}</div>";
            echo "</div>";
        }
    }
    echo "</div>";
}
echo "</div>";

$con->close();
?>

<div class="table-container">
    <h2>Data from Database</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Time</th>
                <th>Device ID</th>
                <th>Key</th>
                <th>Data</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include 'db_connect.php';
            $sql = "SELECT * FROM dataset";
            $result = $con->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['time']}</td>
                            <td>{$row['device_id']}</td>
                            <td>{$row['key']}</td>
                            <td>{$row['data']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No data found</td></tr>";
            }
            $con->close();
            ?>                
        </tbody>
    </table>
</div>

</body>
</html>
