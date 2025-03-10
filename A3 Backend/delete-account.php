
<?php
require './includes/library.php';

session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$pdo = connectDB();

// Fetch user ID from the database based on the current session
$username = $_SESSION['username'];
$stmt = $pdo->prepare("SELECT id FROM 3420_assg_users WHERE username = ?");
$stmt->execute([$username]);
$userID = $stmt->fetchColumn();
// Check if the user has confirmed the account deletion
if (isset($_POST['confirmDelete'])) {
    // Delete all data associated with the user
    $stmtDeleteLists = $pdo->prepare("DELETE FROM 3420_assg_lists WHERE user_id = ?");
    $stmtDeleteLists->execute([$userID]);

    $stmtDeleteUser = $pdo->prepare("DELETE FROM 3420_assg_users WHERE username = ?");
    $stmtDeleteUser->execute([$username]);

// Destroy the session
session_destroy();

// Redirect to login
header("Location: login.php");
exit();
}
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
        <!--This will be the main heading of the page so users know what page they're on-->
        <h1>Delete Account</h1>
        <?php include './includes/nav.php' ?>
    </header>
    <main>
        <form action="delete-account.php" method="post">
            <fieldset>
                <legend>Confirmation</legend>
                <p>Are you sure you want to delete your account? This action cannot be undone.</p>
                <div>
                    <button type="submit" name="confirmDelete" class="big-button">Yes, I'm sure. Delete my account</button>
                </div>
            </fieldset>
        </form>
    </main>
    <?php include './includes/footer.php' ?>
</body>
</html>
