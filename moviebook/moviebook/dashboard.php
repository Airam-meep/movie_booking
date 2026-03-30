<?php
session_start();
include "connect.php";

// Check if the user is logged in and is an admin
if (!isset($_SESSION["user"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php"); // Redirect to login page if not an admin
    exit();
}

// Fetch user data (for example, listing all users)
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

// Handle role updates (promote/demote users)
if (isset($_POST['update_role'])) {
    $user_id = $_POST['user_id'];
    $new_role = $_POST['new_role'];
    
    $update_sql = "UPDATE users SET role='$new_role' WHERE id=$user_id";
    if ($conn->query($update_sql)) {
        echo "<script>alert('User role updated successfully');</script>";
    } else {
        echo "<script>alert('Error updating user role');</script>";
    }
}

// Handle user deletion
if (isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];
    
    $delete_sql = "DELETE FROM users WHERE id=$user_id";
    if ($conn->query($delete_sql)) {
        echo "<script>alert('User deleted successfully');</script>";
    } else {
        echo "<script>alert('Error deleting user');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin User Dashboard</title>
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

        .user-list {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }

        .user-list th, .user-list td {
            border: 1px solid #444;
            padding: 10px;
            text-align: center;
        }

        .user-list th {
            background-color: #AF282F;
            color: white;
        }

        .action-btn {
            padding: 5px 10px;
            background-color: #FFD700;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            margin: 3px;
        }

        .action-btn:hover {
            background-color: #FF6347;
        }

        .role-select {
            padding: 5px;
            border-radius: 5px;
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
        <h1>Admin User Dashboard</h1>

        <table class="user-list">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['username'] . "</td>";
                        echo "<td>" . ucfirst($row['role']) . "</td>";
                        echo "<td>
                                <form method='post'>
                                    <button class='action-btn' onclick='alert(\"User " . $row['username'] . " details\")'>View</button>
                                    <button class='action-btn' type='submit' name='delete_user' value='1' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Delete</button>
                                    <input type='hidden' name='user_id' value='" . $row['id'] . "' />
                                    <select name='new_role' class='role-select'>
                                        <option value='user' " . ($row['role'] == 'user' ? 'selected' : '') . ">User</option>
                                        <option value='admin' " . ($row['role'] == 'admin' ? 'selected' : '') . ">Admin</option>
                                    </select>
                                    <button class='action-btn' type='submit' name='update_role'>Update Role</button>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No users found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>
