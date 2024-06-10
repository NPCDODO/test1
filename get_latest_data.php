<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_connect.php';

// ดึงข้อมูล 10 ชุดล่าสุดจากฐานข้อมูล
$sql = "SELECT * FROM dataset ORDER BY time DESC LIMIT 10";
$result = $con->query($sql);

if ($result->num_rows > 0) {
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($rows);
} else {
    echo json_encode([]);
}

$con->close();
?>
