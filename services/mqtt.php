<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Tải thư viện phpMQTT
require_once __DIR__ . '/../config/database.php'; // Kết nối tới cơ sở dữ liệu
require_once __DIR__ . '/../models/data.php';     // Model cho bảng sensor_data
require_once __DIR__ . '/../models/device.php';   // Model cho bảng device_control

use Bluerhinos\phpMQTT;

// Cấu hình múi giờ GMT+7
date_default_timezone_set('Asia/Bangkok');

// Cấu hình MQTT
$server = 'localhost';             
$port = 1337;                      
$username = 'levantuyen1';         
$password = 'b21dcat218';          
$client_id = 'phpMQTT_' . uniqid();

$mqtt = new phpMQTT($server, $port, $client_id);

// Kiểm tra kết nối đến MQTT broker
if ($mqtt->connect(true, NULL, $username, $password)) {
    echo "Kết nối thành công đến máy chủ MQTT\n";

    // Đăng ký các topic
    $topics['test'] = [
        'qos' => 0,
        'function' => function($topic, $msg) use ($conn) {
            //echo "Tin nhắn nhận được từ $topic: " . $msg . "\n";

            // Giải mã JSON từ tin nhắn nhận được
            $parsedMessage = json_decode($msg, true);

            // Kiểm tra nếu dữ liệu hợp lệ
            if (is_array($parsedMessage)) {
                // Tạo một đối tượng Data và gán các giá trị
                $data = new Data($conn);
                $data->temperature = $parsedMessage['temperature'] ?? null;
                $data->humidity = $parsedMessage['humidity'] ?? null;
                $data->brightness = $parsedMessage['brightness'] ?? null;
                $data->newsensor = $parsedMessage['newsensor'] ?? null;
                $data->timestamp = date('Y-m-d H:i:s'); // Sử dụng múi giờ GMT+7

                // Lưu dữ liệu vào MySQL
                if ($data->save()) {
                    echo "Sensor data saved to MySQL\n";
                } else {
                    echo "Failed to save sensor data\n";
                }
            } else {
                echo "Received invalid message format\n";
            }
        }
    ];

    // Đăng ký các topic điều khiển thiết bị
    $topics['home/fan/control'] = [
        'qos' => 0,
        'function' => function($topic, $msg) use ($conn) {
            handleDeviceControl($conn, 'fan', $msg);
        }
    ];

    $topics['home/ac/control'] = [
        'qos' => 0,
        'function' => function($topic, $msg) use ($conn) {
            handleDeviceControl($conn, 'ac', $msg);
        }
    ];

    $topics['home/light/control'] = [
        'qos' => 0,
        'function' => function($topic, $msg) use ($conn) {
            handleDeviceControl($conn, 'light', $msg);
        }
    ];

    $topics['home/led1/control'] = [
        'qos' => 0,
        'function' => function($topic, $msg) use ($conn) {
            handleDeviceControl($conn, 'led1', $msg);
        }
    ];
    $topics['home/led2/control'] = [
        'qos' => 0,
        'function' => function($topic, $msg) use ($conn) {
            handleDeviceControl($conn, 'led2', $msg);
        }
    ];
    $topics['home/all'] = [
        'qos' => 0,
        'function' => function($topic, $msg) use ($conn) {
            echo "Tin nhắn nhận được từ $topic: " . $msg . "\n";
            // Lưu trạng thái điều khiển tổng hợp
            handleDeviceControl($conn, 'fan', $msg);
            handleDeviceControl($conn, 'ac', $msg);
            handleDeviceControl($conn, 'light', $msg);
        }
    ];

    // Đăng ký các chủ đề
    $mqtt->subscribe($topics, 0);

    // Vòng lặp để xử lý các tin nhắn từ broker
    while ($mqtt->proc()) {
        // Xử lý các tin nhắn được nhận
    }

    // Ngắt kết nối khi hoàn thành
    $mqtt->close();
    echo "Disconnected from MQTT broker.\n";

} else {
    echo "Không thể kết nối đến máy chủ MQTT\n";
}

// Hàm xử lý tin nhắn điều khiển và lưu vào bảng device_control
function handleDeviceControl($conn, $deviceName, $state) {
    $device = new Devices($conn);
    $device->dvname = $deviceName;
    $device->state = (strcasecmp($state, 'ON') === 0) ? 1 : 0;  // Gán 1 nếu $state là 'ON', ngược lại gán 0
    $device->timestamp = date('Y-m-d H:i:s'); // Sử dụng múi giờ GMT+7

    if ($device->save()) {
        echo "Device state for $deviceName saved to MySQL\n";
    } else {
        echo "Failed to save device state for $deviceName\n";
    }
}
