<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

include 'function.php';
$user_id = $_SESSION['id'];
$profile_image = getUserProfileImage($user_id);

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES['media']) && isset($_POST['caption'])) {
    $user_id = $_SESSION['id'];
    $caption = $_POST['caption'];
    $images = $_FILES['media'];

    if (addPost($user_id, $caption, $images)) {

    } else {
        echo "Error adding post.";
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['text-post']))  {
    $user_id = $_SESSION['id'];
    $text = $_POST['text-post'];

    if (addTextPost($user_id, $text)) {

    } else {
        echo "Error adding post.";
    }
}

$post = getUserAndFriendsPosts($user_id);
$friendrequest = getPendingRequestSenderInfo($user_id);
$userProfile = getUserProfile($user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <link rel="stylesheet" href="home.css">
</head>
<body>
    <nav>
            <h2 class="logo">UniChat</h2>
            <div class="search-bar">
                <i class="uil uil-search"></i>
                <input type="search" id="searchInput" placeholder="search for friends and posts" onkeyup="searchUsers()">
                <div id="suggestions" class="suggestions"></div>
            </div>
            <div class="create">
                <button class="btn btn-primary" id="logout-button">Logout</button>
                <button class="btn btn-primary" id="open-create-container">Create</button>
                <div class="profile-photo">
                    <a href="myprofile.php"><img src="profile/<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Picture"></a>
                </div>
            </div>
            <div class="create-container" id="create-container">
                <div class="create-post-header"><h1>Create Post</h1></div>
                <div class="select-post-container">
                    <div class="media-post-button"><p>media</p></div>
                    <div class="text-post-button"><p>text</p></div>
                </div>
                <div class="media-post-container active">
                    <p>Post images and video</p>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="caption-container">
                            <label for="caption">Caption:</label>
                            <input type="text" name="caption" placeholder="caption...">
                        </div>
                        <div class="media-container">
                            <label for="media">Select images or videos:</label>
                            <input type="file" name="media[]" id="media" multiple accept="image/*,video/*">
                        </div>
                        <button type="submit" class="btn">Post</button>
                    </form>

                </div>
                <div class="text-post-container">
                    <p>Have something to say?...write it down<i class="uil uil-pen"></i></p>
                        <form action="" method="POST">
                            <div class="text-content">
                                <textarea id="feedback" class="styled-textarea" placeholder="Enter your text here..." name="text-post"></textarea>
                            </div>
                            <button type="submit" class="btn">Post</button>
                        </form>
                </div>
            </div>
    </nav>
    <main>
        <div class="left">
            <a class="profile" href="myprofile.php"> 
                <div class="profile-photo">
                    <img src="profile/<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Picture">
                </div>
                <div class="handle">
                    <h4><?php echo htmlspecialchars($userProfile['fullname']); ?></h4>
                    <p class="text-muted">
                        @<?php echo htmlspecialchars($userProfile['username']); ?>
                    </p>
                </div>
            </a>
            <!------------------------------side bar---------------------->
            <div class="sidebar">
                <a class="menu-item active" href="home.php">
                    <span><i class="uil uil-home"></i></span><h3>Home</h3>
                </a>
                <a class="menu-item">
                    <span><i class="uil uil-compass"></i></span><h3>Explore</h3>
                </a>
                <a class="menu-item">
                    <span><i class="uil uil-notebooks"></i></span><h3>Academics</h3>
                </a>
                <a class="menu-item" id="notifications">
                    <span><i class="uil uil-bell"><small class="notification-count">9+</small></i></span><h3>Notifications</h3>
                </a>
                <a class="menu-item" id="messages-notifications">
                    <span><i class="uil uil-envelope-alt"><small class="notification-count">6</small></i></span><h3>messages</h3>
                </a>
                <a class="menu-item">
                    <span><i class="uil uil-bookmark"></i></span><h3>Bookmarks</h3>
                </a>
                <a class="menu-item">
                    <span><i class="uil uil-shopping-cart-alt"></i></span><h3>Shops</h3>
                </a>
                <a class="menu-item" id="podcasts">
                    <span><i class="uil uil-microphone"></i></span><h3>Podcasts</h3>
                </a>
                <a class="menu-item">
                    <span><i class="uil uil-setting"></i></span><h3>Settings</h3>
                </a>
            </div>
        </div>
        <div class="middle">

            <?php 
                $posts = getUserAndFriendsPosts($user_id);
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
        <div class="right">
            <div class="messages-container">
                <div class="messages-top">
                    <h2>messages</h2>
                    <div class="search-message-container">
                        <i class="uil uil-search"></i>
                        <input type="search" placeholder="search for messages....">
                    </div>
                </div>
                <div class="messages-body">
                    <div class="message-list-container">
                        <div class="profile-container">
                            <img src="fa847a5c-dba4-4958-8cb1-d2a879906bd6-cover.png" alt="">
                        </div>
                        <div class="message-detail-container">
                            <div class="messenger-name">
                                <p>Larissa Jumbo</p>
                            </div>
                            <div class="message-text" >
                                <p>You still coming to my place?..</p>
                            </div>
                        </div>
                        <div class="message-time">
                            <p>11:45 AM</p>
                            <div class="message-red-container">
                                <p>9</p>
                            </div>
                        </div>
                    </div>
                    <div class="message-list-container">
                        <div class="profile-container">
                            <img src="fa847a5c-dba4-4958-8cb1-d2a879906bd6-cover.png" alt="">
                        </div>
                        <div class="message-detail-container">
                            <div class="messenger-name">
                                <p>Larissa Jumbo</p>
                            </div>
                            <div class="message-text" >
                                <p>You still coming to my place?..</p>
                            </div>
                        </div>
                        <div class="message-time">
                            <p>11:45 AM</p>
                            <div class="message-red-container">
                                <p>9</p>
                            </div>
                        </div>
                    </div>
                    <div class="message-list-container">
                        <div class="profile-container">
                            <img src="fa847a5c-dba4-4958-8cb1-d2a879906bd6-cover.png" alt="">
                        </div>
                        <div class="message-detail-container">
                            <div class="messenger-name">
                                <p>Larissa Jumbo</p>
                            </div>
                            <div class="message-text" >
                                <p>You still coming to my place?..</p>
                            </div>
                        </div>
                        <div class="message-time">
                            <p>11:45 AM</p>
                            <div class="message-red-container">
                                <p>9</p>
                            </div>
                        </div>
                    </div>
                    <div class="message-list-container">
                        <div class="profile-container">
                            <img src="fa847a5c-dba4-4958-8cb1-d2a879906bd6-cover.png" alt="">
                        </div>
                        <div class="message-detail-container">
                            <div class="messenger-name">
                                <p>Larissa Jumbo</p>
                            </div>
                            <div class="message-text" >
                                <p>You still coming to my place?..</p>
                            </div>
                        </div>
                        <div class="message-time">
                            <p>11:45 AM</p>
                            <div class="message-red-container">
                                <p>9</p>
                            </div>
                        </div>
                    </div>
                    <div class="message-list-container">
                        <div class="profile-container">
                            <img src="fa847a5c-dba4-4958-8cb1-d2a879906bd6-cover.png" alt="">
                        </div>
                        <div class="message-detail-container">
                            <div class="messenger-name">
                                <p>Larissa Jumbo</p>
                            </div>
                            <div class="message-text" >
                                <p>You still coming to my place?..</p>
                            </div>
                        </div>
                        <div class="message-time">
                            <p>11:45 AM</p>
                            <div class="message-red-container">
                                <p>9</p>
                            </div>
                        </div>
                    </div>
                    <div class="message-list-container">
                        <div class="profile-container">
                            <img src="fa847a5c-dba4-4958-8cb1-d2a879906bd6-cover.png" alt="">
                        </div>
                        <div class="message-detail-container">
                            <div class="messenger-name">
                                <p>Larissa Jumbo</p>
                            </div>
                            <div class="message-text" >
                                <p>You still coming to my place?..</p>
                            </div>
                        </div>
                        <div class="message-time">
                            <p>11:45 AM</p>
                            <div class="message-red-container">
                                <p>9</p>
                            </div>
                        </div>
                    </div>
                    <div class="message-list-container">
                        <div class="profile-container">
                            <img src="fa847a5c-dba4-4958-8cb1-d2a879906bd6-cover.png" alt="">
                        </div>
                        <div class="message-detail-container">
                            <div class="messenger-name">
                                <p>Larissa Jumbo</p>
                            </div>
                            <div class="message-text" >
                                <p>You still coming to my place?..</p>
                            </div>
                        </div>
                        <div class="message-time">
                            <p>11:45 AM</p>
                            <div class="message-red-container">
                                <p>9</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            if ($friendrequest) {
                    $senderFullName = htmlspecialchars($friendrequest['fullname']);
                    $requestId = $friendrequest['request_id'];
                    $profilePicture = htmlspecialchars($friendrequest['profile_picture']);
                    ?>
                    <div class="friend-request-container">
                        <h2>Friend Request</h2>
                        <div class="friend-request">
                            <div class="profile-container">
                                <div class="profile-image">
                                    <img src="profile/<?php echo $profilePicture; ?>" alt="Profile Picture">
                                </div>
                                <h3><?php echo $senderFullName; ?></h3>
                            </div>
                            <div class="accept-container btn btn-primary" onclick="handleRequest(<?php echo $requestId; ?>, 'accept')">
                                <p>Accept</p>
                            </div>
                            <div class="decline-container btn" onclick="handleRequest(<?php echo $requestId; ?>, 'delete')">
                                <p>Decline</p>
                            </div>
                        </div>
                    </div>
                    <?php
                } else {
                    echo "<div class='friend-request-container'>";
                    echo "<h2> No Friend request </h2>";
                    echo "</div>";
                }
                ?>
        </div>
    </main>
    <script src="home.js"></script>
</body>
</html>