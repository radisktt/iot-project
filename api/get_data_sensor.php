<?php
include_once('../config/database.php');

header('Content-Type: application/json');

// Get parameters from the query string
$startTime = $_GET['start_time'] ?? '1970-01-01 00:00:00'; // Default to earliest timestamp
$endTime = $_GET['end_time'] ?? "2030-01-01 00:00:00";       // Default to now
$orderBy = $_GET['orderBy'] ?? 'timestamp';               // Default sorting column
$order = (isset($_GET['order']) && strtoupper($_GET['order']) === 'ASC') ? 'ASC' : 'DESC'; // Default to DESC
$page = max(1, (int)($_GET['page'] ?? 1));
$pageSize = max(1, (int)($_GET['pageSize'] ?? 10));
$type = $_GET['type'] ?? null;                            // Column to filter
$typeValue = $_GET['value'] ?? null;                      // Value for specific column
$offset = ($page - 1) * $pageSize;

$response = [];

// Check database connection
if ($conn->connect_error) {
    $response = [
        'success' => false,
        'message' => 'Database connection failed: ' . $conn->connect_error
    ];
    echo json_encode($response);
    exit;
}

// Validate the orderBy parameter against allowed columns
$allowedOrderBy = ['temperature', 'humidity', 'brightness', 'newsensor', 'timestamp'];
if (!in_array($orderBy, $allowedOrderBy, true)) {
    $response = [
        'success' => false,
        'message' => 'Invalid orderBy value. Allowed values are: ' . implode(', ', $allowedOrderBy)
    ];
    echo json_encode($response);
    exit;
}

// Validate the type parameter if provided
$allowedTypes = ['temperature', 'humidity', 'brightness', 'newsensor'];
if ($type && !in_array($type, $allowedTypes, true)) {
    $response = [
        'success' => false,
        'message' => 'Invalid type value. Allowed values are: ' . implode(', ', $allowedTypes)
    ];
    echo json_encode($response);
    exit;
}

// Build the base query with filtering conditions
$query = "SELECT * FROM sensor_data WHERE timestamp BETWEEN ? AND ?";
$params = [$startTime, $endTime];
$types = "ss";

// Add specific value search if type and typeValue are provided
if ($type && $typeValue !== null) {
    $query .= " AND $type = ?";
    $params[] = $typeValue;
    $types .= is_numeric($typeValue) ? "i" : "s";
}

// Add ORDER BY, LIMIT, and OFFSET clauses for pagination and sorting
$query .= " ORDER BY $orderBy $order LIMIT ?, ?";
$params[] = $offset;
$params[] = $pageSize;
$types .= "ii";

// Prepare the query
$stmt = $conn->prepare($query);
if ($stmt === false) {
    $response = [
        'success' => false,
        'message' => 'Error preparing query: ' . $conn->error
    ];
    echo json_encode($response);
    exit;
}

$stmt->bind_param($types, ...$params);

// Execute the query
if ($stmt->execute()) {
    $result = $stmt->get_result();
    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    // Get total count of matching records for pagination
    $countQuery = "SELECT COUNT(*) as total FROM sensor_data WHERE timestamp BETWEEN ? AND ?";
    $countParams = [$startTime, $endTime];
    $countTypes = "ss";

    if ($type && $typeValue !== null) {
        $countQuery .= " AND $type = ?";
        $countParams[] = $typeValue;
        $countTypes .= is_numeric($typeValue) ? "i" : "s";
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
if (isset($countStmt)) {
    $countStmt->close();
}
$conn->close();
?>
