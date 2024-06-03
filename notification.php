<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Notification</title>
<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
<title>Notifications</title>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');
    :root{
        --primary-color-hue:252;
        --dark-color-lightness: 17%;
        --light-color-lightness: 95%;
        --white-color-lightness: 100%;
        --color-white: hsl(252, 30%, var(--white-color-lightness));
        --color-light: hsl(252, 30%, var(--light-color-lightness));
        --color-gray: hsl(252, 15%, 65%);
        --color-primary:hsl(var(--primary-color-hue), 75%, 60%);
        --color-secondary: hsl(252, 100%, 90%);
        --color-success: hsl(120, 95%, 65%);
        --color-danger: hsl(0, 95%, 65%);
        --color-dark: hsl(252, 30%, var(--dark-color-lightness));
        --color-black: hsl(252, 30%, 10%);
        --border-radius: 2rem;
        --card-border-radius: 1rem;
        --btn-padding: 0.6rem 2rem;
        --search-padding: 0.6rem 1rem;
        --card-padding: 1rem;
        --sticky-top-left: 5.4rem;
        --stich-top-right: -18rem;
    }
    a:hover, i:hover{
        cursor: pointer;
    }
    *,
    *::before,
    *::after {
        margin: 0;
        padding: 0;
        outline: 0;
        box-sizing: border-box;
        text-decoration:none;
        list-style: none;
        border: none;
    }
    body{
        height:100vh;
        width:100vw;
        font-family: "Poppins", sans-serif;
        color:var(--color-dark);
        background: var(--color-light);
        overflow-x: hidden; 
        display: flex;
        align-items: center;
        justify-content: space-around;
    }
    i:hover{
        opacity:0.8;
    }
    .btn{
        display: inline-block;
        padding: var(--btn-padding);
        font-weight: 500;
        border-radius: var(--border-radius);
        cursor: pointer;
        transition: all 300ms ease;
        font-size: 0.9rem;
    }
    .btn:hover{
        opacity: 0.8;
    }
    .btn-primary{
        background:var(--color-primary);
        color: white;
    }
    .notifications-container {
        width: 1000px;
        height: 600px;
        display: flex; /* Initially hidden */
        flex-direction: column;
        justify-content: space-around;
        align-items: center;
        background-color: var(--color-light);
        box-shadow: 0px 0px 5px 3px var(--color-primary);
        border-radius: 1rem;
        padding: 5px 0px;
        overflow-y: scroll;
        overflow-x:hidden;
    }
    .notifications-container::-webkit-scrollbar{
        width: 10px;
    }
    .notifications-container::-webkit-scrollbar-track{
        background-color: white;
    }
    .notifications-container::-webkit-scrollbar-thumb{
        border-radius:5px;
        background-color:var(--color-primary);
    }
    .notifications-container .notifications-header {
        background: white;
        color: black;
        padding: 10px 20px;
        text-align: center;
        font-size: 1.2em;
        border-radius: 1rem;
        box-shadow: 0px 0px 5px 3px var(--color-primary);
        margin:15px 0px;
    }
    .notifications-container .notification-item {
        padding: 15px;
        width: 90%;
        display: flex;
        align-items: center;
        background-color: white;
        border-radius:1rem;
        box-shadow: 0px 0px 5px 3px var(--color-secondary);
        margin: 10px 0px;
        position: relative;
    }
    .username{
        font-size:20px;
        font-weight: 500;
    }
    .notification-item.read .notification-icon {
        display: none;
    }
    
    .notification-icon {
        width: 40px;
        height: 40px;
        background-color: red;
        color: white;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-right: 15px;
    }
    .notification-text {
        flex: 1;
    }
    .notification-time {
        color: #999;
        font-size: 0.85em;
    }
    .notification-item:hover {
        background-color: #f9f9f9;
    }
    .notification-full {
        position: fixed;
        top: 50%;
        left: 50%;
        width: 300px;
        height: 200px;
        background-color: white;
        transform: translate(-50%, -50%);
        padding: 20px;
        border-radius: 1rem;
        box-shadow: 0px 0px 5px 3px var(--color-primary);
        display: none;
        flex-direction: column;
        justify-content: space-around;
        align-items: center;
    }
    .notification-full .notification-full-header {
        width:300px;
        height:100px;
        background: white;
        color: black;
        padding: 10px 20px;
        text-align: center;
        font-size: 1.2em;
        border-radius: 1rem;
        box-shadow: 0px 0px 5px 3px var(--color-primary);
        margin:15px 0px;
    }
    .notification-full .notification-full-message {
        padding: 20px;
        font-size: 1.2em;
        color: var(--color-dark);
    }
