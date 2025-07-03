<?php
session_start();
include("header.php");

if ($_SESSION['user_role'] !== 'fleet_controller') {
  die("Access denied.");
}

$conn = new mysqli("localhost", "root", "", "escom_transport");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$vehicle_id = $_POST['vehicle_id'];
$date = $_POST['maintenance_date'];
$description = $_POST['description'];
$cost = $_POST['cost'];

$sql = "INSERT INTO maintenance_records (vehicle_id, maintenance_date, description, cost) 
        VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("issd", $vehicle_id, $date, $description, $cost);

if ($stmt->execute()) {
  echo "Maintenance log submitted successfully.";
} else {
  echo "Error: " . $stmt->error;
}

$conn->close();
?>
