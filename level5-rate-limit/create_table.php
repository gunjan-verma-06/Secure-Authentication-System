<?php
include 'db.php';

$sql = "CREATE TABLE IF NOT EXISTS users_auth (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "Table created successfully!";
} else {
    echo "Error: " . $conn->error;
}
?>