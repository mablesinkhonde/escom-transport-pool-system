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

$vehicles = $conn->query("SELECT id, registration_number FROM vehicles");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Log Maintenance</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h2>Log Vehicle Maintenance</h2>

  <form method="POST" action="submit_maintenance.php">
    <label>Select Vehicle:</label><br>
    <select name="vehicle_id" required>
      <option value="">--Choose Vehicle--</option>
      <?php while ($v = $vehicles->fetch_assoc()): ?>
        <option value="<?= $v['id'] ?>"><?= $v['registration_number'] ?></option>
      <?php endwhile; ?>
    </select><br><br>

    <label>Date:</label><br>
    <input type="date" name="maintenance_date" required><br><br>

    <label>Description:</label><br>
    <textarea name="description" rows="4" cols="40" required></textarea><br><br>

    <label>Cost:</label><br>
    <input type="number" name="cost" step="0.01" required><br><br>

    <button type="submit">Log Maintenance</button>
  </form>
</body>
</html>

<?php $conn->close(); ?>
