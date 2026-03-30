<?php
session_start();
if (!isset($_SESSION["user"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}
?>
<nav class="navbar">
    <div class="welcome-message">Welcome, <?php echo $_SESSION["user"]; ?> (Admin)</div>
    <div class="nav-links">
        <a href="home.php">Manage Movies</a>
        <a href="seats.php">Manage Seats</a>
        <a href="confirm.php">Booking Confirmations</a>
        <a href="dashboard.php">User Dashboard</a>
        <a href="search.php">Search & Filters</a>
        <a href="payment.php">Payments</a>
    </div>
</nav>

<style>
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
</style>
