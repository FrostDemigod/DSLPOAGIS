<?php
session_start();
require './includes/library.php';

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    // If the user is logged in, redirect them to the main page
    header("Location: ../main.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="styles/main.css">
</head>
<body>
    <header>
        <h1>Welcome to the Website</h1>
    </header>

    <main>
        <?php if (!isset($_SESSION['username'])): ?>
            <p>You are not logged in. <a href="login.php">Login</a> or <a href="create-account.php">Create an Account</a>.</p>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; <?= date("Y") ?> My Website</p>
    </footer>
</body>
</html>
