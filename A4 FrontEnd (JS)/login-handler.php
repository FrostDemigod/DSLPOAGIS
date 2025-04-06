<?php
session_start();
require './includes/library.php';

try {
    $pdo = connectDB();
} catch (PDOException $e) {
    die("❌ Database connection failed: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim(htmlspecialchars($_POST['username']));
    $password = $_POST['password'] ?? '';
    $rememberMe = isset($_POST['remember_me']);

    $stmt = $pdo->prepare('SELECT id, password_hash FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$userData || !password_verify($password, $userData['password_hash'])) {
        // Failed login
        header("Location: login.php?error=1");
        exit();
    }

    // Successful login
    $_SESSION['username'] = $username;
    $_SESSION['user_id'] = $userData['id'];

    if ($rememberMe) {
        setcookie('remember_me', $username, time() + (86400 * 30), "/");
    } else {
        setcookie('remember_me', '', time() - 3600, "/");
    }

    // Redirect to main.html
    header("Location: main.html");
    exit();
}
?>