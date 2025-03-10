<?php
session_start(); // Start the session

require './includes/library.php';

// Assume you have the item ID passed as a parameter in the URL (e.g., view-item.php?id=1)
$itemId = $_GET['id'] ?? null;

if (!$itemId) {
    // Redirect or handle the case where no item ID is provided
    header("Location: index.php");
    exit();
}

$userid = $_SESSION['user_id'];
$pdo = connectDB();

// Check if the item is public or if the user is the owner
$check = "SELECT user_id, publicity FROM 3420_assg_lists WHERE list_id = ?";
$checkownership = $pdo->prepare($check);
$checkownership->execute([$itemId]);
$result = $checkownership->fetch(PDO::FETCH_ASSOC);

// If the item is private and the user is not the owner, or if the item is not found, redirect to index.php
if (!$result || ($result['publicity'] === 'Private' && $result['user_id'] != $userid)) {
    header("Location: index.php");
    exit();
}

// Fetch the data if the item is public or if the user is the owner
$stmt = $pdo->prepare("SELECT * FROM 3420_assg_lists WHERE list_id = ?");
$stmt->execute([$itemId]);
$item = $stmt->fetch();

// Check if the item is not found
if (!$item) {
    // Redirect or handle the case where the item is not found
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/05ad49203b.js" crossorigin="anonymous"></script>
    <title>View Item</title>
    <!-- include javascript and css-->
    <link rel="stylesheet" href="styles/main.css">
    <script defer src="js/scripts.js"></script>
  </head>
  <body>
    <header>
      <!--This will be the main heading of the page so users know what page they're on-->
      <h1>View Item</h1>
      <?php include './includes/nav.php' ?>
    </header>
    <main>
      <!-- Display item content using the fetched data -->
      <form id="view-form" method="post" action="">
        <fieldset>
          <legend>List Info</legend>
          <div>
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?php echo $item["title"]; ?>" readonly>
          </div>
          <div>
            <label>Parent List:</label>
            <a href="#">Places I want to go</a>
          </div>
          <?php if (isset($item['completion_date'])): ?>
            <div>
              <?php
                  if (isset($item['completion_date'])) {
                  echo "Completion Date: " . $item['completion_date'];
                  }
              ?>
            </div>
          <?php endif; ?>
        </fieldset>
        <fieldset>
          <legend>Description</legend>
          <div>
            <label for="description">Description:</label>
            <p><?php echo $item['description']; ?></p>
          </div>
          <!-- Other HTML elements using data from $item -->
          <div>
            <?php if (isset($item['image_url'])): ?>
              <label>Validation for the List:</label>
              <img src="<?php echo "/~$direx[2]/www_data/" . $item['image_url']; ?>" height="300">
            <?php endif; ?>
          </div>
        </fieldset>
      </form>
    </main>
    <?php include './includes/footer.php' ?>
  </body>
</html>