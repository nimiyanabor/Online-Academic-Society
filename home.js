document.querySelectorAll('.postfeed-container').forEach((postfeedContainer) => {

  const galleryImages = postfeedContainer.querySelectorAll('.gallery-img');
  const closeButton = postfeedContainer.querySelector('.btn-close');
  const nextButton = postfeedContainer.querySelector('.btn-next');
  const prevButton = postfeedContainer.querySelector('.btn-prev');
  const galleryView = postfeedContainer.querySelector('.gallery-view');
  const galleryViewContainer = postfeedContainer.querySelector('.gallery-view-container');
  const totalImagesSpan = postfeedContainer.querySelector('#total-images');
  const commentButton = postfeedContainer.querySelector("#comment-button");

  let currentIndex = 0;

  // Show image or video in view
  function showImage(index) {
    const currentImage = galleryImages[index].querySelector('img, video');
    const clone = currentImage.cloneNode(true);
    galleryViewContainer.innerHTML = '';
    galleryViewContainer.appendChild(clone);
    currentIndex = index;
  }

  // Event listeners for each gallery image
  galleryImages.forEach((image, index) => {
    image.addEventListener('click', () => {
      showImage(index);
      galleryView.style.display = 'flex';
    });
  });

  // Close button
  closeButton.addEventListener('click', () => {
    galleryView.style.display = 'none';
  });

  // Next button
  nextButton.addEventListener('click', () => {
    currentIndex = (currentIndex + 1) % galleryImages.length;
    showImage(currentIndex);
  });

  // Previous button
  prevButton.addEventListener('click', () => {
    currentIndex = (currentIndex - 1 + galleryImages.length) % galleryImages.length;
    showImage(currentIndex);
  });

  // Set fixed sizes for gallery images based on the number of images
  function setGalleryImgSizes() {
    const numImages = galleryImages.length;
    const containerWidth = 500;
    const containerHeight = 300;
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
  }
  // Add click event listener to totalImagesSpan
  totalImagesSpan.addEventListener('click', () => {
    // Show the first image in the gallery view
    showImage(0);
    // Display the gallery view
    galleryView.style.display = 'flex';
  });

  // Set initial sizes
  setGalleryImgSizes();

  // Re-set sizes on window resize
  window.addEventListener('resize', setGalleryImgSizes);

  // Toggle comment box function
// Function to toggle comment box
function toggleCommentBox() {
  var element = postfeedContainer.querySelector(".feed-comments-close");
  element.classList.toggle("feed-comments-open");
}

// Event listener for comment button
if (commentButton) {
  commentButton.addEventListener("click", toggleCommentBox);
}

// Event listener for clicks on the document body
document.body.addEventListener("click", function(event) {
  // Check if the click target is not inside the comment box or its associated button
  if (!event.target.closest(".feed-comments-close") && event.target !== commentButton) {
    // Close the comment box if it's open
    var commentBox = postfeedContainer.querySelector(".feed-comments-close");
    if (commentBox.classList.contains("feed-comments-open")) {
      commentBox.classList.remove("feed-comments-open");
    }
  }
});


});
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

 document.addEventListener("DOMContentLoaded", function() {
            const mediaPostButton = document.querySelector('.media-post-button');
            const textPostButton = document.querySelector('.text-post-button');
            const mediaPostContainer = document.querySelector('.media-post-container');
            const textPostContainer = document.querySelector('.text-post-container');
            const toggleButton = document.getElementById("open-create-container");
            const modal = document.getElementById("create-container");
    
            toggleButton.addEventListener("click", function(event) {
                modal.style.display = 'flex';
                event.stopPropagation();
            });
    
            document.addEventListener("click", function() {
                modal.style.display = 'none';
            });
    
            modal.addEventListener("click", function(event) {
                event.stopPropagation();
            });

            mediaPostButton.addEventListener('click', function() {
                mediaPostContainer.classList.add('active');
                textPostContainer.classList.remove('active');
            });

            textPostButton.addEventListener('click', function() {
                textPostContainer.classList.add('active');
                mediaPostContainer.classList.remove('active');
            });
        });

        function searchUsers() {
          var input = document.getElementById('searchInput').value;
      


          if (input.length === 0) {
              document.getElementById('suggestions').style.display = 'none';
              return;
          }
      
          var xhr = new XMLHttpRequest();
          xhr.open('GET', 'search.php?query=' + encodeURIComponent(input), true);
          xhr.onload = function () {
              if (xhr.status === 200) {
                  var results = JSON.parse(xhr.responseText);
                  console.log(results); // Log the retrieved data to the console
      
                  var suggestions = document.getElementById('suggestions');
                  suggestions.innerHTML = '';
      
                  if (results.length > 0) {
                      suggestions.style.display = 'block';
                      results.forEach(function(user) {
                          var suggestionItem = document.createElement('p');
                          suggestionItem.textContent = user.fullname + ' (@' + user.username + ')';
                          suggestions.appendChild(suggestionItem);
                      });
                  } else {
                      suggestions.style.display = 'none';
                  }
              }
          };
          xhr.send();
          // Add event listener to suggestions div

      }
      function searchUsers() {
        var input = document.getElementById('searchInput').value;

        if (input.length === 0) {
            document.getElementById('suggestions').style.display = 'none';
            return;
        }

        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'search.php?query=' + encodeURIComponent(input), true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                console.log('Response received:', xhr.responseText); // Log the response
                var results = JSON.parse(xhr.responseText);
                var suggestions = document.getElementById('suggestions');
                suggestions.innerHTML = '';

                if (results.length > 0) {
                    suggestions.style.display = 'block';
                    results.forEach(function(user) {
                        var suggestionItem = document.createElement('p');
                        suggestionItem.textContent = user.fullname + ' (@' + user.username + ')';
                        suggestionItem.classList.add('suggestion'); // Add class to identify suggestion items
                        suggestionItem.setAttribute('data-id', user.id); // Add data-id attribute with friend's ID

                        // Attach the click event listener to each suggestion item
                        suggestionItem.addEventListener('click', function() {
                            var friendId = this.getAttribute('data-id');
                            window.location.href = 'profile.php?id=' + friendId;
                        });

                        suggestions.appendChild(suggestionItem);
                    });
                } else {
                    suggestions.style.display = 'none';
                }
            } else {
                console.error('Error fetching search results:', xhr.statusText);
            }
        };
        xhr.send();
    }

    document.getElementById('logout-button').addEventListener('click', function() {
      window.location.href = 'logout.php';
  });

  