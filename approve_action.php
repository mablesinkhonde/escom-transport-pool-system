<?php
session_start();

// Only the manager should be allowed
if ($_SESSION['user_role'] !== 'manager') {
  die("Access denied.");
}
include("header.php");
if (!isset($_GET['id']) || !isset($_GET['status'])) {
  die("Invalid request.");
}

$request_id = $_GET['id'];
$status = $_GET['status'];

// Validate status value
if (!in_array($status, ['approved', 'rejected'])) {
  die("Invalid status.");
}

$conn = new mysqli("localhost", "root", "", "escom_transport");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Update the request status
$stmt = $conn->prepare("UPDATE transport_requests SET status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $request_id);
$stmt->execute();
$stmt->close();

$conn->close();

// Redirect back to the approval page
header("Location: approve_requests.php");
exit;
?>
