<?php
session_start();
include "connect.php";

$selectedSeat = $_SESSION['selected_seat'] ?? null;
$movieId = $_SESSION['movie_id'] ?? null;
$userId = $_SESSION['user_id'] ?? null;
$seatId = $_SESSION['seat_id'] ?? null;

if (!$selectedSeat || !$movieId || !$userId || !$seatId) {
    echo "<script>alert('Session expired. Please reselect your seat.'); window.location.href='home2.php';</script>";
    exit();
}

// Fetch movie price
$stmt = $conn->prepare("SELECT title, price FROM movies WHERE id = ?");
$stmt->bind_param("i", $movieId);
$stmt->execute();
$result = $stmt->get_result();
$movie = $result->fetch_assoc();
$stmt->close();

$price = $movie['price'] ?? 0;
$movieTitle = $movie['title'] ?? 'Unknown';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment</title>
    <style>
        body {
            background-image: url('bgg.jpg');
            color: #E6E1E7;
            font-family: 'Segoe UI', sans-serif;
            text-align: center;
            padding-top: 100px;
        }
        .container {
            background: #2C3D49;
            padding: 40px;
            margin: auto;
            width: 420px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.1);
        }
        .btn {
            background-color: #AF282F;
            color: white;
            border: none;
            padding: 12px 24px;
            margin-top: 20px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn:hover {
            background-color: red;
        }
        select, input[type=text] {
            padding: 10px;
            width: 100%;
            margin-top: 10px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        h2 {
            color: #AF282F;
            margin-bottom: 20px;
        }
        .label {
            text-align: left;
            margin-top: 15px;
            font-size: 14px;
            color: #ccc;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Confirm Your Payment</h2>
        <p><strong>Movie:</strong> <?php echo htmlspecialchars($movieTitle); ?></p>
        <p><strong>Seat:</strong> <?php echo htmlspecialchars($selectedSeat); ?></p>
        <p><strong>Total Price:</strong> ₱<?php echo number_format($price, 2); ?></p>

        <form action="confirm_booking.php" method="POST">
            <input type="hidden" name="seat_id" value="<?php echo $seatId; ?>">
            <input type="hidden" name="movie_id" value="<?php echo $movieId; ?>">
            <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
            <input type="hidden" name="amount" value="<?php echo $price; ?>">

            <div class="label">Select Payment Method:</div>
            <select name="payment_method" required>
                <option value="credit_card">Credit Card</option>
                <option value="paypal">PayPal</option>
                <option value="gcash">GCash</option>
                <option value="cash">Cash</option>
            </select>

            <button type="submit" class="btn">Pay Now</button>
        </form>
    </div>
</body>
</html>
