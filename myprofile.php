<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: /login.php");
    exit();
}

include 'db_connection.php';
include 'function.php';

$currentUserId = $_SESSION['id'];
$userProfile = getUserProfile($currentUserId);
$profileUserId =$_SESSION['id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>profile</title>
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <link rel="stylesheet" href="myprofile.css">
</head>
<body>
    <div class="container">
        <div class="profile-top">
            <div class="left-top">
                <div class="profile-pic">
                <img id="profilePic" src="profile/<?php echo htmlspecialchars($userProfile['profile_image']); ?>" alt="">
                </div>
                <p>@<?php echo htmlspecialchars($userProfile['username']); ?></p>
            </div>
            <div class="right-top">
                    <div class="name">
                        <label for="">Name:</label>
                        <p id="fullName"><?php echo htmlspecialchars($userProfile['fullname']); ?></p>
                    </div>
                    <div class="department">
                        <label for="">Department:</label>
                        <p id="department"><?php echo htmlspecialchars($userProfile['department']); ?></p>
                    </div>
                    <div class="level">
                        <label for="">Level:</label>
                        <p id="level"><?php echo htmlspecialchars($userProfile['level']); ?></p>
                    </div>
                    <div class="email">
                        <label for="email">Email:</label>
                        <p id="email"><?php echo htmlspecialchars($userProfile['email']); ?></p>
                    </div>
            </div>

        </div>
        <div class="bottom-container">
            <div class="bio-container">
                <div class="edit-profile-container" id="edit-profile-container">
                    <i class="uil uil-edit"></i>
                    <p>Edit Profile</p>
                </div>
                <div class="bio-text-container">
                    <div class="bio-icon">
                        <i class="uil uil-paragraph"></i>
                        <p>Bio</p>
                    </div>
                    <div class="bio-text">
                        <p id="Bio"><?php echo htmlspecialchars($userProfile['bio']); ?></p>
                    </div>
                </div>
                <div class="friends-container">
                    <div class="friends-icon">
                    <i class="uil uil-users-alt"></i>
                    </div>
                    <p>View your friends list..</p>
                </div>
            </div>
            <div class="activity-container">
                <div class="other-activity">
                    <div class="highlight">
                        <div class="highlight-container">
                            <i class="uil uil-window"></i>
                            <p>Highlight</p>
                        </div>
                        <p>Add to your highlight</p>
                    </div>
                    <div class="podcast">
                        <div class="podcast-container">
                            <i class="uil uil-microphone"></i>
                            <p>Podcast</p>
                        </div>
                        <p>talk with steven</p>
                    </div>
                    <div class="shop">
                        <div class="shop-container">
                            <i class="uil uil-shopping-cart-alt"></i>
                        <p>Shop</p>
                        </div>
                        <p>pastries by steven</p>
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
        <div class="edit-profile-modal" id="editProfileModal">
            <div class="modal-content">
                <span class="close" id="closeModal">&times;</span>
                <h2>Edit Profile</h2>
                <form id="editProfileForm" method="post" action="update_profile.php" enctype="multipart/form-data">
                    <label for="editUsername">Username:</label>
                    <input type="text" id="editUsername" name="username" value="<?php echo htmlspecialchars($userProfile['username']); ?>">
                    <label for="editBio">Bio:</label>
                    <textarea id="editBio" name="bio"><?php echo htmlspecialchars($userProfile['bio']); ?></textarea>
                    <label for="editProfileImage">Profile Image:</label>
                    <input type="file" id="editProfileImage" name="profile_image">
                    <button type="submit">Save Changes</button>
                </form>
            </div>
        </div>
        <div class="show-friends" id="showfriends">
            <div class="friends-content">
                <span class="close" id="closeModal">&times;</span>
                <h2>Friends List</h2>
                <div class="friend-container" id="friendContainer"></div>
            </div>
        </div>

    <script src="myprofile.js"></script>
</body>
</html>