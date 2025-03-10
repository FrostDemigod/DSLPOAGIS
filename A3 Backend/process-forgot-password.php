<?php
require './includes/library.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pdo = connectDB();
    $usernameOrEmail = $_POST['usernameOrEmail'];
    $newPassword = $_POST['newPassword']; // Assuming the new password is submitted in the form
    
    // Check if the user exists
    $stmt = $pdo->prepare("SELECT id, email FROM 3420_assg_users WHERE username = ? OR email = ?");
    $stmt->execute([$usernameOrEmail, $usernameOrEmail]);
    $user = $stmt->fetch();

    if ($user) {
        // Update the user's password in the database
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $updateStmt = $pdo->prepare("UPDATE 3420_assg_users SET password = ? WHERE id = ?");
        $updateStmt->execute([$hashedPassword, $user['id']]);

        // Send an email to the user notifying them of the password reset
        $subject = "Password Reset";
        $body = "Your password has been reset.";

        mail($user['email'], $subject, $body);

        // Redirect to the login page or display a success message
        header("Location: login.php?reset=success");
        exit();
    } else {
        // User not found, redirect to the forgot password page with an error message
        header("Location: forgot.php?error=user_not_found");
        exit();
    }
}
?>
