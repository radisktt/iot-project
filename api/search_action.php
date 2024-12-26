<?php
include_once('../config/database.php');

header('Content-Type: application/json');

// Get parameters from the query string
$startTime = isset($_GET['start_time']) ? $_GET['start_time'] : '1970-01-01 00:00:00'; // default to earliest timestamp
$endTime = isset($_GET['end_time']) ? $_GET['end_time'] : '2030-01-01 00:00:00'; // default to now
$deviceType = isset($_GET['device']) ? $_GET['device'] : null;
$state = isset($_GET['state']) ? (int)$_GET['state'] : null;
$order = isset($_GET['order']) ? $_GET['order']: 'DESC';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$pageSize = isset($_GET['pageSize']) ? (int)$_GET['pageSize'] : 10;
$offset = ($page - 1) * $pageSize;

$response = [];

// Check if the database connection was successful
if ($conn->connect_error) {
    $response = [
        'success' => false,
        'message' => 'Database connection failed: ' . $conn->connect_error
    ];
    echo json_encode($response);
    exit;
}

// Build the base query with filtering conditions
$query = "SELECT * FROM device_control WHERE timestamp BETWEEN ? AND ?";
$params = [$startTime, $endTime];
$types = "ss";

// Add device type filter if provided
if ($deviceType) {
    $query .= " AND device_name = ?";
    $params[] = $deviceType;
    $types .= "s";
}

// Add state filter if provided
if ($state !== null) {
    $query .= " AND state = ?";
    $params[] = $state;
    $types .= "i";
}

// Add ORDER BY and LIMIT/OFFSET clauses for pagination
$query .= " ORDER BY timestamp $order LIMIT ?, ?";
$params[] = $offset;
$params[] = $pageSize;
$types .= "ii";

// Prepare the query
$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);

// Execute the query
if ($stmt->execute()) {
    $result = $stmt->get_result();
    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    // Get total count of matching records for pagination
    $countQuery = "SELECT COUNT(*) as total FROM device_control WHERE timestamp BETWEEN ? AND ?";
    $countParams = [$startTime, $endTime];
    $countTypes = "ss";

    if ($deviceType) {
        $countQuery .= " AND device_name = ?";
        $countParams[] = $deviceType;
        $countTypes .= "s";
    }

    if ($state !== null) {
        $countQuery .= " AND state = ?";
        $countParams[] = $state;
        $countTypes .= "i";
    }

    $countStmt = $conn->prepare($countQuery);
    $countStmt->bind_param($countTypes, ...$countParams);
    $countStmt->execute();
    $countResult = $countStmt->get_result();
    $totalRows = $countResult->fetch_assoc()['total'];
    $totalPages = ceil($totalRows / $pageSize);

    $response = [
        'success' => true,
        'data' => $data,
        'page' => $page,
        'pageSize' => $pageSize,
        'totalPages' => $totalPages,
        'totalRows' => $totalRows
    ];
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
$countStmt->close();
$conn->close();
?>
