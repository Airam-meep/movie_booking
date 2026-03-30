<?php
session_start();
include "connect.php";

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];

// Get user ID
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();
$userId = $userData['id'];
$stmt->close();

// Get latest booking details
$sql = "SELECT m.title, s.seat_number, m.show_date, m.show_time, m.price, b.booking_date, p.payment_method, p.status 
        FROM bookings b
        JOIN seats s ON b.seat_id = s.id
        JOIN movies m ON b.movie_id = m.id
        LEFT JOIN payments p ON p.booking_id = b.id
        WHERE b.user_id = ?
        ORDER BY b.booking_date DESC LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();
$stmt->close();

if (!$booking) {
    echo "<script>alert('No bookings found.'); window.location.href='home2.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Booking Summary</title>
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
            width: 500px;
            margin: auto;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 12px rgba(255,255,255,0.1);
        }
        h2 {
            color: #AF282F;
        }
        p {
            font-size: 16px;
            margin: 8px 0;
        }
        .back-btn {
            background-color: #AF282F;
            color: white;
            border: none;
            padding: 10px 20px;
            margin-top: 20px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 15px;
        }
        .back-btn:hover {
            background-color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Booking Summary</h2>
        <p><strong>Movie:</strong> <?php echo htmlspecialchars($booking['title']); ?></p>
        <p><strong>Seat:</strong> <?php echo htmlspecialchars($booking['seat_number']); ?></p>
        <p><strong>Date:</strong> <?php echo htmlspecialchars($booking['show_date']); ?></p>
        <p><strong>Time:</strong> <?php echo htmlspecialchars($booking['show_time']); ?></p>
        <p><strong>Price:</strong> ₱<?php echo number_format($booking['price'], 2); ?></p>
        <p><strong>Payment Method:</strong> <?php echo ucfirst($booking['payment_method']); ?></p>
        <p><strong>Payment Status:</strong> <?php echo ucfirst($booking['status']); ?></p>
        <p><strong>Booked On:</strong> <?php echo $booking['booking_date']; ?></p>

        <button class="back-btn" onclick="window.location.href='home2.php'">Back to Home</button>
    </div>
</body>
</html>
