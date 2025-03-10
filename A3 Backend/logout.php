
<?php
// Start or resume the current session
session_start();

// Destroy the session
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/05ad49203b.js" crossorigin="anonymous"></script>
    <title>Delete Account</title>
    <!-- include CSS-->
    <link rel="stylesheet" href="styles/main.css">
    <script defer src="js/scripts.js"></script>
</head>
<body>

  <header>
    <!-- This will be the main heading of the page so users know what page they're on -->
    <h1>You Have Successfully Logged Out</h1>
    <?php include './includes/nav.php'; ?>
  </header>

  <main>
      <p>Thank you for using our site. You have successfully logged out!</p>
  </main>

  <?php include './includes/footer.php'; ?>
</body>
</html>
