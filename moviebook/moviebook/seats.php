<?php
session_start();
include "connect.php";

if (!isset($_SESSION["user"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

$sql = "SELECT * FROM movies ORDER BY show_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Select Movie - Manage Seats</title>
    <style>
        body {
            background-color: #111820;
            color: #E6E1E7;
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background: #AF282F;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            position: fixed;
            top: 0;
            width: 99%;
            z-index: 1000;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
            padding: 0 20px;
        }

        .navbar .welcome-message {
            font-size: 16px;
            color: white;
            font-weight: bold;
            margin-right: auto;
        }

        .nav-links {
            display: flex;
            height: 100%;
            margin-left: 10px;
        }

        .nav-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            padding: 0 18px;
            background: #AF282F;
            color: white;
            text-decoration: none;
            border-right: 2px solid white;
            transition: background 0.3s;
            font-size: 14px;
        }

        .nav-links a:last-child {
            border-right: none;
        }

        .nav-links a:hover {
            background: red;
        }

        .container {
            margin-top: 100px;
            padding: 20px;
        }

        .movie-box {
            background-color: #222;
            padding: 15px;
            margin: 15px auto;
            width: 60%;
            border-radius: 10px;
            text-align: left;
        }

        .movie-box h3 {
            margin: 0;
            color: #AF282F;
        }

        .movie-box p {
            font-size: 14px;
        }

        .view-btn {
            margin-top: 10px;
            background-color: #AF282F;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }

        .view-btn:hover {
            background-color: red;
        }
    </style>
</head>
<body>
<nav class="navbar">
    <div class="welcome-message">Welcome, <?php echo $_SESSION["user"]; ?> (Admin)</div>
    <div class="nav-links">
        <a href="home.php">Manage Movies</a>
        <a href="seats.php">Manage Seats</a>
        <a href="confirm.php">Booking Confirmations</a>
        <a href="dashboard.php">User Dashboard</a>
        <a href="search.php">Search & Filters</a>
        <a href="manage_slider.php">Manage Slider</a>

    </div>
</nav>

<div class="container">
    <h2>Select a Movie to View Seats</h2>
    <?php while ($row = $result->fetch_assoc()) : ?>
        <div class="movie-box">
            <h3><?php echo $row['title']; ?></h3>
            <p><?php echo $row['description']; ?></p>
            <form method="GET" action="view_seats.php">
                <input type="hidden" name="movie_id" value="<?php echo $row['id']; ?>">
                <button type="submit" class="view-btn">View Seats</button>
            </form>
        </div>
    <?php endwhile; ?>
</div>
</body>
</html>
