<?php
session_start();
include "connect.php";

if (!isset($_SESSION["user"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

$booking_id = $_GET['booking_id'] ?? null;
$status = $_GET['status'] ?? null;

$validStatuses = ['paid', 'pending', 'cancelled'];

if ($booking_id && in_array($status, $validStatuses)) {
    $stmt = $conn->prepare("UPDATE bookings SET payment_status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $booking_id);
    if ($stmt->execute()) {
        echo "<script>alert('Booking status updated to $status'); window.location.href='confirm.php';</script>";
    } else {
        echo "<script>alert('Error updating status.'); window.location.href='confirm.php';</script>";
    }
    $stmt->close();
} else {
    echo "<script>alert('Invalid request.'); window.location.href='confirm.php';</script>";
}
?>
