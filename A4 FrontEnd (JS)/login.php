<?php
session_start();
require './includes/library.php';

// Establish the database connection
$pdo = connectDB();  // Ensure this is done before any queries are executed

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim(htmlspecialchars($_POST['username']));
    $password = $_POST['password'] ?? "";
    $rememberMe = isset($_POST['remember_me']);

    // Query the database for user info
    $stmt = $pdo->prepare('SELECT id, password_hash FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the user exists and if the password is correct
    if (!$userData || !password_verify($password, $userData['password_hash'])) {
        // Set error message and redirect to login.html
        $errorMessage = urlencode("Invalid username or password.");
        header("Location: ../login.html?error=$errorMessage");
        exit();
    } else {
        // Successful login, set session variables
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $userData['id'];

        // Handle the "Remember Me" functionality
        if ($rememberMe) {
            setcookie('remember_me', $username, time() + (86400 * 30), "/");
        } else {
            setcookie('remember_me', '', time() - 3600, "/");
        }

        // Redirect to the index page after successful login
        header("Location: index.php");
        exit();
    }
}
?>
