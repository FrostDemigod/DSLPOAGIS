<?php
session_start();
require './includes/library.php';
try {
    $pdo = connectDB(); // Use your database connection function
    echo "<p style='color: green;'>✅ Database connected successfully!</p>";
} catch (PDOException $e) {
    die("<p style='color: red;'>❌ Database connection failed: " . $e->getMessage() . "</p>");
}
$pdo = connectDB();

if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$prepopulatedUsername = $_COOKIE['remember_me'] ?? '';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim(htmlspecialchars($_POST['username']));
    $password = $_POST['password'] ?? "";
    $rememberMe = isset($_POST['remember_me']);

    $stmt = $pdo->prepare('SELECT id, password_hash FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$userData || !password_verify($password, $userData['password_hash'])) {
        $errors['login'] = "Invalid username or password.";
    } else {
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $userData['id'];

        if ($rememberMe) {
            setcookie('remember_me', $username, time() + (86400 * 30), "/");
        } else {
            setcookie('remember_me', '', time() - 3600, "/");
        }

        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <form action="login.php" method="post">
        <input type="text" name="username" placeholder="Username" value="<?= htmlspecialchars($prepopulatedUsername) ?>" required>
        <input type="password" name="password" placeholder="Password" required>
        <label><input type="checkbox" name="remember_me"> Remember me</label>
        <button type="submit">Login</button>
        <span class="error"><?= $errors['login'] ?? '' ?></span>
    </form>
</body>
</html>
