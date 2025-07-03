<?php
if (!isset($_SESSION))  session_start();

/* -------- role‑based navigation -------- */
function nav_links(string $role): string {
  return match ($role) {
    'admin' => '
      <a href="admin_dashboard.php">Dashboard</a>
      <a href="view_users.php">Users</a>
      <a href="admin_view_requests.php">Requests</a>
      <a href="admin_maintenance_log.php">Maintenance</a>
      <a href="admin_fuel_log.php">Fuel</a>
    ',
    'fleet_controller' => '
      <a href="fleet_dashboard.php">Dashboard</a>
      <a href="assign.php">Pending Requests</a>
      <a href="log_fuel.php">Log Fuel</a>
      <a href="fuel_history.php">Fuel History</a>
      <a href="log_maintenance.php">Log Maintenance</a>
      <a href="maintenance_history.php">Maintenance History</a>
    ',
    'manager' => '
      <a href="manager_dashboard.php">Dashboard</a>
      <a href="approve_requests.php">Approve Requests</a>
    ',
    'staff' => '
      <a href="staff_dashboard.php">Dashboard</a>
      <a href="request_transport.php">Make Request</a>
      <a href="view_requests.php">My Requests</a>
    ',
    'driver' => '
      <a href="driver_dashboard.php">Dashboard</a>
      <a href="return_vehicle.php">Return Vehicle</a>
      <a href="log_fuel.php">Log Fuel</a>
      <a href="fuel_history.php">Fuel History</a>
    ',
    default => ''
  };
}
?>
<!-- Top bar -->
<style>
  .topbar      {background:#006400;color:#fff;padding:10px 20px;display:flex;align-items:center;justify-content:space-between;font-family:Arial}
  .topbar a    {color:yellow;margin:0 8px;text-decoration:none;font-weight:bold}
  .nav-links a {color:white;margin:0 8px;text-decoration:none}
  .nav-links a:hover{color:#FFD700}
</style>

<div class="topbar">
  <div><strong>ESCOM Transport Pool Management System</strong></div>
  <div class="nav-links">
    <?= nav_links($_SESSION['user_role'] ?? '') ?>
    <a href="logout.php">Logout</a>
  </div>
</div>

