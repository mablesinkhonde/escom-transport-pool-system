<?php
session_start();
include("menu.php");

if ($_SESSION['user_role'] !== 'staff') {
  die("Access denied.");
}

// DB connection
$conn = new mysqli("localhost", "root", "", "escom_transport");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$request_date = $_POST['request_date'];

// Insert into transport_requests
$sql = "INSERT INTO transport_requests (user_id, request_date, status) VALUES (?, ?, 'pending')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $user_id, $request_date);

if ($stmt->execute()) {
  echo "Transport request submitted successfully!";
} else {
  echo "Error: " . $stmt->error;
}

$conn->close();
?>
