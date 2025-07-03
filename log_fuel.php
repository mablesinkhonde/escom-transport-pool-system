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

// Get list of vehicles
$vehiScles = $conn->query("SELECT id, registration_number FROM vehicles WHERE status = 'available'");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Log Fuel Usage</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h2>Log Fuel Usage</h2>

  <form method="POST" action="submit_fuel_log.php">
    <label>Select Vehicle:</label><br>
    <select name="vehicle_id" required>
      <option value="">--Choose Vehicle--</option>
      <?php while ($v = $vehicles->fetch_assoc()): ?>
        <option value="<?= $v['id'] ?>"><?= $v['registration_number'] ?></option>
      <?php endwhile; ?>
    </select><br><br>

    <label>Date:</label><br>
    <input type="date" name="date" required><br><br>

    <label>Litres Used:</label><br>
    <input type="number" name="litres" step="0.01" required><br><br>

    <label>Total Cost:</label><br>
    <input type="number" name="cost" step="0.01" required><br><br>

    <button type="submit">Log Fuel</button>
  </form>
</body>
</html>

<?php $conn->close(); ?>
