<?php

class Data{
    private $conn;
    private $table = 'sensor_data';

    public $temperature;
    public $humidity;
    public $brightness;
    public $timestamp;
    public $newsensor;
    public function __construct($db) {
        $this->conn = $db;
    }

    public function save(){
        $query = "INSERT INTO " . $this->table . " (temperature, humidity, brightness, newsensor, timestamp) VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);
        $this->timestamp = $this->timestamp ?? date('Y-m-d H:i:s');
        $stmt->bind_param("sssss", $this->temperature, $this->humidity, $this->brightness,$this->newsensor, $this->timestamp);
        if ($stmt->execute()) {
            return true;
        } else {
            echo "Error: " . $stmt->error;
            return false;
        }
    }
}
?>