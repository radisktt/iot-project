<?php

// Mảng routes trang, ánh xạ đường dẫn tới file tương ứng trong thư mục public
$routes = [
    "" => __DIR__ . '/../public/dashboard.php',             // Trang chính (Dashboard)
    "/dashboard.php" => __DIR__ . '/../public/dashboard.php',    // Trang Dashboard
    "/datasensor.php" => __DIR__ . '/../public/datasensor.php',  // Trang Data Sensor
    "/actionhistory.php" => __DIR__ . '/../public/actionhistory.php',  // Trang Action History
    "/profile.php" => __DIR__ . '/../public/profile.php',         // Trang Profile
    "/newdashboard.php" => __DIR__ . '/../public/newdashboard.php'
];

// Lấy URI hiện tại và loại bỏ dấu '/' ở cuối nếu có, sau đó loại bỏ '/iot' nếu có
 $request_uri = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
 $request_uri = str_replace('/iot', '', $request_uri);
//echo $request_uri;

if (array_key_exists($request_uri, $routes)) {
    $currentPage = basename($request_uri);
    require_once $routes[$request_uri];
} else {
    http_response_code(404);
    echo "404 Not Found";
}
