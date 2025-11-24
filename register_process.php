<?php
session_start();

// Include your database connection
include 'includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sanitize input
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate inputs
    if (empty($fullname) || empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION['error'] = "Please fill in all fields.";
        header("Location: register.html");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format.";
        header("Location: register.html");
        exit();
    }

    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: register.html");
        exit();
    }

    // Check if user already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['error'] = "Email is already registered.";
        $stmt->close();
        header("Location: register.html");
        exit();
    }
    $stmt->close();

    // Hash password securely
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $fullname, $email, $hashed_password);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Registration successful. You can now login.";
        $stmt->close();
        $conn->close();
        header("Location: login.html");
        exit();
    } else {
        $_SESSION['error'] = "Something went wrong. Please try again.";
        $stmt->close();
        $conn->close();
        header("Location: register.html");
        exit();
    }
} else {
    // Redirect if accessed directly without POST data
    header("Location: register.html");
    exit();
}
