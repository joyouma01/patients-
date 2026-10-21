<?php
session_start();

if (!isset($_SESSION["logged_in"])) {
    header("Location: index.php");
    exit;
}

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {

        $uploadDir = "uploads/";

        $originalName = basename($_FILES["file"]["name"]);
        $tempFile = $_FILES["file"]["tmp_name"];
        $fileSize = $_FILES["file"]["size"];

        $fileName = pathinfo($originalName, PATHINFO_FILENAME);
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);

        $safeName = preg_replace("/[^a-zA-Z0-9_-]/", "_", $fileName);
        $zipName = $safeName . "_" . time() . ".zip";

        $zipPath = $uploadDir . $zipName;

        if ($fileSize > 10 * 1024 * 1024) {
            $message = "File is too large. Maximum size is 10 MB.";
            $messageType = "error";

        } else {

            $zip = new ZipArchive();

            if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {

                $zip->addFile($tempFile, $originalName);
                $zip->close();

                $message = "File uploaded and compressed successfully!";
                $messageType = "success";

            } else {

                $message = "Unable to create ZIP file. Check your XAMPP ZIP extension.";
                $messageType = "error";
            }
        }

    } else {
        $message = "Please select a file to upload.";
        $messageType = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Upload Files - CompressIT</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="dashboard">

    <header class="topbar">
        <h1>CompressIT</h1>
        <a href="dashboard.php">Back to Dashboard</a>
    </header>

    <main class="dashboard-content">

        <h2>Upload and Compress Files</h2>

        <p>Select a file from your computer to compress it into a ZIP archive.</p>

        <?php if ($message != ""): ?>
            <p style="padding: 15px; margin: 20px 0;
                background: <?php echo $messageType == 'success' ? '#dcfce7' : '#fee2e2'; ?>;
                color: <?php echo $messageType == 'success' ? '#166534' : '#991b1b'; ?>;
                border-radius: 8px;">
                <?php echo htmlspecialchars($message); ?>
            </p>
        <?php endif; ?>

        <div class="action-card">

            <form action="upload.php" method="POST" enctype="multipart/form-data">

                <label for="file">
                    Choose a file (maximum 10 MB):
                </label>

                <br><br>

                <input type="file" name="file" id="file" required>

                <br><br>

                <button type="submit" class="action-button">
                    Upload and Compress
                </button>

            </form>

        </div>

    </main>

</div>

</body>
</html>