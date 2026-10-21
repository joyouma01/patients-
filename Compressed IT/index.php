<?php
session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"] ?? "";
    $password = $_POST["password"] ?? "";

    if ($email == "admin@compressit.local" && $password == "CompressIT123!") {
    $_SESSION["logged_in"] = true;
    $_SESSION["email"] = $email;

    header("Location: dashboard.php");
    exit;
    } else {
        $error = "Incorrect email or password. Please try again.";
    }
}

if (isset($_GET["logout"])) {
    session_destroy();
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>CompressIT - File Management System</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">

    <?php if (isset($_SESSION["logged_in"])): ?>

        <div class="logo">CompressIT</div>

        <h2>Welcome to Your Dashboard!</h2>

        <p class="welcome-text">
            You have successfully logged in to the
            File Compression and Storage Management System.
        </p>

        <p class="user">
            Logged in as:
            <strong><?php echo htmlspecialchars($_SESSION["email"]); ?></strong>
        </p>

        <a href="index.php?logout=1" class="logout">Log Out</a>

    <?php else: ?>

        <div class="logo">CompressIT</div>

        <p class="subtitle">
            File Compression and Storage Management System
        </p>

        <h2>Admin Login</h2>

        <?php if ($error != ""): ?>
            <p class="error">
                <?php echo htmlspecialchars($error); ?>
            </p>
        <?php endif; ?>

        <form method="POST" action="index.php">

            <label for="email">Email Address</label>

            <input
                type="email"
                id="email"
                name="email"
                placeholder="Enter your email"
                required
            >

            <label for="password">Password</label>

            <input
                type="password"
                id="password"
                name="password"
                placeholder="Enter your password"
                required
            >

            <button type="submit">Login</button>

        </form>

    <?php endif; ?>

    <p class="footer">
        &copy; 2026 CompressIT Solutions
    </p>

</div>

</body>
</html>
