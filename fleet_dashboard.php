<?php
session_start();
if ($_SESSION['user_role'] !== 'fleet_controller') {
  die("Access denied.");
}

include("headerS.php");

$conn = new mysqli("localhost", "root", "", "escom_transport");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Count vehicles
$vehicles = $conn->query("SELECT COUNT(*) AS total FROM vehicles")->fetch_assoc()['total'];

// Count transport requests
$total_requests = $conn->query("SELECT COUNT(*) AS total FROM transport_requests")->fetch_assoc()['total'];

// Count requests by status
$statuses = ['pending', 'forwarded', 'approved', 'rejected'];
$request_counts = [];
foreach ($statuses as $status) {
  $stmt = $conn->prepare("SELECT COUNT(*) FROM transport_requests WHERE status = ?");
  $stmt->bind_param("s", $status);
  $stmt->execute();
  $stmt->bind_result($count);
  $stmt->fetch();
  $request_counts[$status] = $count;
  $stmt->close();
}

// Count fuel logs
$fuel_logs = $conn->query("SELECT COUNT(*) AS total FROM fuel_logs")->fetch_assoc()['total'];

// Count maintenance records
$maintenance_logs = $conn->query("SELECT COUNT(*) AS total FROM maintenance_records")->fetch_assoc()['total'];

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Fleet Controller Dashboard</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Fleet Controller Dashboard</h2>

<div style="width: 70%; margin: auto;">
  <table border="1" cellpadding="10">
    <tr><th colspan="2" style="background-color: #006400; color: white;">System Summary</th></tr>
    <tr><td><strong>Total Vehicles</strong></td><td><?= $vehicles ?></td></tr>
    <tr><td><strong>Total Transport Requests</strong></td><td><?= $total_requests ?></td></tr>
    <tr><td>Pending Requests</td><td><?= $request_counts['pending'] ?></td></tr>
    <tr><td>Forwarded Requests</td><td><?= $request_counts['forwarded'] ?></td></tr>
    <tr><td>Approved Requests</td><td><?= $request_counts['approved'] ?></td></tr>
    <tr><td>Rejected Requests</td><td><?= $request_counts['rejected'] ?></td></tr>
    <tr><td><strong>Fuel Logs</strong></td><td><?= $fuel_logs ?></td></tr>
    <tr><td><strong>Maintenance Records</strong></td><td><?= $maintenance_logs ?></td></tr>
  </table>
</div>

</body>
</html>
