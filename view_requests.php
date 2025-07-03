<?php
session_start();
include("header.php");

if ($_SESSION['user_role'] !== 'staff') {
  die("Access denied.");
}

$conn = new mysqli("localhost", "root", "", "escom_transport");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT tr.id, tr.request_date, tr.status,
               v.registration_number,
               d.name AS driver_name
        FROM transport_requests tr
        LEFT JOIN vehicles v ON tr.vehicle_id = v.id
        LEFT JOIN drivers dr ON tr.driver_id = dr.id
        LEFT JOIN users d ON dr.user_id = d.id
        WHERE tr.user_id = ?
        ORDER BY tr.id DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
  <title>My Transport Requests</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h2>My Transport Requests</h2>

  <?php if ($result->num_rows > 0): ?>
    <table border="1" cellpadding="8">
      <tr>
        <th>ID</th>
        <th>Date</th>
        <th>Vehicle</th>
        <th>Driver</th>
        <th>Status</th>
      </tr>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= $row['request_date'] ?></td>
          <td><?= $row['registration_number'] ?? 'Not Assigned' ?></td>
          <td><?= $row['driver_name'] ?? 'Not Assigned' ?></td>
          <td><?= ucfirst($row['status']) ?></td>
        </tr>
      <?php endwhile; ?>
    </table>
  <?php else: ?>
    <p>You haven’t made any requests yet.</p>
  <?php endif; ?>

</body>
</html>

<?php $conn->close(); ?>
