<?php
session_start();
include "connect.php";

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

// Get user ID from session or query
if (!isset($_SESSION['user_id'])) {
    $username = $_SESSION['user'];
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $_SESSION['user_id'] = $row['id'];
    } else {
        echo "<script>alert('User not found.'); window.location.href='login.php';</script>";
        exit();
    }
    $stmt->close();
}

$userId = $_SESSION['user_id'];

// Ensure movie_id is in session
if (isset($_GET['movie_id'])) {
    $_SESSION['movie_id'] = $_GET['movie_id'];
}

if (!isset($_SESSION['movie_id'])) {
    echo "<script>alert('Movie not selected.'); window.location.href='home2.php';</script>";
    exit();
}

$movieId = $_SESSION['movie_id'];

// Fetch taken seats for display
$takenSeats = [];
$stmt = $conn->prepare("SELECT seat_number FROM seats WHERE movie_id = ? AND is_reserved = 1");
$stmt->bind_param("i", $movieId);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $takenSeats[] = $row['seat_number'];
}
$stmt->close();

// Handle booking
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['seat_number'])) {
    $selectedSeat = $_POST['seat_number'];

    // Check if seat exists
    $stmt = $conn->prepare("SELECT id FROM seats WHERE movie_id = ? AND seat_number = ?");
    $stmt->bind_param("is", $movieId, $selectedSeat);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $seat = $result->fetch_assoc();
        $seatId = $seat['id'];

        // Ensure not already reserved
        $check = $conn->prepare("SELECT * FROM seats WHERE id = ? AND is_reserved = 1");
        $check->bind_param("i", $seatId);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            echo "<script>alert('Seat already taken.'); window.location.href='seats2.php';</script>";
            exit();
        }

        // Reserve it
        $update = $conn->prepare("UPDATE seats SET is_reserved = 1, user_id = ? WHERE id = ?");
        $update->bind_param("ii", $userId, $seatId);
        $update->execute();
    } else {
        // Insert new seat
        $insert = $conn->prepare("INSERT INTO seats (movie_id, seat_number, is_reserved, user_id) VALUES (?, ?, 1, ?)");
        $insert->bind_param("isi", $movieId, $selectedSeat, $userId);
        $insert->execute();
        $seatId = $insert->insert_id;
    }

    // Add booking
    $booking = $conn->prepare("INSERT INTO bookings (user_id, movie_id, seat_id, payment_status) VALUES (?, ?, ?, 'pending')");
    $booking->bind_param("iii", $userId, $movieId, $seatId);
    $booking->execute();

    // Store session for payment
    $_SESSION['selected_seat'] = $selectedSeat;
$_SESSION['seat_id'] = $seatId;
$_SESSION['movie_id'] = $movieId;
$_SESSION['user_id'] = $userId;


    // ✅ Redirect to payment
    header("Location: paymentsss2.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Select Seats</title>
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
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
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
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .screen {
            background: gray;
            width: 80%;
            height: 40px;
            text-align: center;
            line-height: 40px;
            font-weight: bold;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .seats {
            display: grid;
            grid-template-columns: repeat(10, 40px);
            gap: 10px;
        }
        .seat {
            width: 40px;
            height: 40px;
            background: #444;
            border-radius: 5px;
            text-align: center;
            line-height: 40px;
            color: white;
            cursor: pointer;
        }
        .seat:hover {
            background: #666;
        }
        .taken {
            background: red;
            cursor: not-allowed;
        }
        .selected {
            background: green !important;
        }
    </style>
</head>
<body>
<nav class="navbar">
    <h1>Welcome, <?php echo $_SESSION["user"]; ?>!</h1>
    <div class="nav-links">
        <a href="home2.php">Movies</a>
        
        <a href="confirm2.php">My Bookings</a>
        <a href="search2.php">Search Movies</a>
        
    </div>
</nav>

<div class="container">
    <div class="screen">SCREEN</div>
    <form id="seatForm" method="POST" action="seats2.php">
        <div class="seats">
            <?php for ($i = 1; $i <= 50; $i++): 
                $seatNum = "S" . $i;
                $takenClass = in_array($seatNum, $takenSeats) ? 'taken' : '';
            ?>
                <!-- Making the seat clickable by adding the event handler -->
                <div class="seat <?php echo $takenClass; ?>" 
                     id="seat_<?php echo $seatNum; ?>" 
                     data-seat="<?php echo $seatNum; ?>"
                     onclick="selectSeat(this)">
                     <?php echo $seatNum; ?>
                </div>
            <?php endfor; ?>
        </div>

        <!-- Confirm Button -->
        <button type="button" id="confirmButton" onclick="confirmSeat()" style="margin-top: 20px; padding: 10px 20px; background-color: #AF282F; border-radius: 5px; color: white; border: none; cursor: pointer;">
            Confirm Seat
        </button>
    </form>
</div>

<script>
    let selectedSeat = null;

    function selectSeat(seatElement) {
        // Reset the previous selected seat, if any
        if (selectedSeat) {
            selectedSeat.classList.remove('selected');
        }
        
        // Mark the new selected seat
        selectedSeat = seatElement;
        selectedSeat.classList.add('selected');
    }

    function confirmSeat() {
        // Ensure that a seat has been selected
        if (!selectedSeat) {
            alert('Please select a seat first!');
            return;
        }

        // Get the seat number
        const seatNumber = selectedSeat.getAttribute('data-seat');

        // Popup confirmation dialog
        let confirmAction = confirm("You have selected " + seatNumber + ". Are you sure you want to confirm this seat?");
        if (confirmAction) {
            // Store the selected seat in the form and submit it
            const seatInput = document.createElement('input');
            seatInput.type = 'hidden';
            seatInput.name = 'seat_number';
            seatInput.value = seatNumber;
            document.getElementById('seatForm').appendChild(seatInput);
            document.getElementById('seatForm').submit();
        } else {
            // If the user cancels, reset the selection
            selectedSeat.classList.remove('selected');
            selectedSeat = null;
        }
    }
</script>

</body>
</html>
