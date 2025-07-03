<?php
session_start();
if ($_SESSION['user_role'] !== 'admin') {
  die("Access denied.");
}

include("header.php");

// Connect to database
$conn = new mysqli("localhost", "root", "", "escom_transport");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get all transport requests
$sql = "
  SELECT tr.id, u.name AS requester, tr.request_date, tr.status,
         v.registration_number,
         d.name AS driver_name
  FROM transport_requests tr
  LEFT JOIN users u ON tr.user_id = u.id
  LEFT JOIN vehicles v ON tr.vehicle_id = v.id
  LEFT JOIN drivers dr ON tr.driver_id = dr.id
  LEFT JOIN users d ON dr.user_id = d.id
  ORDER BY tr.id DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
  <title>All Transport Requests</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<h2>🚗 All Transport Requests (Admin)</h2>

<?php if ($result->num_rows > 0): ?>
  <table border="1" cellpadding="8">
    <tr>
      <th>ID</th>
      <th>Requester</th>
      <th>Date</th>
      <th>Vehicle</th>
      <th>Driver</th>
      <th>Status</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['requester'] ?></td>
        <td><?= $row['request_date'] ?></td>
        <td><?= $row['registration_number'] ?? 'Not Assigned' ?></td>
        <td><?= $row['driver_name'] ?? 'Not Assigned' ?></td>
        <td><?= ucfirst($row['status']) ?></td>
      </tr>
    <?php endwhile; ?>
  </table>
<?php else: ?>
  <p>No transport requests found.</p>
<?php endif; ?>

</body>
</html>

<?php $conn->close(); ?>
