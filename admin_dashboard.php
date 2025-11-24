<?php
include 'auth_check.php';

if ($_SESSION['role'] !== 'admin') {
    // Redirect users without admin rights
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Admin Dashboard</title>
</head>
<body>
  <h1>Admin Dashboard</h1>
  <p>Welcome, <?php echo htmlspecialchars($_SESSION['fullname']); ?> (Admin)</p>
  <a href="logout.php">Logout</a>
</body>
</html>
