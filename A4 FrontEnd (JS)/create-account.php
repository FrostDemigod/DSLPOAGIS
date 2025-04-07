<?php
session_start();
require './includes/library.php';

try {
    // Establish the PDO connection
    $pdo = connectDB();

    // Print server connection details
    $dsn = $pdo->getAttribute(PDO::ATTR_CONNECTION_STATUS);
    echo "Connected to server: $dsn<br>";

    // Print column names from the 'users' table (for debugging) - PostgreSQL way
    $stmt = $pdo->query("SELECT column_name FROM information_schema.columns WHERE table_name = 'users'");
    echo "Columns in 'users' table:<br>";
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        echo $column['column_name'] . "<br>";  // Use the correct key 'column_name'
    }

    $errors = [];

    // Get form data
    $name = trim($_POST['name'] ?? "");
    $gender = trim($_POST['gender'] ?? "");
    $username = trim($_POST['username'] ?? "");
    $email = trim($_POST['email'] ?? "");
    $password = $_POST['password'] ?? "";
    $confirmPassword = $_POST['confirm_password'] ?? "";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $name = htmlspecialchars($name);
        $gender = htmlspecialchars($gender);
        $username = htmlspecialchars($username);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        // Validation
        if (empty($name)) $errors['name'] = "Name is required.";
        if (empty($gender)) $errors['gender'] = "Gender is required.";
        if (empty($username)) $errors['username'] = "Username is required.";
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = "Invalid email format.";
        if (empty($password)) $errors['password'] = "Password is required.";
        if ($password !== $confirmPassword) $errors['match'] = "Passwords do not match.";

        // Check if the username already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->rowCount() > 0) {
            $errors['username'] = "Username is already taken.";
        }

        // If no errors, insert the user into the database
        if (empty($errors)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $query = "INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$username, $email, $hashedPassword]);

            header("Location: ../login.html");
            exit();
        }
    }

} catch (PDOException $e) {
    // Handle exception and print the error message
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create an Account</title>
    <link rel="stylesheet" href="styles/main.css">
</head>
<body>
    <header>
        <h1>Create An Account</h1>
    </header>
    <main>
        <form action="create-account.php" method="post">
            <fieldset>
                <legend>Account Information</legend>
                <div>
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                    <span class="error"><?= $errors['name'] ?? '' ?></span>
                </div>
                <div>
                    <label for="gender">Gender:</label>
                    <select name="gender" id="gender" required>
                        <option value="">Please Choose One</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="gnc">Gender Queer/Non-Conforming</option>
                        <option value="notsay">Prefer not to say</option>
                    </select>
                    <span class="error"><?= $errors['gender'] ?? '' ?></span>
                </div>
                <div>
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                    <span class="error"><?= $errors['username'] ?? '' ?></span>
                </div>
                <div>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                    <span class="error"><?= $errors['email'] ?? '' ?></span>
                </div>
                <div>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                    <span class="error"><?= $errors['password'] ?? '' ?></span>
                </div>
                <div>
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                    <span class="error"><?= $errors['match'] ?? '' ?></span>
                </div>
            </fieldset>
            <input type="submit" value="Create Account">
        </form>
    </main>
</body>
</html>
