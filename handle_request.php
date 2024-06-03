<?php

session_start();
include 'db_connection.php';

// Suppress errors and warnings to avoid breaking JSON response
ini_set('display_errors', 0);
error_reporting(0);

// Set content type to JSON
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['requestId']) && isset($data['action'])) {
    $requestId = $data['requestId'];
    $action = $data['action'];
    $userId = isset($_SESSION['id']) ? intval($_SESSION['id']) : null;

    if (!$userId) {
        echo json_encode(['success' => false, 'message' => 'User not logged in']);
        exit;
    }

    if ($action === 'accept') {
        $query = "UPDATE friends SET status = 'accepted' WHERE id = ?";
    } elseif ($action === 'delete') {
        $query = "DELETE FROM friends WHERE id = ?";
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        exit;
    }

    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => $mysqli->error]);
        exit;
    }

    $stmt->bind_param('i', $requestId);
    $success = $stmt->execute();

    if ($success && $action === 'accept') {
        // Get the requester user ID
        $stmt = $mysqli->prepare("SELECT user1_id FROM friends WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param('i', $requestId);
            $stmt->execute();
            $stmt->bind_result($user1_id);
            $stmt->fetch();
            $stmt->close();

            // Create a notification for the friend request acceptance
            $message = "User $userId accepted your friend request.";
            $stmt = $mysqli->prepare("INSERT INTO notifications (user_id, type, message) VALUES (?, 'friend_accept', ?)");
            if ($stmt) {
                $stmt->bind_param('is', $user1_id, $message);
                $stmt->execute();
                $stmt->close();
            }
        }
    }
    

    $stmt->close();
    $mysqli->close();

    echo json_encode(['success' => $success]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
}
?>
