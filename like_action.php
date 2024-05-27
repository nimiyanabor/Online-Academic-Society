<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$userId = $_SESSION['id'];
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['postId']) || !isset($data['action'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit();
}

$postId = intval($data['postId']);
$action = $data['action'];

if ($action === 'like') {
    $query = "INSERT INTO post_like (post, liked_by, created_at) VALUES (?, ?, NOW())";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('ii', $postId, $userId);
} else {
    $query = "DELETE FROM post_like WHERE post = ? AND liked_by = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('ii', $postId, $userId);
}

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}

$stmt->close();
$mysqli->close();
?>
