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

$request_id = $_GET['request_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $vehicle_id = $_POST['vehicle_id'];
  $driver_id = $_POST['driver_id'];

  // Update the request with vehicle, driver, and new status
  $sql = "UPDATE transport_requests
          SET vehicle_id = ?, driver_id = ?, status = 'forwarded'
          WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("iii", $vehicle_id, $driver_id, $request_id);

  if ($stmt->execute()) {
    echo "Request assigned and forwarded to manager.";
  } else {
    echo "Error: " . $stmt->error;
  }

  $conn->close();
  exit();
}

// Fetch available vehicles
$vehicles = $conn->query("SELECT id, registration_number FROM vehicles WHERE status = 'available'");

// Fetch available drivers
$drivers = $conn->query("SELECT d.id, u.name FROM drivers d JOIN users u ON d.user_id = u.id WHERE d.status = 'active'");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Assign Request</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h2>Assign Vehicle and Driver (Request #<?= $request_id ?>)</h2>

  <form method="POST">
    <label>Select Vehicle:</label><br>
    <select name="vehicle_id" required>
      <option value="">--Choose Vehicle--</option>
      <?php while ($v = $vehicles->fetch_assoc()): ?>
        <option value="<?= $v['id'] ?>"><?= $v['registration_number'] ?></option>
      <?php endwhile; ?>
    </select><br><br>

    <label>Select Driver:</label><br>
    <select name="driver_id" required>
      <option value="">--Choose Driver--</option>
      <?php while ($d = $drivers->fetch_assoc()): ?>
        <option value="<?= $d['id'] ?>"><?= $d['name'] ?></option>
      <?php endwhile; ?>
    </select><br><br>

    <button type="submit">Assign</button>
  </form>
</body>
</html>
