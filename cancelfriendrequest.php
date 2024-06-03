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
        // Here you would add your logic to cancel the friend request in the database
        // For example:
        $stmt = $mysqli->prepare('DELETE FROM friends WHERE user1_id = ? AND user2_id = ? AND status = "pending"');
        $stmt->bind_param('ii', $userId, $friendId);
        if ($stmt->execute()) {
            $response = ['success' => true];
            $message = "User $user2_id declined your friend request.";
            $stmt = $db->prepare("INSERT INTO notifications (user_id, type, message) VALUES (?, 'friend_decline', ?)");
            $stmt->bind_param("is", $user1_id, $message);
            $stmt->execute();
        } else {
            $response = ['success' => false, 'error' => 'Database error: ' . $stmt->error];
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
