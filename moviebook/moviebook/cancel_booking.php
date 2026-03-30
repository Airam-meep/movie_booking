<?php
session_start();
include "connect.php";

if (!isset($_SESSION["user"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

$booking_id = $_GET['booking_id'] ?? null;

if ($booking_id) {
    // Get seat_id before deleting booking
    $stmt = $conn->prepare("SELECT seat_id FROM bookings WHERE id = ?");
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $seat = $result->fetch_assoc();
    $seat_id = $seat['seat_id'] ?? null;
    $stmt->close();

    // Unreserve the seat
    if ($seat_id) {
        $stmt = $conn->prepare("UPDATE seats SET is_reserved = 0, user_id = NULL WHERE id = ?");
        $stmt->bind_param("i", $seat_id);
        $stmt->execute();
        $stmt->close();
    }

    // Delete the booking
    $stmt = $conn->prepare("DELETE FROM bookings WHERE id = ?");
    $stmt->bind_param("i", $booking_id);
    if ($stmt->execute()) {
        echo "<script>alert('Booking cancelled and deleted.'); window.location.href='confirm.php';</script>";
    } else {
        echo "<script>alert('Failed to delete booking.'); window.location.href='confirm.php';</script>";
    }
    $stmt->close();

} else {
    echo "<script>alert('Invalid booking ID.'); window.location.href='confirm.php';</script>";
}
?>
