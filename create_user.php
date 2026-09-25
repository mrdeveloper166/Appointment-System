<?php
include 'db_connect.php';

$username = 'admin';
$password = 'admin123';
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO admin (username, password) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $hashed_password);

if ($stmt->execute()) {
    echo "Admin created successfully!";
} else {
    echo "Error creating admin: " . $conn->error;
}
?>