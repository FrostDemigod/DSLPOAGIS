<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script
      src="https://kit.fontawesome.com/05ad49203b.js"
      crossorigin="anonymous"
    ></script>
    <title>Forgot Password</title>
    <link rel="stylesheet" href="styles/main.css" />
    <script defer src="js/scripts.js"></script>
  </head>
  <body>
    <header>
      <h1>Forgot Password</h1>
      <?php include './includes/nav.php'; ?>
    </header>

    <main>
      <?php if (isset($_GET['error'])): ?>
        <p style="color: red;"><?php echo htmlspecialchars($_GET['error']); ?></p>
      <?php endif; ?>

      <?php if (isset($_GET['reset']) && $_GET['reset'] === 'success'): ?>
        <p style="color: green;">Password reset successfully! You can now <a href="login.php">login</a>.</p>
      <?php endif; ?>

      <form id="forgot-form" action="forgot_process.php" method="post">
        <div>
          <label for="usernameOrEmail">Username or Email:</label>
          <input
            type="text"
            id="usernameOrEmail"
            name="usernameOrEmail"
            value="<?php echo isset($_POST['usernameOrEmail']) ? htmlspecialchars($_POST['usernameOrEmail']) : ''; ?>"
            required
          />
        </div>

        <div>
          <label for="newPassword">New Password:</label>
          <input
            type="password"
            id="newPassword"
            name="newPassword"
            required
          />
        </div>

        <input type="submit" value="Reset Password" />
      </form>
    </main>

    <?php include './includes/footer.php'; ?>
  </body>
</html>