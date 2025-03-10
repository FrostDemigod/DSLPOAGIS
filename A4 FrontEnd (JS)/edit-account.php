<?php
session_start();

require './includes/library.php';

$pdo = connectDB();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$stmtUser = $pdo->prepare("SELECT * FROM 3420_assg_users WHERE id = ?");
$stmtUser->execute([$user_id]);
$user = $stmtUser->fetch();

// Initialize variables with default values or retrieve them from $user
    $name = isset($_POST['name']) ? $_POST['name'] : $user['name'];
    $gender = isset($_POST['gender']) ? $_POST['gender'] : $user['gender'];
    $username = isset($_POST['username']) ? $_POST['username'] : $user['username'];
    $email = isset($_POST['email']) ? $_POST['email'] : $user['email'];
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

// Handle user information update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateUser'])) {

    // Initialize variables with default values or retrieve them from $user
    $name = isset($_POST['name']) ? $_POST['name'] : $user['name'];
    $gender = isset($_POST['gender']) ? $_POST['gender'] : $user['gender'];
    $username = isset($_POST['username']) ? $_POST['username'] : $user['username'];
    $email = isset($_POST['email']) ? $_POST['email'] : $user['email'];
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    // Sanitize all text inputs
    $name               = htmlspecialchars($name);
    $gender             = htmlspecialchars($gender);
    $username           = htmlspecialchars($username);
    $email              = htmlspecialchars($email);
    $password           = htmlspecialchars($password);
    $confirm_password    = htmlspecialchars($confirm_password);


    // Simple validation 
    if (empty($name) || empty($gender) || empty($username) || empty($email)) {
        $userUpdateError = 'All fields are required for user information update.';
    } else {
        // Update user information in the database
        $stmtUserUpdate = $pdo->prepare("UPDATE 3420_assg_users SET name = ?, gender = ?, username = ?, email = ? WHERE id = ?");
        $stmtUserUpdate->execute([$name, $gender, $username, $email, $user_id]);

        // Update password if provided
        if (!empty($password)) {
            // Validate password
            if ($password !== $confirm_password) {
                $userUpdateError = 'Password and confirm password do not match.';
            } else {
                // Update password in the database
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmtPasswordUpdate = $pdo->prepare("UPDATE 3420_assg_users SET password = ? WHERE id = ?");
                $stmtPasswordUpdate->execute([$hashedPassword, $user_id]);
            }
        }

        $userUpdateSuccess = 'User information updated successfully.';
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/05ad49203b.js" crossorigin="anonymous"></script>
    <title>Edit Account</title>
    <!-- include javascript and css-->
    <link rel="stylesheet" href="styles/main.css">
    <script defer src="js/scripts.js"></script>
</head>
<body>
    <header>
        <!--This will be the main heading of the page so users know what page they're on-->
        <h1>Edit Your Account</h1>

        <?php include './includes/nav.php' ?>
    </header>
    <main>
        <!-- User Information Update Form -->
        <form action="edit-account.php" method="post">
            <fieldset>
                <legend>Update User Information</legend>

                <?php
                if (isset($userUpdateError)) {
                    echo '<div class="error">' . $userUpdateError . '</div>';
                } elseif (isset($userUpdateSuccess)) {
                    echo '<div class="success">' . $userUpdateSuccess . '</div>';
                }
                ?>

                <div>
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
                </div>
                <div>
                    <label for="gender">Gender</label>
                    <select name="gender" id="gender" required>
                        <option value="male" <?php echo ($gender === 'male') ? 'selected' : ''; ?>>Male</option>
                        <option value="female" <?php echo ($gender === 'female') ? 'selected' : ''; ?>>Female</option>
                        <option value="gnc" <?php echo ($gender === 'gnc') ? 'selected' : ''; ?>>Gender Queer/Non-Conforming</option>
                        <option value="notsay" <?php echo ($gender === 'notsay') ? 'selected' : ''; ?>>Prefer not to say</option>
                    </select>
                </div>
                <div class="container">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="<?php echo $username; ?>" required>
                </div>
                <div>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
                </div>

                <div>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password">
                </div>
                <div>
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password">
                </div>
                <span>Leave blank to keep the current password.</span>
            </fieldset>

            <input type="submit" name="updateUser" value="Update User Information">
        </form>
    </main>
    <?php include './includes/footer.php' ?>
</body>
</html>