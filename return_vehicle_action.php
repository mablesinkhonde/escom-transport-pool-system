<?php
session_start();
include("header.php");
if ($_SESSION['user_role'] !== 'driver') {
  die("Access denied.");
}

if (!isset($_POST['request_id'])) {
  die("Invalid submission.");
}

$conn = new mysqli("localhost", "root", "", "escom_transport");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$request_id = $_POST['request_id'];
$remarks = $_POST['remarks'];
$return_time = date('Y-m-d H:i:s');

// Update the transport request
$stmt = $conn->prepare("
  UPDATE transport_requests 
  SET return_time = ?, return_remarks = ?, status = 'completed'
  WHERE id = ?
");
$stmt->bind_param("ssi", $return_time, $remarks, $request_id);
$stmt->execute();
$stmt->close();
$conn->close();

// Redirect back to driver dashboard
header("Location: driver_dashboard.php");
exit;
