<?php
session_start();
require './includes/library.php';

if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

if (isset($_COOKIE['remember_me'])) {
    $prepopulatedUsername = $_COOKIE['remember_me'];
} else {
    $prepopulatedUsername = '';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pdo = connectDB();
    $username = $_POST['username'];
    $password = $_POST['password'];

    $username = htmlspecialchars($username);
    $password = htmlspecialchars($password);

    $rememberMe = isset($_POST['remember_me']) ? $_POST['remember_me'] : false;

    $stmt = $pdo->prepare("SELECT id, password FROM 3420_assg_users WHERE username = ?");
    $stmt->execute([$username]);
    $userData = $stmt->fetch();

    if ($userData && password_verify($password, $userData['password'])) {
        session_start();

        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $userData['id'];

        if ($rememberMe) {
            setcookie('remember_me', $username, time() + (86400 * 30), "/"); 
        } else {
            setcookie('remember_me', '', time() - 3600, "/");
        }

        header("Location: index.php");
        exit();
    } else {
        $error_message = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Include external CSS and JS -->
    <link rel="stylesheet" href="styles/main.css">
    <script defer src="scripts/scripts.js"></script>
</head>
<body>
    <header>
        <h1>Login</h1>
        <?php include './includes/nav.php' ?>
    </header>
    <main>
        <?php
        if (isset($error_message)) {
            echo "<p>$error_message</p>";
        }
        ?>
        No account? You can <a href="register.php">sign up now!</a>
        <form action="login.php" method="post" class="login">
            <fieldset>
                <legend>Login Information</legend>
                <div>
                    <label for="username">Username:</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        maxlength="32"
                        placeholder="ex. JohnDoe123"
                        value="<?php echo $prepopulatedUsername; ?>"
                        required
                    >
                </div>
                <div>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div>
                    <label for="remember_me">Remember me:</label>
                    <input type="checkbox" id="remember_me" name="remember_me">
                </div>
            </fieldset>
            <div>
                <a href="forgot.php">Forgot Password?</a>
            </div>
            <input type="submit" value="Login">
        </form>
    </main>
    <?php include './includes/footer.php' ?>
</body>
</html>