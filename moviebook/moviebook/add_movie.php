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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $poster = $_POST['poster'];
    $price = $_POST['price'];

    //Insert to database
    $sql ="INSERT INTO movies (title, description, poster, price) VALUES (?, ?, ?, ?)";
    $stmt  = $conn->prepare($sql);
    $stmt->bind_param("sssd", $title, $description, $poster, $price);

    if ($stmt->execute()) {
        header("Location: home.php");
        exit();
    } else {
        echo "Error adding movie.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Movie</title>
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
        <h2>Add a New Movie</h2>
        <form method="POST">
            <input type="text" name="title" placeholder="Movie Title" required><br>
            <textarea name="description" placeholder="Description" required></textarea><br>
            <input type="text" name="poster" placeholder="Poster Filename" required><br>
            <input type="number" step="0.01" name="price" placeholder="Ticket Price (₱)" required><br>
            <button type="submit">Add Movie</button>
        </form>
        <a href="home.php">Cancel</a>
    </div>
</body>
</html>
