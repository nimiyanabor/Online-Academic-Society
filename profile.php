<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: /login.php");
    exit();
}

include 'db_connection.php';
include 'function.php'; // Include functions.php

// Get the current user ID from the session
$currentUserId = $_SESSION['id'];

// Get the profile user ID from the URL
$profileUserId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Query to fetch friend relationship status
$query = "SELECT status, DATEDIFF(CURDATE(), friend_time) AS days FROM friends 
          WHERE (user1_id = ? AND user2_id = ?) OR (user1_id = ? AND user2_id = ?)";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("iiii", $currentUserId, $profileUserId, $profileUserId, $currentUserId);
$stmt->execute();
$result = $stmt->get_result();

$friendStatus = null;
$days = 0;

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $friendStatus = $row['status'];
    $days = $row['days']; // Add this line to get the number of days
}
$stmt->close();

$userId =($_GET['id']);
// Fetch user posts

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <link rel="stylesheet" href="profile.css">
    <script src="profile.js"></script>
</head>
<body>

    <div class="container">
        <div class="profile-top">
            <div class="left-top">
                <div class="profile-pic">
                    <img id="profilePic" src="" alt="">
                </div>
                <p>@yanabor</p>
            </div>
            <div class="right-top">
                <div class="name">
                    <label for="">Name:</label>
                    <p id="fullName"></p>
                </div>
                <div class="department">
                    <label for="">Department:</label>
                    <p id="department">Information Technology</p>
                </div>
                <div class="level">
                    <label for="">Level:</label>
                    <p id="level">300</p>
                </div>
                <div class="email">
                    <label for="email">Email:</label>
                    <p id="email">yanabor@gmail.com</p>
                </div>
            </div>
        </div>
        <div class="bottom-container">
            <div class="bio-container">
                <div class="bio-text-container">
                    <div class="bio-icon">
                        <i class="uil uil-paragraph"></i>
                        <p>Bio</p>
                    </div>
                    <div class="bio-text">
                        <p id="bio"></p>
                    </div>
                </div>
                <?php if ($friendStatus === 'accepted'): ?>
                    <div class="friends-container">
                        <div class="friends-icon">
                            <i class="uil uil-users-alt"></i>
                            <p>Friends</p>
                        </div>
                        <p>Being friends for <?php echo $days; ?> days... Let's keep it going</p>
                    </div>
                <?php elseif ($friendStatus === 'pending'): ?>
                    <div class="add-friend-pending">
                        <div class="friends-icon">
                            <i class="uil uil-users-alt"></i>
                            <button id="cancel_friend_request">Cancel Request</button>
                        </div>
                        <p>Wait for friendship acceptance</p>
                    </div>
                <?php else: ?>
                    <div class="add-friend-container">
                        <div class="friends-icon">
                            <i class="uil uil-user-plus"></i>
                            <button id="add_friend">Add Friend</button>
                        </div>
                        <p>Let's be friends...Don't you think?</p>
                    </div>
                <?php endif; ?>
                <div class="message-container">
                    <i class="uil uil-envelope"></i>
                    <p>message</p>
                </div>
            </div>
            <div class="activity-container">
                <div class="other-activity">
                    <div class="highlight">
                        <div class="highlight-container">
                            <i class="uil uil-window"></i>
                            <p>Highlight</p>
                        </div>
                        <p>View my special events and activities.....</p>
                    </div>
                    <div class="podcast">
                        <div class="podcast-container">
                            <i class="uil uil-microphone"></i>
                            <p>Podcast</p>
                        </div>
                        <p>No channels yet........</p>
                    </div>
                    <div class="shop">
                        <div class="shop-container">
                            <i class="uil uil-shopping-cart-alt"></i>
                            <p>Shop</p>
                        </div>
                        <p>No outlet yet....</p>
                    </div>
                </div>
                <div class="middle">
                    <?php 
