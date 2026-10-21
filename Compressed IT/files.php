<?php
session_start();

if (!isset($_SESSION["logged_in"])) {
    header("Location: index.php");
    exit();
}
$folder = "uploads/";
$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_file"])) {

    $fileName = basename($_POST["delete_file"]);
    $filePath = $folder . $fileName;

    if (is_file($filePath)) {

        if (unlink($filePath)) {
            $message = "File deleted successfully!";
            $messageType = "success";
        } else {
            $message = "Unable to delete the file.";
            $messageType = "error";
        }

    } else {
        $message = "File not found.";
        $messageType = "error";
    }
}
$files = [];

if (is_dir($folder)) {
    foreach (scandir($folder) as $file) {
        if ($file !== "." && $file !== ".." && is_file($folder . $file)) {
            $files[] = [
                "name" => $file,
                "size" => filesize($folder . $file),
                "date" => filemtime($folder . $file)
            ];
        }
    }
}

function formatSize($bytes) {
    if ($bytes >= 1048576) {
        return round($bytes / 1048576, 2) . " MB";
    }

    if ($bytes >= 1024) {
        return round($bytes / 1024, 2) . " KB";
    }

    return $bytes . " bytes";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Files - CompressIT</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="style.css">
</head>

<body class="files-page">

<div class="container">

    <!-- PAGE HEADER -->
    <div class="header">

        <div class="header-content">
            <div class="brand">COMPRESSIT</div>

            <h1>My Uploaded Files</h1>

            <p class="subtitle">
                Manage, view and download your compressed files.
            </p>
        </div>

        <a href="dashboard.php" class="back-btn">
            ← Back to Dashboard
        </a>

    </div>

    <!-- FILE LIBRARY CARD -->
    <div class="card">
        <?php if ($message !== ""): ?>
    <div class="message <?php echo $messageType; ?>">
        <?php echo htmlspecialchars($message); ?>
    </div>
<?php endif; ?>

        <div class="card-heading">

            <h2>File Library</h2>

            <span class="file-count">
                <?php echo count($files); ?> Files
            </span>

        </div>

        <!-- RESPONSIVE TABLE -->
        <div class="table-wrapper">

            <table>

                <thead>
                    <tr>
                        <th>File Name</th>
                        <th>File Size</th>
                        <th>Upload Date</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                <?php if (count($files) > 0): ?>

                    <?php foreach ($files as $file): ?>

                        <tr>

                            <td>
                                <span class="file-icon">ZIP</span>

                                <span class="file-name">
                                    <?php echo htmlspecialchars($file["name"]); ?>
                                </span>
                            </td>

                            <td>
                                <span class="size-badge">
                                    <?php echo formatSize($file["size"]); ?>
                                </span>
                            </td>

                            <td>
                                <?php echo date("d M Y, H:i", $file["date"]); ?>
                            </td>

                            <<td>
    <div class="action-buttons">

        <a
            class="download-btn"
            href="<?php echo htmlspecialchars($folder . rawurlencode($file["name"])); ?>"
            download
        >
            ↓ Download
        </a>

        <form method="POST" class="delete-form"
              onsubmit="return confirm('Are you sure you want to delete this file?');">

            <button
                type="submit"
                name="delete_file"
                value="<?php echo htmlspecialchars($file["name"], ENT_QUOTES, 'UTF-8'); ?>"
                class="delete-btn"
            >
                Delete
            </button>

        </form>

    </div>
</td>
                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="4" class="empty">
                            No files uploaded yet.
                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

    <!-- FOOTER -->
    <div class="footer">
        © 2026 CompressIT Solutions |
        File Compression and Storage Management System
    </div>

</div>

</body>
</html>