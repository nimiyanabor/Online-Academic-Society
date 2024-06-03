<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

$userId = $_SESSION['id'];

// Retrieve notifications for the logged-in user
$stmt = $mysqli->prepare("SELECT id, type, message, user_id, is_read, created_at FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();

$notifications = [];
while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
}

$stmt->close();
$mysqli->close();

echo json_encode(['success' => true, 'notifications' => $notifications]);
?>
