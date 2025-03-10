
<?php
require './includes/library.php';

session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
if(isset($_GET['id'])){
    $list_id = $_GET['id'];
}
  $userid = $_SESSION['user_id'];

$pdo = connectDB();
// Check if the user has confirmed the account deletion
if (isset($_POST['DelList'])) {
    // Delete The List
    $stmtDeleteList = $pdo->prepare("DELETE FROM 3420_assg_lists WHERE user_id = ? AND list_id = ?");
    $stmtDeleteList->execute([$userid, $list_id]);

// Redirect to Home Page
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
    <title>Delete Item</title>
    <!-- include CSS-->
    <link rel="stylesheet" href="styles/main.css">
    <script defer src="js/scripts.js"></script>
</head>
<body>
    <header>
        <!--This will be the main heading of the page so users know what page they're on-->
        <h1>Delete Item</h1>
        <?php include './includes/nav.php' ?>
    </header>
    <main>
        <form action="" method="post">
            <fieldset>
                <legend>Confirmation</legend>
                <p>Are you sure you want to delete your Item? This action cannot be undone.</p>
                <div>
                    <button type="submit" name="DelList" class="big-button">Yes, I'm sure. Delete my Item</button>
                </div>
            </fieldset>
        </form>
    </main>
    <?php include './includes/footer.php' ?>
</body>
</html>
