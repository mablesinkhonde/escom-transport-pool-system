<?php
session_start();
if ($_SESSION['user_role'] !== 'admin') {
  die("Access denied.");
}
include("header.php");

// DB Connection
$conn = new mysqli("localhost", "root", "", "escom_transport");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Fetch all maintenance logs with vehicle info
$sql = "
  SELECT mr.id, mr.maintenance_date, mr.description, mr.cost, 
         v.registration_number
  FROM maintenance_records mr
  JOIN vehicles v ON mr.vehicle_id = v.id
  ORDER BY mr.maintenance_date DESC
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
  <title>All Maintenance Logs</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      font-family: Arial;
      background-color: #f0f0f0;
      padding: 20px;
    }

    h2 {
      color: #006400;
    }

    .back-btn {
      display: inline-block;
      margin: 15px 0;
      padding: 8px 16px;
      background-color: #006400;
      color: white;
      text-decoration: none;
      border-radius: 5px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background-color: white;
    }

    th, td {
      padding: 10px;
      border: 1px solid #ccc;
    }

    th {
      background-color: #f2f2f2;
    }
  </style>
</head>
<body>

<a class="back-btn" href="admin_dashboard.php">← Back to Dashboard</a>

<h2>🛠️ All Maintenance Records</h2>

<?php if ($result->num_rows > 0): ?>
  <table>
    <tr>
      <th>ID</th>
      <th>Vehicle</th>
      <th>Date</th>
      <th>Description</th>
      <th>Cost (MK)</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['registration_number'] ?></td>
        <td><?= $row['maintenance_date'] ?></td>
        <td><?= htmlspecialchars($row['description']) ?></td>
        <td><?= number_format($row['cost'], 2) ?></td>
      </tr>
    <?php endwhile; ?>
  </table>
<?php else: ?>
  <p>No maintenance records found.</p>
<?php endif; ?>

</body>
</html>

<?php $conn->close(); ?>
