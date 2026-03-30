<?php
session_start();
include "connect.php";

if (!isset($_SESSION["user"]) || $_SESSION["role"] !== "user") {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['booking_id'])) {
    $booking_id = $_POST['booking_id'];

    // Get seat_id before deleting
    $stmt = $conn->prepare("SELECT seat_id FROM bookings WHERE id = ?");
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $seat = $result->fetch_assoc();
    $seat_id = $seat['seat_id'] ?? null;
    $stmt->close();

    if ($seat_id) {
        // Unreserve the seat
        $stmt = $conn->prepare("UPDATE seats SET is_reserved = 0, user_id = NULL WHERE id = ?");
        $stmt->bind_param("i", $seat_id);
        $stmt->execute();
        $stmt->close();
    }

    // Delete the booking completely
    $stmt = $conn->prepare("DELETE FROM bookings WHERE id = ?");
    $stmt->bind_param("i", $booking_id);
    if ($stmt->execute()) {
        echo "<script>alert('Booking cancelled and deleted successfully.'); window.location.href='my_bookings.php';</script>";
    } else {
        echo "<script>alert('Error cancelling booking.'); window.location.href='confirm2.php';</script>";
    }
    $stmt->close();

} else {
    header("Location: confirm2.php");
    exit();
}
?>
