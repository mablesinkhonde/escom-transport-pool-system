<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "escom_transport");

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get values from form
$name = $_POST['name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encrypt password
$role = $_POST['role'];

// Insert into users table
$sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $name, $email, $password, $role);

if ($stmt->execute()) {
  $user_id = $stmt->insert_id;

  // If role is driver, also insert into drivers table
  if ($role == 'driver') {
    $license_number = $_POST['license_number'];
    $phone = $_POST['phone'];

    $sql_driver = "INSERT INTO drivers (user_id, license_number, phone) VALUES (?, ?, ?)";
    $stmt_driver = $conn->prepare($sql_driver);
    $stmt_driver->bind_param("iss", $user_id, $license_number, $phone);
    $stmt_driver->execute();
  }

  echo "User registered successfully!";
} else {
  echo "Error: " . $stmt->error;
}

$conn->close();
?>
