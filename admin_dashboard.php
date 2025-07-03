<?php
session_start();
if ($_SESSION['user_role'] !== 'admin') {
  die("Access denied.");
}

include("header.php");  // This includes the shared menu/header

// ──────────────── Database connection ────────────────
$conn = new mysqli("localhost", "root", "", "escom_transport");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Dashboard statistics
$totalUsers        = $conn->query("SELECT COUNT(*) AS c FROM users")->fetch_assoc()['c'];
$totalVehicles     = $conn->query("SELECT COUNT(*) AS c FROM vehicles")->fetch_assoc()['c'];
$pendingRequests   = $conn->query("SELECT COUNT(*) AS c FROM transport_requests WHERE status='pending'")->fetch_assoc()['c'];
$completedRequests = $conn->query("SELECT COUNT(*) AS c FROM transport_requests WHERE status='returned'")->fetch_assoc()['c'];

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      font-family: Arial;
      background: #f0f0f0;
      padding: 20px;
    }

    .dashboard-title {
      color: #006400;
      margin-top: 20px;
    }

    .summary-box {
      border: 1px solid #ccc;
      border-radius: 10px;
      padding: 20px;
      margin: 15px;
      width: 250px;
      float: left;
      background: #fff;
    }

    .summary-box h3 {
      margin-top: 0;
    }

    .action-button {
      padding: 10px 20px;
      margin: 5px;
      border: none;
      border-radius: 5px;
      font-weight: bold;
      text-decoration: none;
      display: inline-block;
    }

    .green { background: #006400; color: #fff; }
    .forest { background: #228B22; color: #fff; }
    .yellow { background: #FFD700; color: #000; }
    .gray   { background: #A9A9A9; color: #fff; }
    .blue   { background: #4682B4; color: #fff; }
    .logout { background: #ff0000; color: #fff; }
  </style>
</head>
<body>

<h2 class="dashboard-title">🧭 Admin Dashboard Summary</h2>

<div class="summary-box" style="border-left:6px solid green;">
  <h3>Total Users 👤</h3>
  <p><?= $totalUsers ?></p>
</div>

<div class="summary-box" style="border-left:6px solid yellow;">
  <h3>Total Vehicles 🚗</h3>
  <p><?= $totalVehicles ?></p>
</div>

<div class="summary-box" style="border-left:6px solid orange;">
  <h3>Pending Requests ⏳</h3>
  <p><?= $pendingRequests ?></p>
</div>

<div class="summary-box" style="border-left:6px solid gray;">
  <h3>Returned Requests ✅</h3>
  <p><?= $completedRequests ?></p>
</div>

<div style="clear:both"></div>
<hr>

<h3>⚙️ Quick Actions</h3>

<a href="admin_dashboard.php" class="action-button blue">🏠 Dashboard Home</a>
<a href="view_users.php" class="action-button green">👥 View Users</a>
<a href="admin_view_requests.php" class="action-button forest">🚗 View Requests</a>
<a href="admin_maintenance_log.php" class="action-button gray">🛠️ Maintenance Logs</a>
<a href="admin_fuel_log.php" class="action-button yellow">⛽ Fuel Logs</a>

<!-- Logout button at the bottom right -->
<a href="logout.php" class="action-button logout" style="float:right;">🚪 Logout</a>

</body>
</html>
