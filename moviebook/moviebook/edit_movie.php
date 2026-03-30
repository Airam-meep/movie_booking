<?php
session_start();
include "connect.php";

if (!isset($_SESSION["user"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: home.php");
    exit();
}

$movie_id = $_GET['id'];
$sql = "SELECT * FROM movies WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $movie_id);
$stmt->execute();
$result = $stmt->get_result();
$movie = $result->fetch_assoc();

if (!$movie) {
    header("Location: home.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $description = $_POST["description"];
    $poster = $_POST["poster"];
    $price = $_POST["price"];

    $update_sql = "UPDATE movies SET title = ?, description = ?, poster = ?, price = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssdi", $title, $description, $poster, $price, $movie_id);

    if ($update_stmt->execute()) {
        header("Location: home.php");
        exit();
    } else {
        echo "Error updating record.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Movie</title>
    <style>
        body {
            background-color: #111820;
            color: #E6E1E7;
            font-family: Arial, sans-serif;
            text-align: center;
            padding-top: 80px;
        }
        .container {
            background-color: #222;
            padding: 30px;
            margin: auto;
            width: 400px;
            border-radius: 12px;
        }
        input, textarea {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
            background-color: #333;
            color: #fff;
        }
        button {
            width: 200px;
            background-color: #AF282F;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: red;
        }
        a {
            color: #AF282F;
            display: inline-block;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Movie</h2>
        <form method="POST">
            <input type="text" name="title" placeholder="Movie Title" value="<?php echo htmlspecialchars($movie['title']); ?>" required><br>
            <textarea name="description" placeholder="Description" required><?php echo htmlspecialchars($movie['description']); ?></textarea><br>
            <input type="text" name="poster" placeholder="Poster Filename" value="<?php echo htmlspecialchars($movie['poster']); ?>" required><br>
            <input type="number" step="0.01" name="price" placeholder="Ticket Price (₱)" value="<?php echo htmlspecialchars($movie['price']); ?>" required><br>
            <button type="submit">Save Changes</button>
        </form>
        <a href="home.php">Cancel</a>
    </div>
</body>
</html>
