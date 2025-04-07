<?php
session_start();
require './includes/library.php';

$pdo = connectDB();

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $identifier = trim($_POST['usernameOrEmail']);
    $newPassword = $_POST['newPassword'];

    if (empty($identifier) || empty($newPassword)) {
        $errors['reset'] = "Please fill out both fields.";
    } else {
        // Check for user by username or email
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$identifier, $identifier]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $errors['reset'] = "No account found with that username or email.";
        } else {
            // Hash the new password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update the password in the database
            $updateStmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
            $success = $updateStmt->execute([$hashedPassword, $user['id']]);

            if ($success) {
                // Redirect or notify user
                header("Location: ../login.html");
                exit();
            } else {
                $errors['reset'] = "An error occurred while updating the password. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Password Reset</title>
</head>
<body>
    <?php if (!empty($errors['reset'])): ?>
        <p style="color: red;"><?= htmlspecialchars($errors['reset']) ?></p>
    <?php endif; ?>
</body>
</html>
