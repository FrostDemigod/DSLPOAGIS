<?php
session_start();

require_once "includes/library.php";
$pdo = connectDB();

$search = $_POST['search'] ?? "";
$errors = array();
$userLists = array();

if(isset($_POST['submit'])){

  $search = strip_tags($search);
  if($search == ""){
    $errors['search'] = true;
  }

  if (!count($errors)){
    $pdo = connectDB();

    // Use wildcards for a partial match
    $searchTerm = "%$search%";
    $query = "SELECT * FROM 3420_assg_lists WHERE title LIKE ? ";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$searchTerm]);

    // Fetch the user's newly created list and include it in the search results
    $userId = $_SESSION['user_id'] ?? null;
    if ($userId) {
        $stmtUserList = $pdo->prepare("SELECT * FROM 3420_assg_lists WHERE user_id = ? AND title LIKE ?");
        $stmtUserList->execute([$userId, $searchTerm]);
        $userList = $stmtUserList->fetch(PDO::FETCH_ASSOC);
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/05ad49203b.js" crossorigin="anonymous"></script>
    <title>Search</title>
    <!-- include javascript and css-->
    <link rel="stylesheet" href="styles/main.css">
    <script defer src="js/scripts.js"></script>
</head>

<body>
    <header>
        <!--This will be the main heading of the page so users know what page they're on-->
        <h1>Search</h1>

        <?php include './includes/nav.php' ?>
    </header>
    <div>
        <form id="search-form" class="search-form" method="post">
            <input
                type="text"
                class="search_input"
                placeholder="Search for lists in the form"
                name="search"
                value="<?=$search?>"
            />
            <span class="<?= isset($errors['search']) ? "" : "hidden"; ?>">
                You must enter a search
            </span>
            <!--Added name attribute for the search input ^-->
            <button type="submit" name="submit" class="search_button">
               <i class="fa-solid fa-magnifying-glass"></i>
            </button>
            <button type="button" class="feelin_lucky">Feelin Lucky?</button>
        </form>
    </div>

    <div>

    <?php if (isset($_POST['submit']) && !count($errors)) : ?>
      <h2>Search Results for <?= $search; ?></h2>
      <?php if ($stmt->rowCount() <= 0) : ?>
        <p>No Results found</p>
      <?php else : ?>
        <ul>
        <?php foreach ($stmt as $row) : ?>
            <li><a href="view-item.php?id=<?= $row['list_id'] ?>"><?= $row['title'] ?></a></li>
          <?php endforeach ?>
        </ul>
      <?php endif ?>
    <?php endif ?>
    </div>

  <?php include './includes/footer.php' ?>
  </body>
</html>
