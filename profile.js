
  document.querySelectorAll('.article-container').forEach((articleContainer) => {
  
  
  var commentButton = articleContainer.querySelector("#article-comment-button");
  function toggleCommentBox() {
    var element = articleContainer.querySelector(".article-comments-close");
    element.classList.toggle("article-comments-open");
  }
  
  // Event listener for comment button
  if (commentButton) {
    commentButton.addEventListener("click", toggleCommentBox);
  }
  
  // Event listener for clicks on the document body
  document.body.addEventListener("click", function(event) {
    // Check if the click target is not inside the comment box or its associated button
    if (!event.target.closest(".article-comments-close") && event.target !== commentButton) {
      // Close the comment box if it's open
      var commentBox = articleContainer.querySelector(".article-comments-close");
      if (commentBox.classList.contains("article-comments-open")) {
        commentBox.classList.remove("article-comments-open");
      }
    }
  });
  
  });

  document.addEventListener('DOMContentLoaded', function() {
    // Function to get URL parameters
    function getQueryParams() {
        let params = {};
        let queryString = window.location.search.substring(1);
        let regex = /([^&=]+)=([^&]*)/g, m;
        while (m = regex.exec(queryString)) {
            params[decodeURIComponent(m[1])] = decodeURIComponent(m[2]);
        }
        return params;
    }

    let params = getQueryParams();
    let userId = params['id'];

    if (userId) {
        fetchUserProfile(userId);
    }

    function fetchUserProfile(id) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'getProfile.php?id=' + encodeURIComponent(id), true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                try {
                    var user = JSON.parse(xhr.responseText);
                    console.log('Fetched user data:', user);  // Log the fetched data

                    if (user.error) {
                        console.error('Error fetching profile data:', user.error);
                    } else {
                        // Ensure elements exist before trying to set their properties
                        let profilePic = document.getElementById('profilePic');
                        let username = document.getElementById('username');
                        let fullName = document.getElementById('fullName');
                        let department = document.getElementById('department');
                        let level = document.getElementById('level');
                        let email = document.getElementById('email');
                        let bio = document.getElementById('bio');
                        let friendStatus = document.getElementById('friendStatus');

                        if (profilePic && user.profile_image) {
                            profilePic.src = 'profile/' + user.profile_image;
                        }
                        if (username) {
                            username.textContent = '@' + user.username;
                        }
                        if (fullName) {
                            fullName.textContent = user.fullname;
                        }
                        if (department) {
                            department.textContent = user.department;
                        }
                        if (level) {
                            level.textContent = user.level;
                        }
                        if (email) {
                            email.textContent = user.email;
                        }
                        if (bio) {
                            bio.textContent = user.bio;
                        }
                        if (friendStatus) {
                            friendStatus.textContent = user.friend_status;
                        }
                    }
                } catch (e) {
                    console.error('Invalid JSON response:', e);
                    console.error('Response Text:', xhr.responseText);
                }
            } else {
                console.error('Error fetching profile data:', xhr.statusText);
            }
        };
        xhr.send();
    }
});
document.addEventListener('DOMContentLoaded', function() {
  document.getElementById('add_friend').addEventListener('click', function() {
      let params = new URLSearchParams(window.location.search);
      let friendId = params.get('id'); // Get the friend's ID from the URL

      console.log('Friend ID:', friendId); // Debug: Log the friend ID

      if (friendId) {
          sendFriendRequest(friendId);
      }
  });



  function sendFriendRequest(friendId) {
      var xhr = new XMLHttpRequest();
      xhr.open('POST', 'addFriend.php', true);
      xhr.setRequestHeader('Content-Type', 'application/json'); // Set the correct content type for JSON
      xhr.onload = function() {
          if (xhr.status === 200) {
              try {
                  var response = JSON.parse(xhr.responseText);
                  console.log('Response:', response); // Debug: Log the response

                  if (response.success) {
                      console.log('Friend request sent successfully.');
                      // Optionally, update the UI to reflect the friend request status
                      location.reload();
                  } else {
                      console.error('Error sending friend request:', response.error);
                      location.reload();
                  }
              } catch (e) {
                  console.error('Invalid JSON response:', e);
                  console.error('Response Text:', xhr.responseText);
              }
          } else {
              console.error('Error sending friend request:', xhr.statusText);
          }
      };
      xhr.send(JSON.stringify({ friend_id: parseInt(friendId, 10) })); // Send the data as JSON
      console.log('Request sent: friend_id=' + friendId); // Debug: Log the sent data
  }


});
document.addEventListener('DOMContentLoaded', function() {
document.getElementById('cancel_friend_request').addEventListener('click', function() {
  let params = new URLSearchParams(window.location.search);
  let friendId = params.get('id');

  if (friendId) {
      cancelFriendRequest(friendId);
  }
});

function cancelFriendRequest(friendId) {
  var xhr = new XMLHttpRequest();
  xhr.open('POST', 'cancelfriendrequest.php', true);
  xhr.setRequestHeader('Content-Type', 'application/json'); // Set the correct content type for JSON
  xhr.onload = function() {
      if (xhr.status === 200) {
          try {
              var response = JSON.parse(xhr.responseText);
              console.log('Response:', response); // Debug: Log the response

              if (response.success) {
                  console.log('Friend request cancelled.');
                  // Optionally, update the UI to reflect the friend request status
                  location.reload();
              } else {
                  console.error('Error canceling request:', response.error);
              }
          } catch (e) {
              console.error('Invalid JSON response:', e);
              console.error('Response Text:', xhr.responseText);
          }
      } else {
          console.error('Error cancelling friend request:', xhr.statusText);
      }
  };
  xhr.send(JSON.stringify({ friend_id: parseInt(friendId, 10) })); // Send the data as JSON
  console.log('cancel request: friend_id=' + friendId); // Debug: Log the sent data
}

});


