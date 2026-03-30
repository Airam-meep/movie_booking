<!DOCTYPE html>
<html>
    <body>
        <?php
        include "connect.php";
        session_start();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $uname = $_POST['uname'];
            $pw = $_POST['pw'];

            $sql = "SELECT * FROM users WHERE username=?"; //will select ung users sa db with the entred uname
            $stmt= $conn->prepare($sql);
            $stmt->bind_param("s", $uname); //will give values sa placeholders
            $stmt->execute(); 
            $result = $stmt->get_result();
            $user= $result->fetch_assoc();

            if($user && $pw === $user['password']) { //checks if user exists in db and checks if pw matches
                $_SESSION["user"] = $user['username'];
                $_SESSION["role"] = $user['role'];

                if ($user['role'] === "admin"){
                    header("Location: home.php");
                } else {
                    header("Location: home2.php");
                }
                exit();
            } else{
                echo "Invalid credentials!";
            }

            $stmt->close();
        }
        ?>
        
        <style>
    body {
        width: 100%;
        height: 100vh;
        background-color: #111820;
        overflow: hidden;
    }

    .pos {
            width: 40%;
            position: absolute;
            top: 100px;
            margin-left: 700px;
            text-align: center;
        }

    input{
        width: 320px;
        height: 30px;
        margin: 8px;
        background-color: #E6E1E7;
        border-radius: 8px;
    }

    button {
        width: 120px;
        height: 40px;
        margin: 10px;
        position: relative;
        left: 16px;
        color: white;
        background-color: #AF282F;
        cursor: pointer;
        transition-duration: 0.4s;
        border-radius: 12px;
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
    .swiper {
        position: fixed;
        right: 391px;
        bottom: 25px;
        width: 600px;
        height: 640px;
    }

    .swiper-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: 75% 50%;
    }

    .swiper::after {
        content: "";
        position: absolute;
        top: 0;
        right: 0;
        width: 400px;
        height: 100%;
        background: linear-gradient(to left, #111820, transparent);
        pointer-events: none;
        z-index: 2;
    }

    

    .link-text {
        color: #E6E1E7;
        font-size: 14px;
        margin-top: 10px;
        text-align: center;
    }

    .link-text a {
        color: #AF282F;
        text-decoration: none;
        font-weight: bold;
        margin-right: 10px;
    }
    .link-container {
        display: flex;
        justify-content: center;
        gap: 10px;
    }

    .link-text a:hover {
        text-decoration: underline;
    }

    
    </style>

    <html lang="en">
    <head>
        <title>Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    </head>
    <body>
        <div class="pos">
        <div class="logtext"><h1>Sign in to your account</h1></div>
            <form action="" method="POST">

                    <input type="text" name="uname" placeholder="Username" required><br>
                <input type="password" name="pw" placeholder="Password" required><br>
                
                <!-- link text -->
                <div class="link-container">
                <p class="link-text">Don't have an account? <a href="register.php">Sign up</a></p>
                <p class="link-text"> <a href="forgotpw.php">Forgot Password?</a></p>
            </div>
                
                <button type="submit">Sign in</button>
            </form>
        </div>

        <!--swiper pics -->
        <?php
include "connect.php";
$slider = $conn->query("SELECT * FROM slider_images");
?>

<div class="swiper">
    <div class="swiper-wrapper">
        <?php if ($slider->num_rows > 0): ?>
            <?php while ($row = $slider->fetch_assoc()): ?>
                <div class="swiper-slide">
                    <?php if (!empty($row['filename']) && file_exists($row['filename'])): ?>
                        <img src="<?php echo htmlspecialchars($row['filename']); ?>" alt="Slider Image">
                    <?php else: ?>
                        <div style="width: 100%; height: 100%; background-color: #333; display: flex; align-items: center; justify-content: center; color: #E6E1E7; font-size: 20px;">
                            Image missing. Update via Manage Slider.
                        </div>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="swiper-slide">
                <div style="width: 100%; height: 100%; background-color: #333; display: flex; align-items: center; justify-content: center; color: #E6E1E7; font-size: 20px;">
                    No images yet. Add some from Manage Slider.
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-pagination"></div>
</div>


        <!-- bootsrap and swipe code-->
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
        <script>
            var swiper = new Swiper(".swiper", {
                loop: true,
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
            });
        </script>
    </body>
    </html>









