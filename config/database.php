<?php
$host = 'localhost'; 
$username = 'root';  
$password = '';      
$dbname = 'iot2';  // Tên cơ sở dữ liệu 

// Kết nối tới MySQL
$conn = new mysqli($host, $username, $password, $dbname);


if (!$conn) {
    die("Kết nối tới MySQL thất bại: " . mysqli_connect_error());
}

?>