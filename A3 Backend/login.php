<!--PHP section-->
<?php
require './includes/library.php';
session_start(); // Start the session

// Check if the user is already logged in, if so, redirect to the Main Page
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Check if a cookie exists, if yes, pre-populate the username box
if (isset($_COOKIE['remember_me'])) {
    $prepopulatedUsername = $_COOKIE['remember_me'];
} else {
    $prepopulatedUsername = '';
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $pdo = connectDB();
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Sanitize all text inputs
  $username= htmlspecialchars($username);
  $password= htmlspecialchars($password);

  $rememberMe = isset($_POST['remember_me']) ? $_POST['remember_me'] : false;

  // Fetch user data from the database
  $stmt = $pdo->prepare("SELECT id, password FROM 3420_assg_users WHERE username = ?");
  $stmt->execute([$username]);
  $userData = $stmt->fetch();

  // Verify password
  if ($userData && password_verify($password, $userData['password'])) {
      // Password is correct, start a new session
      session_start();

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
    <script
      src="https://kit.fontawesome.com/05ad49203b.js"
      crossorigin="anonymous"
    ></script>
    <title>Login</title>
    <!-- include javascript and css-->
    <link rel="stylesheet" href="styles/main.css">
    <script defer src="js/scripts.js"></script>
  </head>
  <body>
    <header>
      <!--This will be the main heading of the page so users know what page they're on-->
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