$posts = getUserPosts($profileUserId, $currentUserId);

 foreach ($posts as $post): ?>
    <div class="postfeed-container">
        <div class="feed-header">
            <a href="#">
                <div class="feed-profile">
                    <img src="profile/<?php echo htmlspecialchars($post['profile_image']); ?>" alt="Profile Picture">
                </div>
                <h3><?php echo htmlspecialchars($post['fullname']); ?></h3>
            </a>
            <div class="feed-options">
                <a href="#"><i class="uil uil-ellipsis-h"></i></a>
            </div>
        </div>
        <div class="feed-caption">
            <p><?php echo htmlspecialchars($post['caption']); ?></p>
        </div>
        <div class="gallery-container">
            <?php 
            foreach ($post['media'] as $media) {
                if (preg_match('/\.(mp4)$/i', $media)) {
                    echo '<div class="gallery-img"><video class="gallery-img-video" autoplay muted src="' . htmlspecialchars($media) . '" alt="Video"></video></div>';
                } else {
                    echo '<div class="gallery-img"><img class="gallery-img-img" src="' . htmlspecialchars($media) . '" alt="Image"></div>';
                }
            }
            ?>
            <div class=" gallery-imgs" id="total-images"></div> <!-- Change span to div -->
        </div>
        <div class="feed-interaction">
            <div class="left-feed-interaction">
            <?php if ($post['liked_by_user'] == 1): ?>
                    <i class="uil uil-heart liked-post" data-post-id="<?php echo $post['id']; ?>"></i>
            <?php else: ?>
                    <i class="uil uil-heart not-liked-post" data-post-id="<?php echo $post['id']; ?>"></i>
                <?php endif; ?>
                <i class="uil uil-comment-dots" id="comment-button"></i>
                <i class="uil uil-share-alt"></i>
            </div>
            <div class="right-feed-interaction">
                    <?php if ($post['saved_by_user'] == 1): ?>
                        <i class="uil uil-bookmark saved-post" data-post-id="<?php echo $post['id']; ?>"></i>
                    <?php else: ?>
                        <i class="uil uil-bookmark not-saved-post" data-post-id="<?php echo $post['id']; ?>"></i>
                    <?php endif; ?>
            </div>
        </div>
        <div class="feed-interaction-details">
            <div class="feed-likes-container">
                <?php if ($post['like_count'] > 0): ?>
                    <?php if ($post['like_count'] == 1): ?>
                        <p class="like-count" data-post-id="<?php echo $post['id']; ?>">Liked by 1 student</p>
                    <?php else: ?>
                        <p class="like-count" data-post-id="<?php echo $post['id']; ?>">Liked by <?php echo htmlspecialchars($post['like_count']); ?> students</p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <div class="feed-comments-container">
                <?php if ($post['comment_count'] > 0): ?>
                        <?php if ($post['comment_count'] == 1): ?>
                            <p class="comment-count" data-post-id="<?php echo $post['id']; ?>">View comment</p>
                        <?php else: ?>
                            <p class="comment-count" data-post-id="<?php echo $post['id']; ?>">View all <?php echo htmlspecialchars($post['comment_count']); ?> comment</p>
                        <?php endif; ?>
                    <?php endif; ?>
            </div>
                <div class="feed-comments-close">
                        <input type="text" name="feedcomment" id="feedcomment-<?php echo $post['id']; ?>" placeholder="comments..">
                        <button id="post-comment-button" class="btn btn-primary post-comment-button" data-post-id="<?php echo $post['id']; ?>">Post</button>
                </div>
        </div>
        <div class="like-overlay" id="like-overlay-<?php echo $post['id']; ?>">
            <div class="like-container">
                <div class="header-liked-container">
                    <p>Liked by</p>
                </div>
                <div class="like-list" id="like-list-<?php echo $post['id']; ?>">
                    <!-- List of users who liked the post will be populated here -->
                </div>
            </div>
        </div>
        <div id="comment-overlay-<?php echo $post['id']; ?>" class="comment-overlay">
            <div class="comment-container">
                
                <div id="comment-list-<?php echo $post['id']; ?>" class="comment-list"></div>
            </div>
        </div>
        <div class="gallery-view">
            <div class="btn-close"><i class="uil uil-multiply"></i></div>
            <div class="btn-prev"><i class="uil uil-angle-left"></i></div>
            <div class="btn-next"><i class="uil uil-angle-right"></i></div>
            <div class="gallery-view-container"></div>
        </div> 
    </div>
<?php endforeach; ?>

                </div>
            </div>
        </div>
    </div>
</body>
<script>

</script>
</html>
