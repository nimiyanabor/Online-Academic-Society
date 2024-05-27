<?php
// Start the session
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get form data
    $index = $_POST["index"];
    $password = $_POST["password"];

    // Database connection
    $servername = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbname = "unichat";

    $conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the index number and password match
    $sql = "SELECT id FROM user WHERE index_no = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $index, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $_SESSION["id"] = $row["id"];
        
        // Debugging information
        error_log("Authentication successful. Redirecting to home.php.");

        header("Location: home.php");
        exit(); // Ensure no further code is executed
    } else {
        // Authentication failed
        $error = "Invalid index number or password.";
        // Debugging information
        error_log("Authentication failed. Error: " . $error);
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <nav>
        <h2 class="">UniChat</h2>
    </nav>
    <div class="header">
        <p>Re-Join the Online Academic Society UniChat</p>
    </div>
    <div class="login-container">
        <form id="login-form" method="POST">
            <label for="index">Index No:</label>
            <input type="text" name="index" id="index" required>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
            <div class="down">
                <button type="submit" name="submit">Login</button>
            </div>
        </form>
        <?php
        if (isset($error)) {
            echo '<p style="color: red;">' . htmlspecialchars($error) . '</p>';
        }
        ?>
    </div>
    <div class="signup-container">
        <p>Don't have an account? Click <a href="signup.html">Here</a></p>
    </div>
</body>
</html>
