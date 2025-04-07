<?php
// Start session
session_start();
require './includes/library.php';
$pdo = connectDB();

// Initialize errors array
$errors = [];

// Capture form data
$gender = $_POST['gender'] ?? "";
$username = $_POST['username'] ?? "";
$email = $_POST['email'] ?? "";
$password = $_POST['password'] ?? "";
$confirmPassword = $_POST['confirm_password'] ?? "";

// Process form
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize inputs
    $gender = htmlspecialchars($gender);
    $username = htmlspecialchars($username);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    
    // Validate fields
    if (empty($gender)) $errors['gender'] = "Gender is required.";
    if (empty($username)) $errors['username'] = "Username is required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = "Invalid email format.";
    if (empty($password)) $errors['password'] = "Password is required.";
    if ($password !== $confirmPassword) $errors['match'] = "Passwords do not match.";

    // Check if username exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->rowCount() > 0) {
        $errors['username'] = "Username is already taken.";
    }

    // If no errors, insert into database
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // ✅ Properly hash password

        $query = "INSERT INTO users (gender, username, email, password_hash) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$gender, $username, $email, $hashedPassword]);

        // Redirect to login.php
        header("Location: ../login.html");
        exit();
    }
}
