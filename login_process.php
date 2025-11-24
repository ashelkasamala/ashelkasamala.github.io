<?php
session_start();
include 'includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, fullname, email, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // Verify password hash
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            if ($user['role'] === 'admin') {
                header("Location: admin_dashboard.php");
                exit();
            } else {
                header("Location: dashboard.php");
                exit();
            }
        } else {
            // Invalid password
            $_SESSION['error'] = "Invalid email or password.";
            header("Location: login.html");
            exit();
        }
    } else {
        // No user found
        $_SESSION['error'] = "Invalid email or password.";
        header("Location: login.html");
        exit();
    }
} else {
    header("Location: login.html");
    exit();
}
?>
