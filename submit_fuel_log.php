<?php
session_start();
include("header.php");

if ($_SESSION['user_role'] !== 'fleet_controller' && $_SESSION['user_role'] !== 'driver') {
  die("Access denied.");
}

$conn = new mysqli("localhost", "root", "", "escom_transport");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$vehicle_id = $_POST['vehicle_id'];
$date = $_POST['date'];
$litres = $_POST['litres'];
$cost = $_POST['cost'];

$sql = "INSERT INTO fuel_logs (vehicle_id, date, litres, cost) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isdd", $vehicle_id, $date, $litres, $cost);

if ($stmt->execute()) {
  echo "Fuel log submitted successfully.";
} else {
  echo "Error: " . $stmt->error;
}

$conn->close();
?>
