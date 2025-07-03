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

// Fixed query with error checking
$sql = "
  SELECT r.id, 
         requester.name AS requester, 
         v.registration_number, 
         driver_user.name AS driver, 
         r.request_date 
  FROM transport_requests r
  JOIN users requester ON r.user_id = requester.id
  JOIN vehicles v ON r.vehicle_id = v.id
  JOIN drivers d ON r.driver_id = d.id
  JOIN users driver_user ON d.user_id = driver_user.id
  WHERE r.status = 'forwarded'
";


$result = $conn->query($sql);

// Check if query worked
if (!$result) {
  die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Approve Requests</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<h2>Approve Transport Requests</h2>

<?php if ($result->num_rows > 0): ?>
<table border="1" cellpadding="10">
  <tr>
    <th>ID</th>
    <th>Requester</th>
    <th>Vehicle</th>
    <th>Driver</th>
    <th>Request Date</th>
    <th>Action</th>
  </tr>

  <?php while ($row = $result->fetch_assoc()): ?>
  <tr>
    <td><?= $row['id'] ?></td>
    <td><?= $row['requester'] ?></td>
    <td><?= $row['registration_number'] ?></td>
    <td><?= $row['driver'] ?></td>
    <td><?= $row['request_date'] ?></td>
    <td>
      <a href="approve_action.php?id=<?= $row['id'] ?>&status=approved">
        <button style="background-color: green;">Approve</button>
      </a>
      <a href="approve_action.php?id=<?= $row['id'] ?>&status=rejected">
        <button style="background-color: red;">Reject</button>
      </a>
    </td>
  </tr>
  <?php endwhile; ?>
</table>
<?php else: ?>
  <p style="color: orange;">No requests pending approval.</p>
<?php endif; ?>

</body>
</html>

<?php $conn->close(); ?>
