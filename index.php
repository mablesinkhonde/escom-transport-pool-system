<?php
session_start();

if (isset($_SESSION['user_role'])) {
  switch ($_SESSION['user_role']) {
    case 'staff':
      header("Location: staff_dashboard.php"); exit();
    case 'fleet_controller':
      header("Location: fleet_dashboard.php"); exit();
    case 'manager':
      header("Location: manager_dashboard.php"); exit();
    case 'driver':
      header("Location: driver_dashboard.php"); exit();
    case 'admin':
      header("Location: admin_dashboard.php"); exit();
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Welcome to ESCOM Transport System</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      background-color: #2e8b57; /* ESCOM green */
      font-family: Arial, sans-serif;
      color: white;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
      text-align: center;
    }

    h1 {
      font-size: 36px;
      margin-bottom: 20px;
    }

    p {
      font-size: 18px;
      margin-bottom: 30px;
      max-width: 550px;
    }

    .buttons a {
      text-decoration: none;
      padding: 12px 25px;
      margin: 10px;
      border-radius: 5px;
      background-color: #fff;
      color: #2e8b57;
      font-weight: bold;
      font-size: 16px;
      transition: background 0.3s, color 0.3s;
      display: inline-block;
    }

    .buttons a:hover {
      background-color: #228b22;
      color: white;
    }

    .footer {
      position: absolute;
      bottom: 10px;
      font-size: 14px;
      color: #ccc;
    }
  </style>
</head>
<body>

  <h1>🚗 Welcome to ESCOM Transport Pool Management System</h1>
  <p>
    Streamline your transport operations with our digital platform.<br>
    Request vehicles, track usage, monitor maintenance, and more — all in one place.
  </p>

  <div class="buttons">
    <a href="login.html">🔐 Login</a>
    <a href="register.php">📝 Register</a>
  </div>

  <div class="footer">
    &copy; 2025 ESCOM Transport Department
  </div>

</body>
</html>

