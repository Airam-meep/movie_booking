<?php
session_start();
include "connect.php";

// Fetch search and filter values
$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$filter_date = isset($_GET['filter_date']) ? $_GET['filter_date'] : "";
$sort = isset($_GET['sort']) ? $_GET['sort'] : "title_asc";

// Build the SQL query dynamically
$query = "SELECT * FROM movies WHERE 1=1";

// Apply search filter (by title)
if (!empty($search)) {
    $query .= " AND title LIKE '%$search%'";
}

// Apply date filter
if (!empty($filter_date)) {
    $query .= " AND show_date = '$filter_date'";
}

// Apply sorting
if ($sort === "title_asc") {
    $query .= " ORDER BY title ASC";
} elseif ($sort === "title_desc") {
    $query .= " ORDER BY title DESC";
} elseif ($sort === "date_asc") {
    $query .= " ORDER BY show_date ASC, show_time ASC";
} elseif ($sort === "date_desc") {
    $query .= " ORDER BY show_date DESC, show_time DESC";
}

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search & Filters</title>
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
            padding: 20px;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }

        .search-box {
            display: flex;
            gap: 10px;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
        }

        input, select, button {
            padding: 8px;
            font-size: 16px;
            border-radius: 5px;
            border: none;
        }

        button {
            background: #AF282F;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background: red;
        }

        .movies-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .movie-card {
            background: #222;
            padding: 15px;
            border-radius: 10px;
            margin: 10px;
            width: 250px;
            text-align: left;
            box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.2);
        }

        .movie-card img {
            width: 100%;
            height: 180px;
            border-radius: 10px;
            object-fit: cover;
        }

        .movie-card h3 {
            margin: 10px 0;
            color: #AF282F;
        }

        .movie-card p {
            font-size: 14px;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
    <h1>Welcome, <?php echo $_SESSION["user"]; ?>!</h1>
    <div class="nav-links">
        <a href="home2.php">Movies</a>

        <a href="confirm2.php">My Bookings</a>
        <a href="search2.php">Search Movies</a>
        
    </div>
</nav>

    <div class="container">
        <h2>Search & Filters</h2>

        <form method="GET" action="search.php">
            <div class="search-box">
                <input type="text" name="search" placeholder="Search movie title..." value="<?php echo $search; ?>">
                <input type="date" name="filter_date" value="<?php echo $filter_date; ?>">
                <select name="sort">
                    <option value="title_asc" <?php if ($sort === "title_asc") echo "selected"; ?>>Title A-Z</option>
                    <option value="title_desc" <?php if ($sort === "title_desc") echo "selected"; ?>>Title Z-A</option>
                    <option value="date_asc" <?php if ($sort === "date_asc") echo "selected"; ?>>Earliest Show</option>
                    <option value="date_desc" <?php if ($sort === "date_desc") echo "selected"; ?>>Latest Show</option>
                </select>
                <button type="submit">Search</button>
            </div>
        </form>

        <div class="movies-list">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='movie-card'>
                            <img src='images/{$row['poster']}' alt='{$row['title']}'>
                            <h3>{$row['title']}</h3>
                            <p>{$row['description']}</p>
                            <p><strong>Date:</strong> {$row['show_date']} | <strong>Time:</strong> {$row['show_time']}</p>
                          </div>";
                }
            } else {
                echo "<p>No movies found.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
