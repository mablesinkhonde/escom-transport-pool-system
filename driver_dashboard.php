<?php
session_start();
if ($_SESSION['user_role'] !== 'driver') {
  die("Access denied.");
}

include("header.php");

// Connect to database
$conn = new mysqli("localhost", "root", "", "escom_transport");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get this logged-in driver's user_id
$driver_user_id = $_SESSION['user_id'];

// Get the driver's id from drivers table
$driver_id = null;
$stmt = $conn->prepare("SELECT id FROM drivers WHERE user_id = ?");
$stmt->bind_param("i", $driver_user_id);
$stmt->execute();
$stmt->bind_result($driver_id);
$stmt->fetch();
$stmt->close();

if (!$driver_id) {
  die("Driver profile not found.");
}

// Get assigned transport requests for this driver
$sql = "
  SELECT r.id, v.registration_number, r.request_date, r.status
  FROM transport_requests r
  JOIN vehicles v ON r.vehicle_id = v.id
  WHERE r.driver_id = ?
  ORDER BY r.request_date DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $driver_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Driver Dashboard</title>
  <link rel="stylesheet" href="style.css">
  <a href="return_vehicle.php"><button>🚗 Return Vehicle</button></a>

</head>
<body>

<h2>Driver Dashboard</h2>

<?php if ($result->num_rows > 0): ?>
  <table border="1" cellpadding="10">
    <tr>
      <th>Request ID</th>
      <th>Vehicle</th>
      <th>Request Date</th>
      <th>Status</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= $row['id'] ?></td>
      <td><?= $row['registration_number'] ?></td>
      <td><?= $row['request_date'] ?></td>
      <td><?= ucfirst($row['status']) ?></td>
    </tr>
    <?php endwhile; ?>
  </table>
<?php else: ?>
  <p>No transport requests assigned to you yet.</p>
<?php endif; ?>

</body>
</html>

<?php
$conn->close();
?>

