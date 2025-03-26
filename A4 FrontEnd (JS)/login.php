<?php
$file = __DIR__ . "/includes/library.php";

if (!file_exists($file)) {
    die("Error: library.php file is missing at $file");
}

require_once $file;
session_start(); // Start the session

// Check if the user is already logged in, if so, redirect to the Main Page
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Check if a cookie exists, if yes, pre-populate the username box
$prepopulatedUsername = isset($_COOKIE['remember_me']) ? $_COOKIE['remember_me'] : '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pdo = connectDB();
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $rememberMe = isset($_POST['remember_me']) ? $_POST['remember_me'] : false;

    // Fetch user data from the database
    $stmt = $pdo->prepare('SELECT id, password FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $userData = $stmt->fetch();

    // Verify password
    if ($userData && password_verify($password, $userData['password'])) {
        // Store user data in session variables
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $userData['id'];

        // Create a cookie if "remember me" is checked
        if ($rememberMe) {
            setcookie('remember_me', $username, time() + (86400 * 30), "/"); // 30 days
        } else {
            // If "remember me" is not checked, clear the cookie
            setcookie('remember_me', '', time() - 3600, "/");
        }

        // Table checking logic - display tables from the database
        $dbName = $pdo->query('SELECT current_database()')->fetchColumn();
        echo "Connected to database: $dbName<br>";

        $schema = $pdo->query("SELECT current_schema()")->fetchColumn();
        echo "Current Schema: $schema<br>";

        // Fetch tables in the schema
        $query = $pdo->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'");
        $tables = $query->fetchAll(PDO::FETCH_COLUMN);

        if ($tables) {
            echo "<h2>Tables in the database:</h2><ul>";
            foreach ($tables as $table) {
                echo "<li>$table</li>";
            }
            echo "</ul>";
        } else {
            echo "No tables found in the database.";
        }

        // Redirect to the Main Page
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
    <script src="https://kit.fontawesome.com/05ad49203b.js" crossorigin="anonymous"></script>
    <title>Login</title>
    <link rel="stylesheet" href="styles/main.css">
    <script defer src="js/scripts.js"></script>
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
        <p>No account? You can <a href="register.php">sign up now!</a></p>
        <form id="login-form" action="login.php" method="post" class="login">
            <fieldset>
                <legend>Login Information</legend>
                <div>
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" maxlength="32" placeholder="ex. JohnDoe123" required value="<?php echo htmlspecialchars($prepopulatedUsername); ?>">
                </div>
                <div>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                    <input type="checkbox" id="showPassword" onclick="Toggle()">
                    <label for="showPassword">Show Password</label>
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

<script>
function Toggle() {
    let temp = document.getElementById("password");
    temp.type = temp.type === "password" ? "text" : "password";
}
</script>
