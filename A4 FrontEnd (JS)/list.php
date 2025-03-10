<?php
//Include library and connect to DB
require './includes/library.php';

$pdo = connectDB();
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
  <script defer src="js/scripts.js"></script>
</head>

<body>
  <header>
    <!--This will be the main heading of the page so users know what page they're on-->
    <h1>Public View</h1>

    <?php include './includes/nav.php' ?>
  </header>

  <main>
    <h2>User Lists</h2>
    <ul>
    <?php
            // Fetch and display user's lists from the database
            $varpub ="Public";
            $query = "SELECT list_id, title FROM 3420_assg_lists WHERE publicity = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$varpub]);
            $user_lists = $stmt->fetchAll();

            foreach ($user_lists as $list) :
            ?>
      <li><a href="view-item.php?id=<?php echo $list["list_id"]; ?>"><?= $list["title"] ?></a> 
        <?php endforeach; ?>
      </li>
    </ul>
  </main>
  <?php include './includes/footer.php' ?>
</body>

</html>