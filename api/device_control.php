<?php

require_once __DIR__ . '/../vendor/autoload.php';
use Bluerhinos\phpMQTT;

header('Content-Type: application/json');

$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Lấy thông tin từ request
$device = isset($data['device']) ? $data['device'] : null;
$state = isset($data['state']) ? (int)$data['state'] : null;

// Các thiết bị và trạng thái hợp lệ
$allowedDevices = ['fan', 'ac', 'light', 'all', 'led1', 'led2'];
$allowedStates = [0, 1];

// Kiểm tra tính hợp lệ của dữ liệu đầu vào
if (!$device || !in_array($device, $allowedDevices) || !in_array($state, $allowedStates)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid input. Allowed devices: fan, ac, light. States: 1 (on), 0 (off).'
    ]);
    exit;
}

// Cấu hình MQTT
$server = 'localhost';             // Địa chỉ máy chủ MQTT
$port = 1337;                      // Cổng MQTT
$username = 'levantuyen1';         // Tên đăng nhập MQTT
$password = 'b21dcat218';
$clientId = "phpMQTT-clientxyz";
$mqtt = new phpMQTT($server, $port, $clientId);

// Định nghĩa topic và thông điệp
$topic = "home/$device/control";
$message = $state === 1 ? "on" : "off";
if($device=='all'){
    $topic = "home/all";
}
// Kết nối MQTT và gửi tin nhắn
if ($mqtt->connect(true, NULL, $username, $password)) {
    // Gửi tin nhắn qua MQTT
    $mqtt->publish($topic, $message, 0);
    
    // Phản hồi thành công
    $response = [
        'success' => true,
        'message' => ucfirst($device) . ' turned ' . $message
    ];
    
    // Đóng kết nối
    $mqtt->close();
} else {
    // Phản hồi lỗi khi không thể kết nối MQTT
    http_response_code(500);
    $response = [
        'success' => false,
        'message' => 'Failed to connect to MQTT server.'
    ];
}

// Trả về phản hồi JSON
echo json_encode($response);
?>
