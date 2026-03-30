<?php
session_start();
include "connect.php";

// Redirect if the user is not an admin
if (!isset($_SESSION["user"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

// Get all booking details
$sql = "SELECT b.id AS booking_id, u.username, m.title AS movie_title, 
               s.seat_number, b.booking_date, b.payment_status, b.user_id
        FROM bookings b
        JOIN users u ON b.user_id = u.id
        JOIN movies m ON b.movie_id = m.id
        JOIN seats s ON b.seat_id = s.id
        ORDER BY b.booking_date DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Booking Confirmations</title>
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
            margin-top: 90px;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #333;
        }

        .status-btn {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
        }

        .paid-btn {
            background-color: green;
            color: white;
        }

        .pending-btn {
            background-color: orange;
            color: white;
        }

        .cancelled-btn {
            background-color: red;
            color: white;
        }

        .cancel-btn {
            background-color: red;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
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
        <h2>Booking Confirmations</h2>
        
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>User</th>
                        <th>Movie</th>
                        <th>Seat</th>
                        <th>Booking Date</th>
                        <th>Payment Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['booking_id']; ?></td>
                            <td><?php echo $row['username']; ?></td>
                            <td><?php echo $row['movie_title']; ?></td>
                            <td><?php echo $row['seat_number']; ?></td>
                            <td><?php echo $row['booking_date']; ?></td>
                            <td>
                                <!-- Show payment status -->
                                <?php if ($row['payment_status'] == 'paid'): ?>
                                    <span class="status-btn paid-btn">Paid</span>
                                <?php elseif ($row['payment_status'] == 'pending'): ?>
                                    <span class="status-btn pending-btn">Pending</span>
                                <?php else: ?>
                                    <span class="status-btn cancelled-btn">Cancelled</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <!-- Update payment status or cancel booking -->
                                <a href="update_payment_status.php?booking_id=<?php echo $row['booking_id']; ?>&status=paid" class="status-btn paid-btn">Mark as Paid</a>
                                <a href="update_payment_status.php?booking_id=<?php echo $row['booking_id']; ?>&status=pending" class="status-btn pending-btn">Mark as Pending</a>
                                <a href="cancel_booking.php?booking_id=<?php echo $row['booking_id']; ?>" class="cancel-btn">Cancel Booking</a>
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
