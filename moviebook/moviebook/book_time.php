<?php
session_start();
include "connect.php";

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['movie_id'])) {
    $_SESSION['movie_id'] = $_GET['movie_id'];
}

if (!isset($_SESSION['movie_id'])) {
    echo "<script>alert('No movie selected.'); window.location.href='home2.php';</script>";
    exit();
}

$movieId = $_SESSION['movie_id'];

$stmt = $conn->prepare("SELECT title FROM movies WHERE id = ?");
$stmt->bind_param("i", $movieId);
$stmt->execute();
$result = $stmt->get_result();
$movie = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['booking_date'] = $_POST['date'];
    $_SESSION['booking_time'] = $_POST['time'];
    header("Location: seats2.php");
    exit();
}

$today = date('Y-m-d');
$maxDate = date('Y-m-d', strtotime('+7 days'));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Select Date & Time</title>
    <style>
        body {
            background-image: url('bgg.jpg');
            color: #E6E1E7;
            font-family: Arial, sans-serif;
            text-align: center;
            padding-top: 80px;
        }
        form {
            display: inline-block;
            background: #2C3D49;
            padding: 60px;
            border-radius: 10px;
        }
        label, input, select {
            display: block;
            margin: 5px auto;
            font-size: 20px;
            border-radius: 8px;
            position: relative;
            bottom: 10px;
            
        }
        button {
            background-color: #AF282F;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            position: relative;
            top: 20px;
        }
        button:hover {
            background-color: red;
        }
    </style>
</head>
<body>
    <h1>Select Date and Time for: <?php echo htmlspecialchars($movie['title']); ?></h1>
    <form method="POST">
        <label for="date">Date:</label>
        <input type="date" name="date" id="date" min="<?php echo $today; ?>" max="<?php echo $maxDate; ?>" required>

        <label for="time">Time:</label>
        <select name="time" id="time" required>
            <option value="13:00">1:00 PM</option>
            <option value="15:30">3:30 PM</option>
            <option value="18:00">6:00 PM</option>
            <option value="20:30">8:30 PM</option>
        </select>

        <button type="submit">Continue to Seat Selection</button>
    </form>
</body>
</html>
