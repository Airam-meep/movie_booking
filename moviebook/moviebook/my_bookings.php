<?php
session_start();
include "connect.php";

if (!isset($_SESSION["user"]) || $_SESSION["role"] !== "user") {
    header("Location: login.php");
    exit();
}

// Get logged-in user's ID
$username = $_SESSION['user'];
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user['id'];
$stmt->close();

// Fetch user's bookings
$sql = "SELECT b.id AS booking_id, m.title AS movie_title, s.seat_number, b.booking_date, b.payment_status
        FROM bookings b
        JOIN movies m ON b.movie_id = m.id
        JOIN seats s ON b.seat_id = s.id
        WHERE b.user_id = ?
        ORDER BY b.booking_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$bookings = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Bookings</title>
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
            font-size: 14px;
            transition: background 0.3s;
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
            width: 90%;
            margin-left: auto;
            margin-right: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #222;
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 15px;
            border-bottom: 1px solid #444;
            text-align: center;
        }

        th {
            background-color: #AF282F;
            color: white;
        }

        .status {
            padding: 8px 15px;
            border-radius: 5px;
            display: inline-block;
        }

        .pending {
            background-color: orange;
            color: white;
        }

        .paid {
            background-color: green;
            color: white;
        }

        .cancelled {
            background-color: red;
            color: white;
        }

        .cancel-btn {
            background-color: red;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
        }

        .cancel-btn:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>

<nav class="navbar">
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION["user"]); ?>!</h1>
    <div class="nav-links">
        <a href="home2.php">Movies</a>
        <a href="confirm2.php">My Bookings</a>
        <a href="search2.php">Search Movies</a>
    </div>
</nav>

<div class="container">
    <h2>My Bookings</h2>

    <?php if ($bookings->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Movie</th>
                    <th>Seat</th>
                    <th>Booking Date</th>
                    <th>Payment Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $bookings->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['movie_title']); ?></td>
                        <td><?php echo htmlspecialchars($row['seat_number']); ?></td>
                        <td><?php echo $row['booking_date']; ?></td>
                        <td>
                            <span class="status <?php echo strtolower($row['payment_status']); ?>">
                                <?php echo ucfirst($row['payment_status']); ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($row['payment_status'] !== 'cancelled'): ?>
                                <form method="POST" action="user_cancel_booking.php" onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                                    <input type="hidden" name="booking_id" value="<?php echo $row['booking_id']; ?>">
                                    <button type="submit" class="cancel-btn">Cancel</button>
                                </form>
                            <?php else: ?>
                                Cancelled
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No bookings found.</p>
    <?php endif; ?>
</div>

</body>
</html>