document.addEventListener('DOMContentLoaded', function() {
  const container = document.querySelector('.middle');

  container.addEventListener('click', function(event) {
      const postfeedContainer = event.target.closest('.postfeed-container');
      if (!postfeedContainer) return;

      const galleryView = postfeedContainer.querySelector('.gallery-view');
      const galleryViewContainer = postfeedContainer.querySelector('.gallery-view-container');
      const galleryImages = postfeedContainer.querySelectorAll('.gallery-img');

      if (event.target.closest('.gallery-img')) {
          const currentIndex = Array.from(galleryImages).indexOf(event.target.closest('.gallery-img'));
          showImage(galleryViewContainer, galleryImages, currentIndex);
          galleryView.style.display = 'flex';
      }

      if (event.target.closest('.btn-close')) {
          galleryView.style.display = 'none';
      }

      if (event.target.closest('.btn-next')) {
          const currentIndex = (parseInt(galleryViewContainer.dataset.currentIndex, 10) + 1) % galleryImages.length;
          showImage(galleryViewContainer, galleryImages, currentIndex);
      }

      if (event.target.closest('.btn-prev')) {
          const currentIndex = (parseInt(galleryViewContainer.dataset.currentIndex, 10) - 1 + galleryImages.length) % galleryImages.length;
          showImage(galleryViewContainer, galleryImages, currentIndex);
      }

      if (event.target.closest('#comment-button')) {
          const commentBox = postfeedContainer.querySelector('.feed-comments-close');
          commentBox.classList.toggle('feed-comments-open');
      }
      if (event.target.closest('.feed-comments-close') || event.target.closest('#comment-button')) {
        event.stopPropagation();
    }
      
  });
      // Close comment box when clicking outside
      document.body.addEventListener('click', function(event) {
        document.querySelectorAll('.feed-comments-open').forEach(commentBox => {
            if (!event.target.closest('.feed-comments-close') && !event.target.closest('#comment-button')) {
                commentBox.classList.remove('feed-comments-open');
            }
        });
    });

  function showImage(galleryViewContainer, galleryImages, index) {
      const currentImage = galleryImages[index].querySelector('img, video').cloneNode(true);
      galleryViewContainer.innerHTML = '';
      galleryViewContainer.appendChild(currentImage);
      galleryViewContainer.dataset.currentIndex = index;
  }

  function setGalleryImgSizes() {
      document.querySelectorAll('.postfeed-container').forEach(postfeedContainer => {
          const galleryImages = postfeedContainer.querySelectorAll('.gallery-img');
          const totalImagesSpan = postfeedContainer.querySelector('#total-images');

          const numImages = galleryImages.length;
          const containerWidth = 380;
          const containerHeight = 200;
          let width, height;

          if (numImages === 1) {
              width = containerWidth * 0.98; // 98% width
              height = containerHeight * 0.98; // 98% height
          } else if (numImages === 2) {
              width = containerWidth * 0.49; // 49% width
              height = containerHeight * 0.98; // 98% height
          } else if (numImages === 3) {
              width = containerWidth * 0.30; // 33% width
              height = containerHeight * 0.98; // 98% height
          } else if (numImages === 4) {
              width = containerWidth * 0.49; // 49% width
              height = containerHeight * 0.49; // 49% height
          } else if (numImages >= 5) {
              width = containerWidth * 0.33; // 33% width
              height = containerHeight * 0.49; // 49% height
          }

          // Apply fixed sizes to gallery images
          galleryImages.forEach((img, index) => {
              if (index >= 5) {
                  img.style.display = 'none'; // Hide images beyond the first five
              } else {
                  img.style.display = 'block';
                  img.style.width = `${width}px`;
                  img.style.height = `${height}px`;
              }
          });

          // Show total number of images in the gallery
          if (numImages > 5) {
              totalImagesSpan.innerHTML = `<p>+ ${numImages - 5}</p>`;
              totalImagesSpan.style.display = 'block';
          } else {
              totalImagesSpan.style.display = 'none';
          }

          // Add click event listener to totalImagesSpan
          totalImagesSpan.addEventListener('click', () => {
              showImage(galleryViewContainer, galleryImages, 0);
              postfeedContainer.querySelector('.gallery-view').style.display = 'flex';
          });
      });
  }

  // Set initial sizes
  setGalleryImgSizes();

  // Re-set sizes on window resize
  window.addEventListener('resize', setGalleryImgSizes);
  document.body.addEventListener('click', function(event) {
    document.querySelectorAll('.feed-comments-open').forEach(commentBox => {
        if (!event.target.closest('.feed-comments-close') && !event.target.closest('#comment-button')) {
            commentBox.classList.remove('feed-comments-open');
        }
    });
});
});

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.left-feed-interaction i.uil-heart').forEach(function(icon) {
        icon.addEventListener('click', function() {
            const postId = this.dataset.postId;
            const action = this.classList.contains('liked-post') ? 'unlike' : 'like';

            fetch('like_action.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ postId: postId, action: action })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (action === 'like') {
                        this.classList.add('liked-post');
                        this.classList.remove('not-liked-post');
                    } else {
                        this.classList.add('not-liked-post');
                        this.classList.remove('liked-post');
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
    document.querySelectorAll('.post-comment-button').forEach(function(button) {
        button.addEventListener('click', function() {
            const postId = this.getAttribute('data-post-id');
            const commentInput = document.querySelector('#feedcomment-' + postId);
            const comment = commentInput.value.trim();

            if (comment) {
                fetch('post_comment.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ postId: postId, comment: comment })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        commentInput.value = '';
                        const commentBox = postfeedContainer.querySelector('.feed-comments-close');
                        commentBox.classList.remove('feed-comments-open');
                    } else {
                        console.error('Error:', data.message);
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
            } else {
                alert('Please enter a comment.');
            }
        });
    });
    document.querySelectorAll('.uil-share-alt').forEach(function(icon) {
        icon.addEventListener('click', function() {
            const postUrl = this.getAttribute('data-post-url');

            navigator.clipboard.writeText(postUrl).then(() => {
                alert('Post link copied to clipboard!');
            }).catch(err => {
                console.error('Could not copy text: ', err);
            });
        });
    });
    document.querySelectorAll('.uil-share-alt').forEach(function(icon) {
        icon.addEventListener('click', function() {
            const postUrl = this.getAttribute('data-post-url');

            if (postUrl) {
                navigator.clipboard.writeText(postUrl).then(() => {
                    alert('Post link copied to clipboard!');
                }).catch(err => {
                    console.error('Could not copy text: ', err);
                });
            } else {
                console.error('Post URL is null or undefined');
            }
        });
    });
    document.querySelectorAll('.right-feed-interaction .uil-bookmark').forEach(function(icon) {
        icon.addEventListener('click', function() {
            const postId = this.getAttribute('data-post-id');
            const action = this.classList.contains('saved-post') ? 'unsave' : 'save';

            fetch('save_action.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ postId: postId, action: action })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (action === 'save') {
                        this.classList.remove('not-saved-post');
                        this.classList.add('saved-post');
                    } else {
                        this.classList.remove('saved-post');
                        this.classList.add('not-saved-post');
                    }
                } else {
                    console.error('Error:', data.message);
                }
            })
            .catch((error) => {
                console.error('Error:', error);
            });
        });
    });
});
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.like-count').forEach(function(element) {
        element.addEventListener('click', function() {
            const postId = this.getAttribute('data-post-id');
            if (postId) {
                const overlay = document.getElementById('like-overlay-' + postId);
                const likeList = document.getElementById('like-list-' + postId);

                fetch('get_likes.php?post_id=' + postId)
                    .then(response => response.json())
                    .then(data => {
                        console.log("Data received: ", data); // Debugging line to check data
                        if (data.likes && Array.isArray(data.likes)) {
                            likeList.innerHTML = data.likes.map(user => `<p>${user}</p>`).join('');
                        } else {
                            likeList.innerHTML = '<p>No likes found.</p>';
                        }
                        overlay.style.display = 'flex';
                    })
                    .catch(error => {
                        console.error('There was a problem with the fetch operation:', error);
                    });
            } else {
                console.error('Post ID is null or undefined');
            }
        });
    });
    document.querySelectorAll('.comment-count').forEach(function(element) {
        element.addEventListener('click', function() {
            const postId = this.getAttribute('data-post-id');
            if (postId) {
                const overlay = document.getElementById('comment-overlay-' + postId);
                const commentList = document.getElementById('comment-list-' + postId);

                fetch('get_comments.php?post_id=' + postId)
                    .then(response => response.json())
                    .then(data => {
                        if (data.comments && Array.isArray(data.comments)) {
                            commentList.innerHTML = data.comments.map(comment => `<p><strong>${comment.fullname}</strong>: ${comment.comment}</p>`).join('');
                        } else {
                            commentList.innerHTML = '<p>No comments found.</p>';
                        }
                        overlay.style.display = 'flex';
                    })
                    .catch(error => {
                        console.error('There was a problem with the fetch operation:', error);
                    });
            }
        });
    });

    document.querySelectorAll('.close-like-container').forEach(function(button) {
        button.addEventListener('click', function() {
            this.closest('.like-overlay').style.display = 'none';
        });
    });

    document.querySelectorAll('.like-overlay').forEach(function(overlay) {
        overlay.addEventListener('click', function(event) {
            if (event.target === overlay) {
                overlay.style.display = 'none';
            }
        });
    });
    document.querySelectorAll('.close-comment-container').forEach(function(button) {
        button.addEventListener('click', function() {
            this.closest('.comment-overlay').style.display = 'none';
        });
    });

    document.querySelectorAll('.comment-overlay').forEach(function(overlay) {
        overlay.addEventListener('click', function(event) {
            if (event.target === overlay) {
                overlay.style.display = 'none';
            }
        });
    });
});


