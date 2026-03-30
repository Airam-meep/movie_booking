<?php
session_start();
include "connect.php";

// Only allow admins
if (!isset($_SESSION["user"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

// Get movie ID
if (!isset($_GET['movie_id'])) {
    echo "<script>alert('No movie selected.'); window.location.href='seats.php';</script>";
    exit();
}
$movieId = intval($_GET['movie_id']);

// Fetch movie title
$movieQuery = $conn->prepare("SELECT title FROM movies WHERE id = ?");
$movieQuery->bind_param("i", $movieId);
$movieQuery->execute();
$movieResult = $movieQuery->get_result();
$movieTitle = $movieResult->fetch_assoc()['title'];

// Get taken seats
$takenSeats = [];
$query = $conn->prepare("SELECT seat_number FROM seats WHERE movie_id = ? AND is_reserved = 1");
$query->bind_param("i", $movieId);
$query->execute();
$res = $query->get_result();
while ($row = $res->fetch_assoc()) {
    $takenSeats[] = $row['seat_number'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Seat View - <?php echo $movieTitle; ?></title>
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
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .screen {
            background: gray;
            width: 80%;
            height: 40px;
            text-align: center;
            line-height: 40px;
            font-weight: bold;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .seats {
            display: grid;
            grid-template-columns: repeat(10, 40px);
            gap: 10px;
        }

        .seat {
            width: 40px;
            height: 40px;
            background: #444;
            border-radius: 5px;
            text-align: center;
            line-height: 40px;
            color: white;
        }

        .taken {
            background: red;
        }

        .legend {
            margin-top: 20px;
        }

        .legend span {
            display: inline-block;
            width: 40px;
            height: 20px;
            margin: 0 10px;
            border-radius: 4px;
        }

        .available-box {
            background-color: #444;
        }

        .taken-box {
            background-color: red;
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

        </div>
    </nav>

    <div class="container">
        <h2>Seats for "<?php echo $movieTitle; ?>"</h2>
        <div class="screen">SCREEN</div>
        <div class="seats">
            <?php for ($i = 1; $i <= 50; $i++): 
                $seatNum = "S" . $i;
                $taken = in_array($seatNum, $takenSeats) ? 'taken' : '';
            ?>
                <div class="seat <?php echo $taken; ?>"><?php echo $seatNum; ?></div>
            <?php endfor; ?>
        </div>
        <div class="legend">
            <span class="available-box"></span> Available
            <span class="taken-box"></span> Booked
        </div>
    </div>
</body>
</html>
