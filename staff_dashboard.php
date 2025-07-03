<?php
session_start();
if ($_SESSION['user_role'] !== 'staff') {
  die("Access denied.");
}

include("header.php"); // for navigation
$conn = new mysqli("localhost", "root", "", "escom_transport");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// Count requests by status
$statuses = ['pending', 'forwarded', 'approved', 'rejected'];
$data = [];

foreach ($statuses as $status) {
  $stmt = $conn->prepare("SELECT COUNT(*) FROM transport_requests WHERE user_id = ? AND status = ?");
  $stmt->bind_param("is", $user_id, $status);
  $stmt->execute();
  $stmt->bind_result($count);
  $stmt->fetch();
  $data[$status] = $count;
  $stmt->close();
}

// Total requests
$stmt = $conn->prepare("SELECT COUNT(*) FROM transport_requests WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($total);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Staff Dashboard</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h2>Staff Dashboard</h2>

  <div style="width: 60%; margin: auto;">
    <table border="1" cellpadding="10">
      <tr><th colspan="2" style="background-color: #006400; color: white;">My Request Summary</th></tr>
      <tr><td><strong>Total Requests</strong></td><td><?= $total ?></td></tr>
      <tr><td>Pending</td><td><?= $data['pending'] ?></td></tr>
      <tr><td>Forwarded</td><td><?= $data['forwarded'] ?></td></tr>
      <tr><td>Approved</td><td><?= $data['approved'] ?></td></tr>
      <tr><td>Rejected</td><td><?= $data['rejected'] ?></td></tr>
    </table>
  </div>

</body>
</html>

<?php $conn->close(); ?>
