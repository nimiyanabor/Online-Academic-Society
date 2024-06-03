<?php
header('Content-Type: application/json');

if (!isset($_GET['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit();
}

$userId = intval($_GET['user_id']);

$mysqli = new mysqli("localhost", "root", "", "unichat");

if ($mysqli->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit();
}

$stmt = $mysqli->prepare("SELECT fullname FROM user WHERE id = ?");
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $mysqli->error]);
    exit();
}

$stmt->bind_param('i', $userId);
$stmt->execute();
$stmt->bind_result($username);
$stmt->fetch();

if ($username) {
    echo json_encode(['success' => true, 'username' => $username]);
} else {
    echo json_encode(['success' => false, 'message' => 'User not found']);
}

$stmt->close();
$mysqli->close();
?>
