<?php
session_start();
if ($_SESSION['user_role'] !== 'admin') {
  die("Access denied. Only admin can reset passwords.");
}
include("menu.php");

$conn = new mysqli("localhost", "root", "", "escom_transport");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = $_POST['email'];
  $new_pass = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

  $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    $stmt->close();
    $update = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
    $update->bind_param("ss", $new_pass, $email);
    $update->execute();
    $success = "Password reset successfully.";
  } else {
    $error = "Email not found.";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Reset Password</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Reset User Password</h2>

<form method="POST" action="reset_password.php">
  <label>Email Address:</label><br>
  <input type="email" name="email" required><br>

  <label>New Password:</label><br>
  <input type="password" name="new_password" required><br><br>

  <button type="submit">Reset Password</button>
</form>

<?php if ($success): ?>
  <p style="color: green;"><?= $success ?></p>
<?php elseif ($error): ?>
  <p style="color: red;"><?= $error ?></p>
<?php endif; ?>

</body>
</html>

<?php $conn->close(); ?>
