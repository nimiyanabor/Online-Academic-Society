<?php
include 'db_connection.php';

$postId = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;

if ($postId > 0) {
    $query = "
        SELECT u.fullname
        FROM post_like pl
        JOIN user u ON pl.liked_by = u.id
        WHERE pl.post = ?
    ";

    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $postId);
    $stmt->execute();
    $result = $stmt->get_result();

    $likes = [];
    while ($row = $result->fetch_assoc()) {
        $likes[] = $row['fullname'];
    }

    $stmt->close();
    $mysqli->close();

    echo json_encode(['likes' => $likes]);
} else {
    echo json_encode(['error' => 'Invalid post ID']);
}
