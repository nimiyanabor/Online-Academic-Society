<?php
// functions.php
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

function getUserProfileImage($user_id) {
    $conn = getConnection();
    $sql = "SELECT profile_image FROM profile WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $profile_image = "default_profile.png"; // Default profile image

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $profile_image = $row['profile_image'];
    }

    $stmt->close();
    $conn->close();
    return $profile_image;
}
function getUserProfile($user_id) {
    $conn = getConnection();
    $sql = "
        SELECT 
            u.username, u.fullname, u.email, u.department, u.level, pr.profile_image, u.bio
        FROM 
            user u
        LEFT JOIN 
            profile pr ON u.id = pr.user_id
        WHERE 
            u.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $profile = null;
    if ($result->num_rows == 1) {
        $profile = $result->fetch_assoc();
    }

    $stmt->close();
    $conn->close();
    return $profile;
}
function addPost($user_id, $caption, $images) {
    $conn = getConnection();
    $success = true;

    // Loop through each uploaded file
    foreach ($images['name'] as $index => $name) {
        // Generate a unique file name and move the file to the "post" folder
        $imageFileName = uniqid() . '_' . basename($name);
        $targetPath = 'post/' . $imageFileName;
        
        if (move_uploaded_file($images['tmp_name'][$index], $targetPath)) {
            // Add the post details to the database
            $sql = "INSERT INTO post (user_id, post, caption) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iss", $user_id, $targetPath, $caption);
            if (!$stmt->execute()) {
                $success = false;
            }
            $stmt->close();
        } else {
            $success = false;
        }
    }

    $conn->close();
    return $success;
}

function addTextPost($user_id, $text) {
    $conn = getConnection();
    $success = true;
            // Add the post details to the database
            $sql = "INSERT INTO post_text (user_id, content) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $user_id, $text);
            if (!$stmt->execute()) {
                $success = false;
            }
            $stmt->close();

    $conn->close();
    return $success;
}


function getUserPosts($userId, $currentUserId) {
    include 'db_connection.php';

    $query = "
        SELECT 
            p.id, p.user_id, p.post, p.caption, p.created_at, 
            (SELECT COUNT(*) FROM post_like WHERE post_like.post = p.id) AS like_count,
            (SELECT COUNT(*) FROM post_comment WHERE post_comment.post = p.id) AS comment_count,
            (SELECT 1 FROM post_like WHERE post_like.post = p.id AND post_like.liked_by = ?) AS liked_by_user,
            (SELECT COUNT(*) FROM saved_post WHERE saved_post.post = p.id AND saved_post.user_id = ?) AS saved_by_user,
            pr.profile_image, u.fullname
        FROM 
            post p
        JOIN 
            user u ON p.user_id = u.id
        LEFT JOIN 
            profile pr ON u.id = pr.user_id
        WHERE 
            p.user_id = ?
        ORDER BY 
            p.created_at DESC";
    
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        echo 'Error: ' . $mysqli->error;
        return;
    }

    $stmt->bind_param('iii', $currentUserId, $currentUserId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $posts = [];
    while ($row = $result->fetch_assoc()) {
        $key = $row['caption'] . '|' . $row['created_at'] . '|' . $row['user_id'];
        if (!isset($posts[$key])) {
            $posts[$key] = [
                'profile_image' => $row['profile_image'],
                'fullname' => $row['fullname'],
                'caption' => $row['caption'],
                'created_at' => $row['created_at'],
                'like_count' => $row['like_count'],
                'comment_count' => $row['comment_count'],
                'liked_by_user' => $row['liked_by_user'],
                'saved_by_user' => $row['saved_by_user'],
                'id' => $row['id'], // Add this line
                'media' => []
            ];
        }
        $posts[$key]['media'][] = $row['post'];
    }

    $stmt->close();
    $mysqli->close();

    return $posts;
}


function getfriends($userId) {
    $conn = getConnection();


    $query = "
        SELECT u.fullname, u.username
        FROM friends pl
        JOIN user u ON pl.user1_id OR pl.user2_id = u.id
        WHERE pl.user1_id OR pl.user2_id = ?
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $friend = [];
    while ($row = $result->fetch_assoc()) {
        $friend[] = $row['fullname'];
    }


    $stmt->close();
    $conn->close();
    return $friend;

}

function getFriendsIds($userId) {
    $conn = getConnection();
    $query = "
        SELECT 
            CASE 
                WHEN f.user1_id = ? THEN f.user2_id 
                ELSE f.user1_id 
            END AS friend_id
        FROM friends f
        WHERE 
            (f.user1_id = ? OR f.user2_id = ?) AND f.status = 'accepted'
    ";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo 'Error: ' . $conn->error;
        return [];
    }

    $stmt->bind_param('iii', $userId, $userId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $friendIds = [];
    while ($row = $result->fetch_assoc()) {
        $friendIds[] = $row['friend_id'];
    }

    $stmt->close();
    $conn->close();

    return $friendIds;
}


function getUserAndFriendsPosts($currentUserId) {
    $conn = getConnection();
    $friendIds = getFriendsIds($currentUserId);

    // Add the current user's ID to the list of IDs
    $userIds = array_merge([$currentUserId], $friendIds);

    // Create a string with the appropriate number of placeholders
    $placeholders = implode(',', array_fill(0, count($userIds), '?'));

    $query = "
        SELECT 
            p.id, p.user_id, p.post, p.caption, p.created_at, 
            (SELECT COUNT(*) FROM post_like WHERE post_like.post = p.id) AS like_count,
            (SELECT COUNT(*) FROM post_comment WHERE post_comment.post = p.id) AS comment_count,
            (SELECT 1 FROM post_like WHERE post_like.post = p.id AND post_like.liked_by = ?) AS liked_by_user,
            (SELECT COUNT(*) FROM saved_post WHERE saved_post.post = p.id AND saved_post.user_id = ?) AS saved_by_user,
            pr.profile_image, u.fullname
        FROM 
            post p
        JOIN 
            user u ON p.user_id = u.id
        LEFT JOIN 
            profile pr ON u.id = pr.user_id
        WHERE 
            p.user_id IN ($placeholders)
        ORDER BY 
            p.created_at DESC
    ";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo 'Error: ' . $conn->error;
        return [];
    }

    // Prepare the parameters for bind_param
    $params = array_merge([$currentUserId, $currentUserId], $userIds);
    $paramTypes = str_repeat('i', count($params));
    $stmt->bind_param($paramTypes, ...$params);

    $stmt->execute();
    $result = $stmt->get_result();

    $posts = [];
    while ($row = $result->fetch_assoc()) {
        $key = $row['caption'] . '|' . $row['created_at'] . '|' . $row['user_id'];
        if (!isset($posts[$key])) {
            $posts[$key] = [
                'profile_image' => $row['profile_image'],
                'fullname' => $row['fullname'],
                'caption' => $row['caption'],
                'created_at' => $row['created_at'],
                'like_count' => $row['like_count'],
                'comment_count' => $row['comment_count'],
                'liked_by_user' => $row['liked_by_user'],
                'saved_by_user' => $row['saved_by_user'],
                'id' => $row['id'],
                'media' => []
            ];
        }
        $posts[$key]['media'][] = $row['post'];
    }

    $stmt->close();
    $conn->close();

    return $posts;
}


?>

