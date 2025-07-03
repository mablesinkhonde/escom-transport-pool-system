<?php
session_start();
if ($_SESSION['user_role'] !== 'driver') {
  die("Access denied.");
}

include("header.php");

$conn = new mysqli("localhost", "root", "", "escom_transport");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$driver_user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT id FROM drivers WHERE user_id = ?");
$stmt->bind_param("i", $driver_user_id);
$stmt->execute();
$stmt->bind_result($driver_id);
$stmt->fetch();
$stmt->close();

if (!$driver_id) {
  die("Driver profile not found.");
}

$result = $conn->query("
  SELECT r.id, v.registration_number, r.request_date
  FROM transport_requests r
  JOIN vehicles v ON r.vehicle_id = v.id
  WHERE r.driver_id = $driver_id AND r.status = 'approved' AND r.return_time IS NULL
");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Return Vehicle</title>
</head>
<body>

<h2>Return Vehicle</h2>

<?php if ($result->num_rows > 0): ?>
  <form method="post" action="return_vehicle_action.php">
    <label for="request_id">Select Trip:</label>
    <select name="request_id" required>
      <option value="">-- Select Trip --</option>
      <?php while ($row = $result->fetch_assoc()): ?>
        <option value="<?= $row['id'] ?>">
          <?= $row['registration_number'] ?> (Requested on <?= $row['request_date'] ?>)
        </option>
      <?php endwhile; ?>
    </select><br><br>

    <label for="remarks">Remarks:</label><br>
    <textarea name="remarks" rows="4" cols="50" placeholder="Condition, fuel left, notes..."></textarea><br><br>

    <button type="submit">Submit Return</button>
  </form>
<?php else: ?>
  <p>No active trips to return.</p>
<?php endif; ?>

</body>
</html>

<?php $conn->close(); ?>
