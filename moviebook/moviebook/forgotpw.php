<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            width: 100%;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-image: url('bgg.jpg');

        }

        input{
            width: 370px;
            height: 30px;
            margin: 10px 0;
            background-color: #E6E1E7;
            border: none;
            padding: 8px;
            border-radius: 8px;
        }
        button {
            width: 370px;
            height: 40px;
            margin-top: 10px;
            color: white;

            background-color: #AF282F;
            cursor: pointer;
            transition-duration: 0.4s;
            border-radius: 12px;
            border: none;
        }
        button:hover {
        background-color: red;
        }
        
        .logtext {
        font-size: 17px;
            text-align: center;
            color: #E6E1E7;
            margin-bottom: 10px;
        }
        .link-text {
            color: #E6E1E7;
            font-size: 14px;
            margin-top: 10px;
            }
        .link-text a {
            color: #AF282F;
            text-decoration: none;
            font-weight: bold;
        }

        .link-text a:hover {
            text-decoration: underline;
        }


        .container {
            background: #2C3D49;
            padding: 30px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logtext"><h1>Reset Password</h1></div>
        <div class="logtext"><h1>!RESET PW IS JUST SIMULATION!</h1></div>
        <form action="" method="POST">
            <!--input boxes -->
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="text" name="code" placeholder="Code" required><br>
            <button type="submit">Reset Password</button>
        </form>
       
    </div>
</body>
</html>

<?php
include "connect.php";
// get inputs sa input box
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uname = $_POST['username'];
    $email = $_POST['email'];
    $pw = $_POST['password'];
// inserts them sa db
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $uname, $email, $pw);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful!'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('An error occurred: " . $conn->error . "');</script>";
    }

    $stmt->close();
}
?>
