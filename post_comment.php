<?php
session_start();

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

include 'db_connection.php';

$currentUserId = $_SESSION['id'];
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['postId']) && isset($data['comment'])) {
    $postId = intval($data['postId']);
    $comment = trim($data['comment']);

    if (!empty($comment)) {
        $stmt = $mysqli->prepare("INSERT INTO post_comment (post, comment, user_id) VALUES (?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param('isi', $postId, $comment, $currentUserId);
            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Database error']);
            }
            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Statement preparation error']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Empty comment']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
}

$mysqli->close();
?>
