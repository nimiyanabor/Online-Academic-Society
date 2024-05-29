<?php
include 'db_connection.php';

session_start();
if (!isset($_SESSION['id'])) {
    header("Location: /login.php");
    exit();
}
ini_set('log_errors', 1);
ini_set('error_log', '/path/to/php-error.log');

$currentUserId = $_SESSION['id'];
function getFriends($userId) {
    $host = 'localhost'; // Your database host (usually 'localhost')
    $db   = 'unichat';   // Your database name
    $user = 'root';      // Your database username
    $pass = '';          // Your database password
    
    // Create connection
    $conn = new mysqli($host, $user, $pass, $db);
    $query = " SELECT u.fullname
    FROM friends f
    JOIN user u ON (f.user1_id = u.id OR f.user2_id = u.id)
    WHERE (f.user1_id = ? OR f.user2_id = ?) AND f.status = 'accepted' AND u.id != ?
";

    $stmt = $conn->prepare($query);
    $stmt->bind_param('iii', $userId, $userId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $friends = [];
    while ($row = $result->fetch_assoc()) {
        $friends[] = $row;
    }

    $stmt->close();
    $conn->close();
    return $friends;
}

header('Content-Type: application/json');
echo json_encode(getFriends($currentUserId));
?>
