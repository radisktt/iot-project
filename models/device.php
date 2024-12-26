<?php

class Devices{
    private $conn;
    private $table = 'device_control';

    public $dvname;
    public $state;
    public $timestamp;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function save() {
        $this->timestamp = $this->timestamp ?? date('Y-m-d H:i:s');

        $query = "INSERT INTO " . $this->table . " (device_name, state, timestamp) VALUES (?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bind_param("sss", $this->dvname, $this->state, $this->timestamp);

        if ($stmt->execute()) {
            return true;
        } else {
            echo "Error: " . $stmt->error;
            return false;
        }
    }
}