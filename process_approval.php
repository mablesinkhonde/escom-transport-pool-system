<?php
session_start();
include("menu.php");
if ($_SESSION['user_role'] !== 'manager') {
  die("Access denied.");
}

$conn = new mysqli("localhost", "root", "", "escom_transport");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$request_id = $_GET['id'];
$action = $_GET['action'];

if ($action === 'approve') {
  $status = 'approved';
} elseif ($action === 'reject') {
  $status = 'rejected';
} else {
  die("Invalid action.");
}

$sql = "UPDATE transport_requests SET status = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $status, $request_id);

if ($stmt->execute()) {
  echo "Request has been " . $status . ".";
} else {
  echo "Error: " . $stmt->error;
}

$conn->close();
?>
