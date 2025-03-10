<?php
session_start();

require_once "includes/library.php";
$pdo = connectDB();

$search = $_POST['search'] ?? "";
$errors = array();
$userLists = array();
$varpub ="Public";

if(isset($_POST['submit'])){

  $search = strip_tags($search);
  if($search == ""){
    $errors['search'] = true;
  }

  if (!count($errors)){
    $pdo = connectDB();

    // Use wildcards for a partial match
    $searchTerm = "%$search%";
    $query = "SELECT * FROM 3420_assg_lists WHERE `title` LIKE ? AND publicity = ? OR `description` LIKE ? AND publicity = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$searchTerm, $varpub, $searchTerm, $varpub]);
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

            <!-- Random id picker for the feeling lucky function -->
            <?php
            $rand = "SELECT list_id FROM 3420_assg_lists WHERE publicity = ? ORDER BY RAND() LIMIT 1;";
            $randid = $pdo->prepare($rand);
            $randid->execute([$varpub]);
            $listid = $randid->fetch(PDO::FETCH_ASSOC);
            ?>

            <button type="button" class="feelin_lucky"><a class="feelin_lucky" href="view-item.php?id=<?php echo $listid["list_id"]; ?>">Feelin Lucky?</button>
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
