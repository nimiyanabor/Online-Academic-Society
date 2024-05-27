<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get form data
    $username = $_POST["username"];
    $password = $_POST["password"];
    $phone = $_POST["phone"];
    $dob = $_POST["dob"];
    $gender = $_POST["gender"];
    $email = $_POST["email"];
    $fullname = $_POST["fullname"];
    $level = $_POST["level"];
    $department = $_POST["department"];
    $stream = $_POST["stream"];
    $index = $_POST["index"];

    // Database connection
    $servername = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbname = "unichat";

    $conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the username, password, or email already exists
    $checkQuery = "SELECT * FROM user WHERE index_no = '$index' OR password = '$password' OR email = '$email'";
    $result = $conn->query($checkQuery);
    if ($result->num_rows > 0) {
        // Duplicate username, password, or email found
        echo "index number, password, or email already exists. Please choose a different one.";
    } else {
        // Insert data into the "user" table
        $sql = "INSERT INTO user (username, fullname, dob, phone, email, gender, password, level, department, stream, index_no) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssssss", $username, $fullname, $dob, $phone, $email, $gender, $password, $level, $department, $stream, $index);
        
        $user = $conn->insert_id;

        $_SESSION['id'] = $user['id'];

        if ($stmt->execute()) {

            header("Location: home.php");
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        
        // Close statement and database connection
        $stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign-Up</title>
    <link rel="stylesheet" href="signup.css">
</head>
<body>
    <nav>
            <h2 class="">UniChat</h2>
    </nav>
    <div class="header">
        <p>Join the Online Academic Society  UniChat</p>
    </div>
    <div class="signup-container">
        <form id="signup-form" method="POST">
            <div class="left">
                <label for="fullname">Full Name:</label>
                <input type="text" id="fullname" name="fullname" required>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <label for="phone">Phone Number:</label>
                <input type="tel" id="phone" name="phone" required>
                <label for="dob">Date of Birth</label>
                <input type="date" id="dob" name="dob" required>
                <label>Gender:</label>
                <input type="radio" id="male" name="gender" value="male" required>
                <label for="male">Male</label>
                <input type="radio" id="female" name="gender" value="female" required>
                <label for="female">Female</label>
            </div>
            <div class="right">
                <label for="index">Index No:</label>
                <input type="text" name="index" id="index">
                    <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <label for="level">Level:</label>
                <select id="level" name="level" required>
                    <option value="">Select Level</option>
                    <option value="100">100</option>
                    <option value="200">200</option>
                    <option value="300">300</option>
                    <option value="400">400</option>
                </select>
                <label for="department">Department:</label>
                <select id="department" name="department" required>
                    <option value="">Select Department</option>
                    <option value="IT">Information Tecnology</option>
                </select>
                <label>Student stream:</label>
                    <input type="radio" name="stream" id="weekend" value="weekend" required>
                    <label for="weekend">Weekend</label>
                    <input type="radio" name="stream" id="regular" value="regular" required>
                    <label for="regular">Regular</label>
                    <div class="down">
                        <button type="submit" name="submit">Sign Up</button>
                    </div>
            </div> 
           
        </form>
    </div>
    <div class="login-container">
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
    <div id="success-message"></div>
</body>
</html>