</style>
</head>
<body>
<div class="notifications-container" id="notifications-container">
    <div class="notifications-header">
        Notifications
    </div>
    <!-- Notification items will be inserted here by JavaScript -->
</div>
<div class="notification-full" id="notification-full">
    <div class="notification-full-header" id="notification-full-header">
        Notification
    </div>
    <div class="notification-full-message" id="notification-full-message">
        <!-- Full message will be displayed here -->
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetchNotifications();
    });

    function fetchNotifications() {
        fetch('view_notification.php', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.text())
        .then(data => {
            try {
                const jsonData = JSON.parse(data);
                if (jsonData.success) {
                    displayNotifications(jsonData.notifications);
                } else {
                    alert('Failed to fetch notifications');
                }
            } catch (e) {
                console.error('Invalid JSON response:', data);
                alert('An error occurred while processing your request.');
            }
        });
    }

    async function fetchUserName(userId) {
    try {
        const response = await fetch(`get_user_name.php?user_id=${userId}`);
        const data = await response.json();
        
        if (data.success) {
            return data.username;
        } else {
            console.error('Error fetching user name:', data.message);
            return 'Unknown User';
        }
    } catch (error) {
        console.error('Error fetching user name:', error);
        return 'Unknown User';
    }
}

async function displayNotifications(notifications) {
    const notificationContainer = document.getElementById('notifications-container');
    notificationContainer.innerHTML = '<div class="notifications-header">Notifications</div>';

    for (const notification of notifications) {
        const notificationItem = document.createElement('div');
        notificationItem.className = 'notification-item' + (notification.is_read ? ' read' : '');

        let notificationIcon = '<i class="uil uil-bell"></i>';

        // Extract the user ID from the notification message
        const userIdMatch = notification.message.match(/User (\d+)/);
        if (userIdMatch) {
            const userId = userIdMatch[1];
            const userName = await fetchUserName(userId);
            notification.message = notification.message.replace(`User ${userId}`, `${userName}`);
        }

        notificationItem.innerHTML = `
            <div class="notification-icon">${notificationIcon}</div>
            <div class="notification-text">
                ${notification.message}
                <div class="notification-time">${new Date(notification.created_at).toLocaleString()}</div>
            </div>
        `;

        notificationItem.addEventListener('click', function() {
            displayFullNotification(notification);
            markAsRead(notification.id);
        });

        notificationContainer.appendChild(notificationItem);
    }
}


    function displayFullNotification(notification) {
        const notificationFull = document.getElementById('notification-full');
        const notificationFullHeader = document.getElementById('notification-full-header');
        const notificationFullMessage = document.getElementById('notification-full-message');

        notificationFullHeader.innerText = 'Notification';
        notificationFullMessage.innerText = notification.message;

        notificationFull.style.display = 'flex';

        notificationFull.addEventListener('click', function() {
            notificationFull.style.display = 'none';
            location.reload();
        });
    }

    function markAsRead(notificationId) {
        fetch('mark_notification_read.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ notificationId: notificationId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const notificationItems = document.querySelectorAll('.notification-item');
                notificationItems.forEach(item => {
                    if (item.dataset.id == notificationId) {
                        item.classList.add('read');
                    }
                });
            } else {
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
</script>
</body>
</html>
