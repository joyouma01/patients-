<?php
session_start();

// Protect the dashboard from users who are not logged in
if (!isset($_SESSION["logged_in"])) {
    header("Location: index.php");
    exit;
}

$email = $_SESSION["email"];
$uploadFolder = "uploads/";

$totalFiles = 0;
$totalStorage = 0;
$zipFiles = 0;

if (is_dir($uploadFolder)) {
    $uploadedFiles = scandir($uploadFolder);

    foreach ($uploadedFiles as $file) {
        if ($file !== "." && $file !== ".." && is_file($uploadFolder . $file)) {

            $totalFiles++;

            $totalStorage += filesize($uploadFolder . $file);

            if (strtolower(pathinfo($file, PATHINFO_EXTENSION)) === "zip") {
                $zipFiles++;
            }
        }
    }
}

$storageLimit = 100 * 1024 * 1024;

$availableStorage = max(0, $storageLimit - $totalStorage);

function formatStorage($bytes) {
    if ($bytes >= 1048576) {
        return round($bytes / 1048576, 2) . " MB";
    }

    if ($bytes >= 1024) {
        return round($bytes / 1024, 2) . " KB";
    }

    return $bytes . " bytes";
}?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>CompressIT - Dashboard</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="dashboard">

    <header class="topbar">
        <h1>CompressIT</h1>
        <a href="index.php?logout=1" class="logout">Log Out</a>
    </header>

    <main class="dashboard-content">

        <h2>Welcome Back!</h2>

        <p>
            Hello, <?php echo htmlspecialchars($email); ?>.
            Manage your files and monitor your storage below.
        </p>

        <section class="stats">
<div class="stats-container">

    <div class="stat-card">
        <div class="stat-icon blue">📁</div>
        <div class="stat-info">
            <h3>Total Files</h3>
            <p><?php echo $totalFiles; ?></p>
            <span>Files uploaded</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green">💾</div>
        <div class="stat-info">
            <h3>Storage Used</h3>
            <p><?php echo formatStorage($totalStorage); ?></p>
            <span>Total space occupied</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon purple">📦</div>
        <div class="stat-info">
            <h3>Compressed Files</h3>
            <p><?php echo $zipFiles; ?></p>
            <span>ZIP archives</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon orange">☁️</div>
        <div class="stat-info">
            <h3>Available Storage</h3>
            <p><?php echo formatStorage($availableStorage); ?></p>
            <span>Remaining demo capacity</span>
        </div>
    </div>

            </div>

        </section>

        <section class="actions">

            <h2>File Management</h2>

            <div class="action-card">
                <h3>Upload Files</h3>
                <p>Select files to upload to the system.</p>
                <a href="files.php" class="action-button">
                    Upload Files
                </a>
            </div>

            <div class="action-card">
                <h3>Manage Files</h3>
                <p>View and manage your stored files.</p>
                <a href="files.php" class="action-button">
                    View Files
                </a>
            </div>

            <div class="action-card">
                <h3>Storage Reports</h3>
                <p>View storage and compression reports.</p>
                <a href="reports.php" class="action-button">
                    View Reports
                </a>
            </div>

        </section>

    </main>

    <footer class="footer">
        &copy; 2026 CompressIT Solutions
    </footer>

</div>

</body>
</html>