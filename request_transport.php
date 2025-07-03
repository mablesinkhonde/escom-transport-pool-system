<?php
session_start();
include("menu.php");

// Only staff should access this page
if ($_SESSION['user_role'] !== 'staff') {
  die("Access denied.");
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Transport Request Form</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h2>Submit Transport Request</h2>

  <form action="submit_request.php" method="POST">
    <label>Request Date:</label><br>
    <input type="date" name="request_date" required><br><br>

    <button type="submit">Submit Request</button>
  </form>
</body>
</html>
