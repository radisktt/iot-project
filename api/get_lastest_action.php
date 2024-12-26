<?php
require_once __DIR__ . '/../config/database.php'; // Database connection

header('Content-Type: application/json');

// Get device and state from the query string
$device = isset($_GET['device']) ? $_GET['device'] : null;
$state = isset($_GET['state']) ? $_GET['state'] : null; // Optional

$response = [];

// Validate device parameter
$allowedDevices = ['fan', 'ac', 'light', 'led1', 'led2'];
if (!$device || !in_array($device, $allowedDevices)) {
    $response = [
        'success' => false,
        'message' => 'Invalid device. Allowed values are: fan, ac, light, led1, led2'
    ];
    echo json_encode($response);
    exit;
}

// Build the base query
$query = "SELECT * FROM device_control WHERE device_name = ?";

// If state is provided, add it to the query
$params = [$device];
$types = "s";
if ($state !== null) {
    $query .= " AND state = ?";
    $params[] = $state;
    $types .= "i";
}

// Order by timestamp in descending order to get the latest action and limit the result to 1
$query .= " ORDER BY timestamp DESC LIMIT 1";

// Prepare the statement
$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);

// Execute the query
if ($stmt->execute()) {
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // Fetch the latest action
        $response = [
            'success' => true,
            'data' => $result->fetch_assoc()
        ];
    } else {
        $response = [
            'success' => true,
            'message' => 'No action found for the specified criteria'
        ];
    }
} else {
    $response = [
        'success' => false,
        'message' => 'Error executing query: ' . $stmt->error
    ];
}

// Output the JSON response
echo json_encode($response);

// Close the statement and connection
$stmt->close();
$conn->close();
?>
