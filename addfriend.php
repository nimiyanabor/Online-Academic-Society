<?php
session_start();
include 'db_connection.php';

header('Content-Type: application/json'); // Ensure the response is JSON

$response = [];

// Log the request method
$requestMethod = $_SERVER['REQUEST_METHOD'];
error_log("Request Method: " . $requestMethod);

if ($requestMethod === 'POST') {
    // Log the raw POST data
    $input = file_get_contents('php://input');
    error_log("Raw POST Data: " . $input);

    // Decode the JSON data
    $data = json_decode($input, true);

    // Log the decoded JSON data
    error_log("Decoded JSON Data: " . print_r($data, true));

    $friendId = isset($data['friend_id']) ? intval($data['friend_id']) : null;
    $userId = isset($_SESSION['id']) ? intval($_SESSION['id']) : null;

    // Log the extracted IDs
    error_log("Friend ID: " . $friendId);
    error_log("User ID: " . $userId);

    if ($friendId && $userId) {
        // Check if a record already exists between these users
        $stmt = $mysqli->prepare('SELECT * FROM friends WHERE (user1_id = ? AND user2_id = ?) OR (user1_id = ? AND user2_id = ?)');
        $stmt->bind_param('iiii', $userId, $friendId, $friendId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            // Insert the new friend request
            $stmt = $mysqli->prepare('INSERT INTO friends (user1_id, user2_id, status, friend_time) VALUES (?, ?, ?, CURDATE())');
            $status = 'pending';
            $stmt->bind_param('iis', $userId, $friendId, $status);

            if ($stmt->execute()) {
                $response = ['success' => true];
            } else {
                $response = ['success' => false, 'error' => 'Database error: ' . $stmt->error];
            }
        } else {
            $response = ['success' => false, 'error' => 'Friend request already exists.'];
        }
    } else {
        $response = ['success' => false, 'error' => 'Invalid request.'];
    }
} else {
    $response = ['success' => false, 'error' => 'Invalid request method.'];
}

echo json_encode($response);

$mysqli->close();
?>
