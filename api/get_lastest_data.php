<?php
session_start();
include_once('../config/database.php');

header('Content-Type: application/json');
$response = [];

if ($conn->connect_error) {
    $response = [
        'success' => false,
        'message' => 'Database connection failed: ' . $conn->connect_error
    ];
    echo json_encode($response);
    exit;
}

$query = "SELECT * FROM sensor_data ORDER BY timestamp DESC LIMIT 1";
$result = $conn->query($query);

if ($result) {
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $_SESSION['sensor_data'] = $data;  // Lưu dữ liệu vào session
        $response = [
            'success' => true,
            'data' => $data
        ];
    } else {
        $response = [
            'success' => true,
            'message' => 'No data found'
        ];
    }
} else {
    $response = [
        'success' => false,
        'message' => 'Error executing query: ' . $conn->error
    ];
}

echo json_encode($response);
$conn->close();
?>
