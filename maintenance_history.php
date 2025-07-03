<?php
session_start();
include("menu.php");

if ($_SESSION['user_role'] !== 'fleet_controller') {
  die("Access denied.");
}

$conn = new mysqli("localhost", "root", "", "escom_transport");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT m.id, v.registration_number, m.maintenance_date, m.description, m.cost
        FROM maintenance_records m
        JOIN vehicles v ON m.vehicle_id = v.id
        ORDER BY m.maintenance_date DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Maintenance History</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h2>Maintenance History</h2>

  <?php if ($result->num_rows > 0): ?>
    <table border="1" cellpadding="8">
      <tr>
        <th>ID</th>
        <th>Vehicle</th>
        <th>Date</th>
        <th>Description</th>
        <th>Cost</th>
      </tr>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= $row['registration_number'] ?></td>
          <td><?= $row['maintenance_date'] ?></td>
          <td><?= $row['description'] ?></td>
          <td><?= $row['cost'] ?></td>
        </tr>
      <?php endwhile; ?>
    </table>
  <?php else: ?>
    <p>No maintenance records found.</p>
  <?php endif; ?>

</body>
</html>

<?php $conn->close(); ?>
