<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}
$userid = $_SESSION['user_id'];
// declare error array
$errors = array();


// delcare defaults
$title              = $_POST['title'] ?? "";
$description        = $_POST['description'] ?? "";
$public             = $_POST['public_view'] ?? 'Public';



//Include library and connect to DB
require './includes/library.php';

$pdo = connectDB();
if (isset($_POST['submit'])) {
  if (strlen($title) == 0) {
    $errors['title'] = true;
  }
  if (strlen($description) == 0) {
    $errors['description'] = true;
  }

  if (count($errors) === 0) { 
    // Sanitize all text inputs
    $title= htmlspecialchars($title);
    $description= htmlspecialchars($description);
    
    
    $listquery = "INSERT INTO 3420_assg_lists (`user_id`, `title`, `description`, `publicity`) VALUES (?, ?, ?, ?)";
    $list_stmt = $pdo->prepare($listquery);
    $list_stmt->execute([$userid, $title, $description, $public]);
    
    // refresh page
    header("Location: index.php");
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
  <title>Index</title>
  <!-- include javascript and css-->
  <link rel="stylesheet" href="styles/main.css">
  <script defer src="./scripts/main.js"></script>
</head>

<body>
  <header>
    <!--This will be the main heading of the page so users know what page they're on-->
    <h1>Welcome to the Main Page</h1>

    <?php include './includes/nav.php' ?>
  </header>

  <main>
    <h2>My Lists</h2>
    <ul>
    <?php
            // Fetch and display user's lists from the database
            $varpub ="Public";
            $varpriv ="Private";
            $query = "SELECT list_id, user_id, title FROM 3420_assg_lists WHERE publicity = ? OR user_id = ? AND publicity = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$varpub, $userid, $varpriv]);
            $user_lists = $stmt->fetchAll();

            foreach ($user_lists as $list) :
            ?>
      <li><a id="preview" href="#view-item.php?id=<?php echo $list["list_id"]; ?>"><?= $list["title"] ?></a>
      <?php if ($list["user_id"] == $userid) { ?> 
        <a href="edit-item.php?id=<?php echo $list["list_id"]; ?>"><i class="fa-solid fa-pen-to-square"></i></a>
          <a href="delete-item.php?id=<?php echo $list["list_id"]; ?>"><i class="fa-solid fa-trash"></i></a>
        </button>
      <?php } ?>

             <!-- Modal -->
              <div class="modal fade" id="myModal" role="dialog">
              <div class="modal-dialog">
        
            <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Modal Header</h4>
                </div>
                <div class="modal-body">
                  <fieldset>
                    <div>
                      <label for="title">Title:</label>
                      <input type="text" id="title" name="title" value="<?php echo $item["title"]; ?>" readonly>
                    </div>
                    <div>
                      <label for="title">Description:</label>
                      <input type="text" id="description" name="description" value="<?php echo $item["description"]; ?>" readonly>
                    </div>
                    <div>
                      <label for="rating">User Score: <?php echo $item["rating"]?></label>
                      <input type="range" id="rating" name="rating" min="1" max="100" value="<?php echo $item["rating"]; ?>" disabled>
                      <output for="rating"></output>
                    </div>
                    <div>
                    <label for="title">Completion Date:</label>
                      <input type="date" value="<?php echo $item["completion_date"]; ?>" disabled>
                      </div>
                  </fieldset>
                  <fieldset>
                    <legend>Description</legend>
                    <div>
                      <label for="details">Details:</label>
                      <p><?php echo $item['details']; ?></p>
                    </div>
                    <!-- Other HTML elements using data from $item -->
                    <div>
                      <?php if (isset($item['image_url'])): ?>
                        <label>Image Proof:</label>
                        <img src="<?php echo "/~$direx[2]/www_data/" . $item['image_url']; ?>" height="300">
                      <?php endif; ?>
                    </div>
                  </fieldset>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div> 
        <?php endforeach; ?>
      </li>
    </ul>
    <button id="copyLinkBtn">Copy Public List Link</button>
    <h2>Add New Entry:</h2>
    <form id="add-list-form" method="post" action="">
    <div>
          <label for="title">Title:</label>
          <input type="text" id="title" name="title" value="<?= $title ?>">
          <span class="error <?= !isset($errors['title']) ? 'hidden' : '' ?>">Please Choose a List Title.</span>
        </div>
        <div>
          <label for="description">Description:</label>
          <textarea id="description" name="description" ><?= $description ?></textarea>
          <span class="error <?= !isset($errors['description']) ? 'hidden' : '' ?>">Please Describe Your List.</span>
        </div>
        <div>
          <label for="public_view">Make List Public?:</label>
          <input type="hidden" id="public_view" name="public_view" value="Private">
          <input type="checkbox" id="public_view" name="public_view" value="Public" checked>
        </div>
      </fieldset>
      <button id="submit" name="submit">Make List</button>
    </form>
  </main>
  <?php include './includes/footer.php' ?>
</body>

</html>