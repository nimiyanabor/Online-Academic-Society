<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: /login.php");
    exit();
}

include 'db_connection.php';
include 'function.php';

$currentUserId = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = getConnection();

    $username = isset($_POST['username']) ? trim($_POST['username']) : null;
    $bio = isset($_POST['bio']) ? trim($_POST['bio']) : null;
    $profileImage = isset($_FILES['profile_image']) ? $_FILES['profile_image'] : null;

    // Update the user table
    $updates = [];
    $params = [];

    if ($username) {
        $updates[] = "username = ?";
        $params[] = $username;
    }

    if ($bio) {
        $updates[] = "bio = ?";
        $params[] = $bio;
    }

    if (!empty($updates)) {
        $query = "UPDATE user SET " . implode(", ", $updates) . " WHERE id = ?";
        $params[] = $currentUserId;
        $stmt = $conn->prepare($query);
        $stmt->bind_param(str_repeat("s", count($params) - 1) . "i", ...$params);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Profile updated successfully.";
        } else {
            $_SESSION['error'] = "Failed to update profile.";
        }

        $stmt->close();
    }

    // Update the profile image in the profile table
    if ($profileImage && $profileImage['error'] == 0) {
        $imageFileName = uniqid() . '_' . basename($profileImage['name']);
        $targetPath = 'profile/' . $imageFileName;
        if (move_uploaded_file($profileImage['tmp_name'], $targetPath)) {
            $query = "UPDATE profile SET profile_image = ? WHERE user_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $imageFileName, $currentUserId);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Profile image updated successfully.";
            } else {
                $_SESSION['error'] = "Failed to update profile image.";
            }

            $stmt->close();
        } else {
            $_SESSION['error'] = "Failed to upload profile image.";
        }
    }

    $conn->close();
    header("Location: myprofile.php");
    exit();
}
?>
