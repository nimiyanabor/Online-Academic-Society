<?php
// db_connection.php

$host = 'localhost'; // Your database host (usually 'localhost')
$db   = 'unichat';   // Your database name
$user = 'root';      // Your database username
$pass = '';          // Your database password

// Create connection
$mysqli = new mysqli($host, $user, $pass, $db);

// Check connection
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}
?>
