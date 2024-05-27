<?php
session_start();

function getConnection() {
    $servername = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbname = "unichat";

    $conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

if (isset($_GET['query'])) {
    $query = $_GET['query'];

    $conn = getConnection();

    // Search for users by ID or fullname
    $sql = "
        SELECT id, username, fullname 
        FROM user 
        WHERE id LIKE ? OR fullname LIKE ?";
    $stmt = $conn->prepare($sql);
    $likeQuery = '%' . $query . '%';
    $stmt->bind_param("ss", $likeQuery, $likeQuery);
    $stmt->execute();
    $result = $stmt->get_result();

    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

    $stmt->close();
    $conn->close();

    // Log the retrieved users
    error_log("Retrieved users: " . print_r($users, true));

    echo json_encode($users);
}
?>
