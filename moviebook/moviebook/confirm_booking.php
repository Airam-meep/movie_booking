<?php
session_start();
include "connect.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_POST['user_id'] ?? null;
    $movie_id = $_POST['movie_id'] ?? null;
    $seat_id = $_POST['seat_id'] ?? null;
    $payment_method = $_POST['payment_method'] ?? null;
    $amount = $_POST['amount'] ?? null;

    if ($user_id && $movie_id && $seat_id && $payment_method && $amount) {
        // Insert into bookings
        $stmt = $conn->prepare("INSERT INTO bookings (user_id, movie_id, seat_id, payment_status) VALUES (?, ?, ?, 'pending')");
        $stmt->bind_param("iii", $user_id, $movie_id, $seat_id);

        if ($stmt->execute()) {
            $booking_id = $conn->insert_id;

            // Insert into payments
            $stmt2 = $conn->prepare("INSERT INTO payments (booking_id, amount, payment_method, status) VALUES (?, ?, ?, 'pending')");
            $stmt2->bind_param("ids", $booking_id, $amount, $payment_method);

            if ($stmt2->execute()) {
                echo "<script>alert('Booking confirmed successfully!'); window.location.href='home2.php';</script>";
                exit();
            } else {
                echo "Error saving payment: " . $stmt2->error;
            }

            $stmt2->close();
        } else {
            echo "Error saving booking: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "<script>alert('Incomplete booking info.'); window.location.href='home2.php';</script>";
        exit();
    }
} else {

    header("Location: home2.php");
    exit();
}
?>



<!DOCTYPE html>
<html>
<head>
    <title>Confirm Booking</title>
    <style>
        body {
            background-color: #111820;
            color: #E6E1E7;
            font-family: Arial, sans-serif;
            text-align: center;
            padding-top: 100px;
        }
        .summary-box {
            background-color: #222;
            padding: 30px;
            border-radius: 10px;
            display: inline-block;
        }
        button {
            background-color: #AF282F;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        button:hover {
            background-color: red;
        }
    </style>
</head>
<body>
    <div class="summary-box">
        <h2>Booking Summary</h2>
        <p><strong>Movie:</strong> <?php echo $movie['title']; ?></p>
        <p><strong>Seat:</strong> <?php echo $seatNumber; ?></p>
        <p><strong>Price:</strong> ₱<?php echo number_format($movie['price'], 2); ?></p>
        <p><strong>Payment Method:</strong> <?php echo ucfirst($paymentMethod); ?></p>
        <form method="POST">
            <button type="submit">Confirm Booking</button>
        </form>
    </div>
</body>
</html>
