
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/05ad49203b.js" crossorigin="anonymous"></script>
    <title>Edit Successful!</title>
    <!-- include CSS-->
    <link rel="stylesheet" href="styles/main.css">
    <script defer src="js/scripts.js"></script>
</head>
<body>
    <header>
        <!--This will be the main heading of the page so users know what page they're on-->
        <h1>Item Edited!</h1>
        <?php include './includes/nav.php' ?>
    </header>
    <main>
            <fieldset>
                <legend>Congratulations!</legend>
                <p>Your Item has been edited Successfully! You may see it <a href="view-item.php?id=<?php echo $list_id; ?>">Here</a></p>
            </fieldset>
    </main>
    <?php include './includes/footer.php' ?>
</body>
</html>
