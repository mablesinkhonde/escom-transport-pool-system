<?php
session_start();
if ($_SESSION['user_role'] !== 'admin') {
  die("Access denied.");
}
include("menu.php");

// Database connection
$conn = new mysqli("localhost", "root", "", "escom_transport");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Fetch all users
$result = $conn->query("SELECT id, name, email, role FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
  <title>All Registered Users</title>
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
      text-align: left;
      border: 1px solid #ddd;
    }

    th {
      background-color: #f2f2f2;
    }
  </style>
</head>
<body>

<a class="back-btn" href="admin_dashboard.php">← Back to Dashboard</a>

<h2>👥 All Registered Users</h2>

<?php if ($result->num_rows > 0): ?>
  <table>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Email</th>
      <th>Role</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><?= ucfirst($row['role']) ?></td>
      </tr>
    <?php endwhile; ?>
  </table>
<?php else: ?>
  <p>No users found.</p>
<?php endif; ?>

</body>
</html>

<?php $conn->close(); ?>

