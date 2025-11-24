<?php
include 'auth_check.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>User Dashboard</title>
</head>
<body>
  <h1>Welcome, <?php echo htmlspecialchars($_SESSION['fullname']); ?>!</h1>
  <p>Your role: <?php echo htmlspecialchars($_SESSION['role']); ?></p>
  <a href="logout.php">Logout</a>
</body>
</html>
