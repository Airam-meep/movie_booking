<?php
session_start();
include "connect.php";

if (!isset($_SESSION["user"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php"); //redirect if not admin
    exit();
}

// Get now showing movies
$sql = "SELECT * FROM movies ORDER BY show_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Homepage</title>
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

        button {
            padding: 10px;
            border: none;
            cursor: pointer;
            border-radius: 8px;
            margin: 5px;
        }

        .edit-btn { 
            background: #FFD700; 
            color: #000; 
        }

        .delete-btn { 
            background: #AF282F; 
            color: white; 
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

        .add-btn:hover {
            background: red;
        }

        .add-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #AF282F;
            color: white;
            padding: 10px 15px;
            border-radius: 8px;
            text-decoration: none;
        }

    </style>
</head>
<body>

    <!-- Navigation Bar -->
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
        <h2>Now Showing</h2>
        <?php while ($row = $result->fetch_assoc()) : ?>
            <div class="movie">
                <!-- show inserted in db-->
                <img src="<?php echo $row['poster']; ?>" alt="<?php echo $row['title']; ?>">
                <div class="movie-info">
                    <h3><?php echo $row['title']; ?></h3>
                    <p><?php echo $row['description']; ?></p>
                </div>

                <!-- Admin-only edit/delete buttons -->
                <button class="edit-btn" onclick="window.location.href='edit_movie.php?id=<?php echo $row['id']; ?>'">Edit</button>
                <button class="delete-btn" onclick="confirmDelete(<?php echo $row['id']; ?>)">Delete</button>
        </div>
            <button class="add-btn" onclick="window.location.href='add_movie.php?id=<?php echo $row['id']; ?>'">Add Movie</button>
        <?php endwhile; ?>
    </div>

    <!-- Logout button fixed to bottom left -->
    <a href="logout.php" class="logout-btn">Logout</a>

</body>

<script>
    function confirmDelete(movieId) {
        let confirmAction = confirm("Are you sure you want to delete this movie?");
        if (confirmAction) {
            window.location.href = 'delete_movie.php?id=' + movieId;
        }
    }
</script>

</html>






