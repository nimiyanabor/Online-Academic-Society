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
                <img src="profile/<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Picture">
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
            <a class="profile" href="edit_profile.php"> 
                <div class="profile-photo">
                    <img src="profile/<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Picture">
                </div>
                <div class="handle">
                    <h4>YANABOR</h4>
                    <p class="text-muted">
                        @yanabor001
                    </p>
                </div>
            </a>
            <!------------------------------side bar---------------------->
            <div class="sidebar">
                <a class="menu-item active">
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
            
            <div class="podcast-container">
                <div class="cover-photo">
                    <img src="fa847a5c-dba4-4958-8cb1-d2a879906bd6-cover.png" alt="">
                </div>
                <div class="podcast-description">
                    <div class="podcast-title">
                        <p class="episode-name">What do men wants?</p>
                        <a href="" class="podcast-name"> Relationship talk with Steven</a>
                    </div>
                    
                    <div class="podcast-info">
                        <p>Judging through the disires of men and how much does it take a woman to satify does desires</p>
                    </div>
                    <div class="podcast-play-container">
                        <i class="uil uil-play-circle play-play" ></i>
                        <div class="save-podcast-contianer">
                            <i class="uil uil-bookmark-full"></i>
                            <i class="uil uil-ellipsis-h"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="postfeed-container">
                <div class="feed-header">
                    <a href="" >
                        <div class="feed-profile">
                            <img src="fa847a5c-dba4-4958-8cb1-d2a879906bd6-cover.png" alt="">

                        </div>
                        <h3>Yanabor</h3>
                    </a>
                    <div class="feed-options">
                        <a href=""><i class="uil uil-ellipsis-h"></i></a>
                    </div>
                </div>
                <div class="feed-caption">
                    <p>Judging through the disires of men and how much does it take a woman to satify does desires</p>
                </div>
                <div class="gallery-container">
                    <div class="gallery-img">
                      <img class="gallery-img-img" src="fa847a5c-dba4-4958-8cb1-d2a879906bd6-cover.png" alt="Image 1">
                    </div>
                    <div class="gallery-img">
                      <img class="gallery-img-img"  src="IMG_2499.JPG" alt="Image 2">
                    </div>
                    <div class="gallery-img">
                      <img class="gallery-img-img"  src="fa847a5c-dba4-4958-8cb1-d2a879906bd6-cover.png" alt="Image 3">
                    </div>
                    <div class="gallery-img">
                      <video class="gallery-img-video" autoplay muted  src="0c30b322a8acd511eab96ee02fdfe464.mp4" alt="Image 4">
                    </div>
                    <div class="gallery-img">
                      <video class="gallery-img-video" autoplay muted src="0c30b322a8acd511eab96ee02fdfe464.mp4" alt="Image 4">
                    </div>
                    <div class="gallery-img">
                        <img class="gallery-img-img" src="fa847a5c-dba4-4958-8cb1-d2a879906bd6-cover.png" alt="Image 1">
                    </div>
                    <div class=" gallery-imgs" id="total-images"></div> <!-- Change span to div -->
                </div>
                <div class="feed-interaction">
                    <div class="left-feed-interaction">
                        <i class="uil uil-heart"></i>
                        <i class="uil uil-comment-dots" id="comment-button"></i>
                        <i class="uil uil-share-alt"></i>
                    </div>
                    <div class="right-feed-interaction">
                        <a href="" ><i class="uil uil-bookmark-full"></i></a>
                    </div>
                </div>
                <div class="feed-interaction-details">
                    
                    <div class="feed-likes-container">
                        <p>liked by you and 37 others</p>
                    </div>
                    <div class="feed-comments-container">
                        <p>view all 12 comments</p>
                    </div>
                    <div class="feed-comments-close">
                        <input type="text" name="feedcomment" id="feedcomment" placeholder="comments..">
                        <button class="btn btn-primary"> Post</button>
                    </div>
                </div>
                <div class="gallery-view">
                    <div class="btn-close"><i class="uil uil-multiply"></i></div>
                    <div class="btn-prev"><i class="uil uil-angle-left"></i></div>
                    <div class="btn-next"><i class="uil uil-angle-right"></i></div>
                    <div class="gallery-view-container"></div>
                </div>                               
            </div>
            <div class="postfeed-container">
                <div class="feed-header">
                    <a href="" >
                        <div class="feed-profile">
                            <img src="fa847a5c-dba4-4958-8cb1-d2a879906bd6-cover.png" alt="">

                        </div>
                        <h3>Yanabor</h3>
                    </a>
                    <div class="feed-options">
                        <a href=""><i class="uil uil-ellipsis-h"></i></a>
                    </div>
                </div>
                <div class="feed-caption">
                    <p>Judging through the disires of men and how much does it take a woman to satify does desires</p>
                </div>
                <div class="gallery-container">
                    <div class="gallery-img">
                      <img class="gallery-img-img" src="fa847a5c-dba4-4958-8cb1-d2a879906bd6-cover.png" alt="Image 1">
                    </div>
                    <div class="gallery-img">
                      <img class="gallery-img-img"  src="IMG_2499.JPG" alt="Image 2">
                    </div>
                    <div class="gallery-img">
                      <img class="gallery-img-img"  src="fa847a5c-dba4-4958-8cb1-d2a879906bd6-cover.png" alt="Image 3">
                    </div>
                    <div class="gallery-img">
                      <video class="gallery-img-video" autoplay muted  src="0c30b322a8acd511eab96ee02fdfe464.mp4" alt="Image 4">
                    </div>
                    <div class="gallery-img">
                      <video class="gallery-img-video" autoplay muted src="0c30b322a8acd511eab96ee02fdfe464.mp4" alt="Image 4">
                    </div>
                    <div class="gallery-img">
                        <img class="gallery-img-img" src="fa847a5c-dba4-4958-8cb1-d2a879906bd6-cover.png" alt="Image 1">
                    </div>
                    <div class=" gallery-imgs" id="total-images"></div> <!-- Change span to div -->
                </div>
                <div class="feed-interaction">
                    <div class="left-feed-interaction">
                        <i class="uil uil-heart"></i>
                        <i class="uil uil-comment-dots" id="comment-button"></i>
                        <i class="uil uil-share-alt"></i>
                    </div>
                    <div class="right-feed-interaction">
                        <a href="" ><i class="uil uil-bookmark-full"></i></a>
                    </div>
                </div>
                <div class="feed-interaction-details">
                    
                    <div class="feed-likes-container">
                        <p>liked by you and 37 others</p>
                    </div>
                    <div class="feed-comments-container">
                        <p>view all 12 comments</p>
                    </div>
                    <div class="feed-comments-close">
                        <input type="text" name="feedcomment" id="feedcomment" placeholder="comments..">
                        <button class="btn btn-primary"> Post</button>
                    </div>
                </div>
                <div class="gallery-view">
                    <div class="btn-close"><i class="uil uil-multiply"></i></div>
                    <div class="btn-prev"><i class="uil uil-angle-left"></i></div>
                    <div class="btn-next"><i class="uil uil-angle-right"></i></div>
                    <div class="gallery-view-container"></div>
                </div>                               
            </div>
            <div class="article-container">
                <div class="article-header">
                    <a href="" >
                        <div class="article-profile">
                            <img src="fa847a5c-dba4-4958-8cb1-d2a879906bd6-cover.png" alt="">
                        </div>
                        <h3>Yanabor</h3>
                    </a>
                    <div class="article-options">
                        <a href=""><i class="uil uil-ellipsis-h"></i></a>
                    </div>
                </div>
                <div class="article-title">
                    <h4>So many things we talk about</h4>
                </div>
                <div class="article-text">
                    <p>Judging through the disires of men and how much does it take a woman to satify does desires</p>
                </div>
                <div class="article-interaction">
                    <div class="left-article-interaction">
                        <i class="uil uil-heart"></i>
                        <i class="uil uil-comment-dots" id="article-comment-button"></i>
                        <i class="uil uil-share-alt"></i>
                    </div>
                    <div class="right-article-interaction">
                        <a href="" ><i class="uil uil-bookmark-full"></i></a>
                    </div>
                </div>
                <div class="article-interaction-details">
                    
                    <div class="article-likes-container">
                        <p>liked by you and 37 others</p>
                    </div>
                    <div class="article-comments-container">
                        <p>view all 12 comments</p>
                    </div>
                    <div class="article-comments-close">
                        <input type="text" name="feedcomment" id="feedcomment" placeholder="comments..">
                        <button class="btn btn-primary"> Post</button>
                    </div>
                </div>                              
            </div>
            <div class="article-container">
                <div class="article-header">
                    <a href="" >
                        <div class="article-profile">
                            <img src="fa847a5c-dba4-4958-8cb1-d2a879906bd6-cover.png" alt="">
                        </div>
                        <h3>Yanabor</h3>
                    </a>
                    <div class="article-options">
                        <a href=""><i class="uil uil-ellipsis-h"></i></a>
                    </div>
                </div>
                <div class="article-title">
                    <h4>So many things we talk about</h4>
                </div>
                <div class="article-text">
                    <p>Judging through the disires of men and how much does it take a woman to satify does desires Lorem, ipsum dolor sit amet consectetur adipisicing elit. Rerum numquam alias aliquid, ullam pariatur ea et iure id quos laboriosam, <br> perspiciatis asperiores magnam quasi debitis sed nihil sapiente officiis atque. Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quia iure omnis sequi officiis soluta assumenda cumque, libero quaerat qui maiores ad in nisi et suscipit? Corporis reiciendis eligendi quo exercitationem. Lorem ipsum dolor sit amet consectetur adipisicing elit. Nemo obcaecati asperiores architecto impedit facere nam itaque ratione, quidem consequuntur aperiam officiis ullam excepturi, iste laborum voluptates dignissimos veritatis maiores velit? Lorem ipsum dolor sit amet, consectetur adipisicing elit. Commodi, tenetur temporibus. Est enim repudiandae quas! Sint eaque omnis cumque harum? Fugit repellendus cumque expedita fuga quibusdam, quaerat tempore totam unde. Lorem ipsum dolor sit amet consectetur adipisicing elit. Et ab incidunt libero odio exercitationem ullam earum alias. Nam ut labore sunt incidunt quasi praesentium, commodi sed obcaecati asperiores deserunt? Facilis. Lorem ipsum dolor, sit amet consectetur adipisicing elit. Suscipit excepturi voluptates ad, porro soluta voluptas nulla sunt? Iusto repellat iure officiis tenetur dicta, dolores, commodi corporis, temporibus exercitationem perspiciatis accusantium!</p>
                </div>
                <div class="article-interaction">
                    <div class="left-article-interaction">
                        <i class="uil uil-heart"></i>
                        <i class="uil uil-comment-dots" id="article-comment-button"></i>
                        <i class="uil uil-share-alt"></i>
                    </div>
                    <div class="right-article-interaction">
                        <a href="" ><i class="uil uil-bookmark-full"></i></a>
                    </div>
                </div>
                <div class="article-interaction-details">
                    
                    <div class="article-likes-container">
                        <p>liked by you and 37 others</p>
                    </div>
                    <div class="article-comments-container">
                        <p>view all 12 comments</p>
                    </div>
                    <div class="article-comments-close">
                        <input type="text" name="feedcomment" id="feedcomment" placeholder="comments..">
                        <button class="btn btn-primary"> Post</button>
                    </div>
                </div>                              
            </div>
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
            <div class="friend-request-container">
                <h2>friend request</h2>
                <div class="friend-request">
                    <div class="profile-container">
                        <div class="profile-image">
                            <img src="fa847a5c-dba4-4958-8cb1-d2a879906bd6-cover.png" alt="">
                        </div>
                        <h3>Belema</h3>
                    </div>
                    <div class="accept-container btn btn-primary">
                        <p>accept</p>
                    </div>
                    <div class="decline-container btn">
                        decline
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="home.js"></script>
</body>
</html>