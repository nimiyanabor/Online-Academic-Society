<?php
include 'db_connection.php';

$postId = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;

if ($postId > 0) {
    $stmt = $mysqli->prepare("
        SELECT pc.comment, u.fullname 
        FROM post_comment pc
        JOIN user u ON pc.user_id = u.id
        WHERE pc.post = ?
    ");
    $stmt->bind_param('i', $postId);
    $stmt->execute();
    $result = $stmt->get_result();

    $comments = [];
    while ($row = $result->fetch_assoc()) {
        $comments[] = [
            'comment' => $row['comment'],
            'fullname' => $row['fullname']
        ];
    }

    $stmt->close();
    $mysqli->close();

    echo json_encode(['comments' => $comments]);
} else {
    echo json_encode(['comments' => []]);
}
?>
