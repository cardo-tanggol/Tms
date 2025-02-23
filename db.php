<?php
$host = "192.168.100.244"; // MySQL Server IP
$port = "3306"; // MySQL Port
$username = "root"; // MySQL Username
$password = "root"; // MySQL Password
$database = "task_management"; // Database Name

// Create connection
$conn = new mysqli($host, $username, $password, $database, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>