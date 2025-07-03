<?php
session_start();
if ($_SESSION['user_role'] !== 'manager') {
  die("Access denied.");
}

include("header.php");

$conn = new mysqli("localhost", "root", "", "escom_transport");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Count transport requests
$total_requests = $conn->query("SELECT COUNT(*) AS total FROM transport_requests")->fetch_assoc()['total'];

// Count by status
$statuses = ['forwarded', 'approved', 'rejected'];
$data = [];
foreach ($statuses as $status) {
  $stmt = $conn->prepare("SELECT COUNT(*) FROM transport_requests WHERE status = ?");
  $stmt->bind_param("s", $status);
  $stmt->execute();
  $stmt->bind_result($count);
  $stmt->fetch();
  $data[$status] = $count;
  $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Manager Dashboard</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Manager Dashboard</h2>

<div style="width: 70%; margin: auto;">
  <table border="1" cellpadding="10">
    <tr><th colspan="2" style="background-color: #006400; color: white;">Request Summary</th></tr>
    <tr><td><strong>Total Requests</strong></td><td><?= $total_requests ?></td></tr>
    <tr><td><strong>Pending Approvals (Forwarded)</strong></td><td><?= $data['forwarded'] ?></td></tr>
    <tr><td><strong>Approved</strong></td><td><?= $data['approved'] ?></td></tr>
    <tr><td><strong>Rejected</strong></td><td><?= $data['rejected'] ?></td></tr>
  </table>

  <br>
  <a href="approve_requests.php"><button>🚦 View & Approve Requests</button></a>
</div>

</body>
</html>

