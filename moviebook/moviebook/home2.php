<?php
session_start();
include "connect.php"; // Ensure database connection

if (!isset($_SESSION["user"])) {
    header("Location: login.php"); // Redirect if not logged in
    exit();
}

// Fetch now showing movies
$sql = "SELECT * FROM movies ORDER BY show_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Homepage</title>
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
            width: 100%;
            z-index: 1000;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
            padding: 0 20px;
        }

        .navbar h1 {
            font-size: 20px;
            color: white;
            font-weight: bold;
            margin-right: auto;
        }

        .nav-links {
            display: flex;
            height: 100%;
            margin-left: 300px;
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
            width: 80%;
            margin: auto;
            margin-top: 90px;
        }

        .movie {
            display: flex;
            align-items: center;
            background: #222;
            margin: 15px;
            padding: 10px;
            border-radius: 10px;
        }

        .movie img {
            width: 120px;
            height: 180px;
            margin-right: 15px;
            border-radius: 8px;
        }

        .movie-info {
            text-align: left;
            flex: 1;
        }

        .book-btn {
            background: #AF282F;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }

        .book-btn:hover {
            background-color: red;
        }

        .logout-btn {
            position: fixed;
            bottom: 20px;
            left: 20px;
            background: #AF282F;
            color: white;
            padding: 10px 15px;
            border-radius: 8px;
            text-decoration: none;
        }

        .logout-btn:hover {
            background: red;
        }
    </style>
</head>
<body>
<nav class="navbar">
    <h1>Welcome, <?php echo $_SESSION["user"]; ?>!</h1>
    <div class="nav-links">
        <a href="home2.php">Movies</a>

        <a href="my_bookings.php">My Bookings</a>
        <a href="search2.php">Search Movies</a>
        
    </div>
</nav>

<div class="container">
    <h2>Now Showing</h2>
    <?php while ($row = $result->fetch_assoc()) : ?>
        <div class="movie">
            <img src="<?php echo $row['poster']; ?>" alt="<?php echo $row['title']; ?>">
            <div class="movie-info">
                <h3><?php echo $row['title']; ?></h3>
                <p><?php echo $row['description']; ?></p>
                <form method="GET" target="_blank" action="book_time.php"> <!-- Open in a new tab -->
    <input type="hidden" name="movie_id" value="<?php echo $row['id']; ?>">
    <button type="submit" class="book-btn">Book Now</button>
</form>
            </div>
        </div>
    <?php endwhile; ?>
</div>
<a href="logout.php" class="logout-btn">Logout</a>
</body>
</html>
