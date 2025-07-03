<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "escom_transport");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get user input
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// Look up user by email
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
  $user = $result->fetch_assoc();

  if (password_verify($password, $user['password'])) {
    // Store user info in session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_role'] = $user['role'];

    // Redirect based on role
    switch ($user['role']) {
      case 'staff':
    header("Location: staff_dashboard.php");
    break;
  case 'fleet_controller':
    header("Location: fleet_dashboard.php");
    break;
  case 'manager':
    header("Location: manager_dashboard.php");
    break;
  case 'driver':
    header("Location: driver_dashboard.php");
    break;
  case 'admin':
    header("Location: admin_dashboard.php");
    break;
  default:
    echo "Invalid role.";
    break;

    }
    exit();
  } else {
    echo "Incorrect password.";
  }
} else {
  echo "No user found with this email.";
}

$conn->close();
?>
