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

$sql = "DELETE FROM movies WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $movie_id);

if ($stmt->execute()) {
    header("Location: home.php");
    exit();
} else {
    echo "Error deleting record.";
}
?>


















