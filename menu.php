<?php
if (!isset($_SESSION)) {
    session_start();
}
?>

<hr>
<nav>
  <strong>Logged in as: <?= $_SESSION['user_name'] ?> (<?= $_SESSION['user_role'] ?>)</strong> |
  <a href="logout.php">Logout</a>
  <br><br>

  <?php if ($_SESSION['user_role'] === 'staff'): ?>
    <a href="staff_dashboard.php">🏠 Dashboard</a> |
    <a href="request_form.php">📝 Make Request</a> |
    <a href="view_requests.php">📄 View My Requests</a>

  <?php elseif ($_SESSION['user_role'] === 'fleet_controller'): ?>
    <a href="fleet_dashboard.php">🏠 Dashboard</a> |
    <a href="pending_requests.php">🗂️ Pending Requests</a> |
    <a href="log_fuel.php">⛽ Log Fuel</a> |
    <a href="fuel_history.php">📊 Fuel History</a> |
    <a href="log_maintenance.php">🛠️ Log Maintenance</a> |
    <a href="maintenance_history.php">🧾 Maintenance History</a>

  <?php elseif ($_SESSION['user_role'] === 'manager'): ?>
    <a href="manager_dashboard.php">🏠 Dashboard</a>

  <?php elseif ($_SESSION['user_role'] === 'driver'): ?>
    <a href="driver_dashboard.php">🏠 Dashboard</a> |
    <a href="log_fuel.php">⛽ Log Fuel</a> |
    <a href="fuel_history.php">📊 Fuel History</a>
  <?php endif; ?>
</nav>
<hr>
