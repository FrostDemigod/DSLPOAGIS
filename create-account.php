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

        // Update the query to insert only the columns you need
        $query = "INSERT INTO users (gender, username, email, password_hash) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$gender, $username, $email, $hashedPassword]);

        // Redirect to login.php instead of createaccount.php
        header("Location: ../login.html");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/05ad49203b.js" crossorigin="anonymous"></script>
    <title>Create an Account</title>
    <link rel="stylesheet" href="styles/main.css">
  </head>
  <body>
    <header>
      <h1>Create An Account</h1>
    </header>
    <main>
      <form action="create-account.php" method="post">
        <fieldset>
          <legend>Account Information</legend>
          <div>
            <label for="gender">Gender:</label>
            <select name="gender" id="gender" required>
              <option value="">Please Choose One</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
              <option value="gnc">Gender Queer/Non-Conforming</option>
              <option value="notsay">Prefer not to say</option>
            </select>
            <span class="error"><?= $errors['gender'] ?? '' ?></span>
          </div>
          <div>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <span class="error"><?= $errors['username'] ?? '' ?></span>
          </div>
          <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <span class="error"><?= $errors['email'] ?? '' ?></span>
          </div>
          <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <span class="error"><?= $errors['password'] ?? '' ?></span>
          </div>
          <div>
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            <span class="error"><?= $errors['match'] ?? '' ?></span>
          </div>
        </fieldset>
        <input type="submit" value="Create Account">
      </form>
    </main>
    <footer>
      <nav>
        <ul>
          <li><a href="login.html"><i class="fa-solid fa-right-to-bracket"></i><span>Login</span></a></li>
          <li><a><i class="fa-regular fa-circle-question"></i><span>Help</span></a></li>
          <li><a href="main.html"><i class="fa-solid fa-house"></i><span>Main Page</span></a></li>
        </ul>
      </nav>
    </footer>
  </body>
</html>
