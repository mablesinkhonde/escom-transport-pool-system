<?php
session_start();
include("menu.php");

if ($_SESSION['user_role'] !== 'fleet_controller' && $_SESSION['user_role'] !== 'driver') {
  die("Access denied.");
}

$conn = new mysqli("localhost", "root", "", "escom_transport");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Fetch fuel logs with vehicle info
$sql = "SELECT f.id, v.registration_number, f.date, f.litres, f.cost
        FROM fuel_logs f
        JOIN vehicles v ON f.vehicle_id = v.id
        ORDER BY f.date DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Fuel Usage History</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h2>Fuel Usage History</h2>

  <?php if ($result->num_rows > 0): ?>
    <table border="1" cellpadding="8">
      <tr>
        <th>ID</th>
        <th>Vehicle</th>
        <th>Date</th>
        <th>Litres</th>
        <th>Cost</th>
      </tr>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['registration_number']) ?></td>
          <td><?= htmlspecialchars($row['date']) ?></td>
          <td><?= htmlspecialchars($row['litres']) ?></td>
          <td><?= htmlspecialchars($row['cost']) ?></td>
        </tr>
      <?php endwhile; ?>
    </table>
  <?php else: ?>
    <p>No fuel logs found.</p>
  <?php endif; ?>

</body>
</html>

<?php $conn->close(); ?>
