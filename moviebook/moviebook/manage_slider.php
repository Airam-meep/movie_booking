<?php
session_start();
include "connect.php";

if (!isset($_SESSION["user"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

// If admin submits new filenames
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Clear old images first
    $conn->query("TRUNCATE TABLE slider_images");

    // Insert new ones
    foreach ($_POST['filenames'] as $filename) {
        if (!empty($filename)) {
            $stmt = $conn->prepare("INSERT INTO slider_images (filename) VALUES (?)");
            $stmt->bind_param("s", $filename);
            $stmt->execute();
        }
    }
    echo "<script>alert('Slider images updated!'); window.location.href='manage_slider.php';</script>";
    exit();
}

// Fetch current images
$images = $conn->query("SELECT * FROM slider_images");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Slider Images</title>
    <style>
        body { background-color: #111820; color: #E6E1E7; font-family: Arial, sans-serif; text-align: center; }
        input { margin: 10px; padding: 8px; width: 300px; }
        button { padding: 10px 20px; background: #AF282F; color: white; border: none; border-radius: 8px; cursor: pointer; }
        button:hover { background: red; }
    </style>
</head>
<body>
    <h1>Manage Slider Images</h1>

    <form method="POST">
        <?php
        $i = 0;
        while ($row = $images->fetch_assoc()) {
            echo '<input type="text" name="filenames[]" value="' . htmlspecialchars($row['filename']) . '" required><br>';
            $i++;
        }
        for (; $i < 3; $i++) {
            echo '<input type="text" name="filenames[]" placeholder="Enter image filename..." required><br>';
        }
        ?>
        <br>
        <button type="submit">Save Changes</button>
    </form>

    <br><a href="home.php" style="color: #AF282F;">Back to Admin Home</a>
</body>
</html>
