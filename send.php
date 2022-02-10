<?php
    include 'config.php';
    //error_reporting(0);
    if (isset($_POST['submit'])) {
        $receiver = $_POST['receiver'];
        $message = $_POST['message'];

        session_start();
        if (!isset($_SESSION['username'])) { //if user went here without login
            $sender = "000-unknown-000";
        }else{
            $sender = $_SESSION['username'];
        }

        $date = date("d/m/Y \t h:i:s A") . " (GMT+1)";

        $sql = "INSERT INTO messages (receiver, msg, sender, date)
                VALUES ('$receiver', '$message', '$sender', '$date')"; //add a row to the table
        $result = mysqli_query($conn, $sql);
        if ($result) {
            echo "<script>alert('Your message has been successfully sent.')</script>";
            header("Location: done.html");
        } else {
            echo "<script>alert('Woops! Something Wrong Went.')</script>";
        }
    }
?>

<?php
    if (isset($_GET['username'])) {
        $receiver = $_GET['username'];
    }else{
        header("Location: index.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta name="theme-color" content="#2b2b2b">
    <meta name="msapplication-navbutton-color" content="#2b2b2b">
    <meta name="apple-mobile-web-app-status-bar-style" content="#2b2b2b">
    <meta name="description" content="Web-Pigeon is a website where you can send and receive messages without knowing sender name.">
    <meta name="keywords" content="web-pigeon, Ahmed Saber, Full Stack Web Developer">
    <meta name="author" content="Ahmed Saber, ahmed0saber33@gmail.com">
    <meta name="og:title" content="Web Pigeon">
    <meta name="og:description" content="Web-Pigeon is a website where you can send and receive messages without knowing sender name.">
    
    <meta property="og:image" content="https://drive.google.com/u/0/uc?id=1cW0Xb57DLj7OZbntxsesjZrr4Gh9LLll&export=download">
    <link rel="icon" href="https://drive.google.com/u/0/uc?id=1cW0Xb57DLj7OZbntxsesjZrr4Gh9LLll&export=download" type="image/x-icon">

    <meta name="og:type" content="web-pigeon">
    <meta name="og:email" content="ahmed0saber33@gmail.com">
    <meta name="og:phone_number" content="+201208611892">
    <meta name="og:country-name" content="Egypt">

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Message | Web Pigeon</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400&display=swap" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Bree Serif' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <a><span>W</span>eb <span>P</span>igeon</a>
            </div>
            <div class="links">
                <div>
                    <a class="link" href="index.php"><i class="fa fa-home"></i> Home</a>
                    <a class="link" href="search.php"><i class="fa fa-search"></i> Search</a>
                    <a class="link" href="profile.php"><i class="fa fa-user"></i> Profile</a>
                </div>
            </div>
        </nav>
    </header>

    <?php
        $receiver = $_GET['username'];
        $sql = "SELECT * FROM users WHERE username='$receiver'";  
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_row($result);
        // Free result set
        unset($result);
    ?>

    <main class="container profile">
        <section class="user-data">
            <div class="img-container">
                <div class="cover"></div>
                <img src="images/<?php echo $row[5] ?>">
            </div>
            <div class="text-container">
                <div>
                    <h1><?php echo $row[1] ?></h1>
                    <p><?php echo $row[4] ?></p>
                </div>
            </div>
        </section>

        <section class="msg-receiver">
            <form action="" method="POST" autocomplete="off">
                <p>Enter your message here</p>
                <input type="text" name="receiver" value="<?php echo $row[1]; ?>" hidden>
                <textarea placeholder="Message" name="message"></textarea>
                <button name="submit" class="btn">Submit</button>
            </form>
        </section>
    </main>

    <footer class="page-footer">
        <div class="footer-copyright">
            <div class="by">
                <span>© 2022 Copyright : Ahmed Saber & Hassan El-Deghedy</span>
            </div>
            <div>
                <a href="#"><i class="fa fa-facebook"></i></a>
                <a href="#"><i class="fa fa-instagram"></i></a>
                <a href="#"><i class="fa fa-twitter"></i></a>
                <a href="#"><i class="fa fa-linkedin"></i></a>
                <a href="#"><i class="fa fa-github"></i></a>
            </div>
        </div>
    </footer>

    <div class="bottom-nav hide-on-large-only">
        <a class="" href="search.php"><i class="fa fa-search"></i></a>
        <a class="" href="index.php"><i class="fa fa-home"></i></a>
        <a class="" href="profile.php"><i class="fa fa-user"></i></a>
    </div>

    <button onclick="changeTheme()" class="btn-floating"><i class="fa fa-magic"></i></button>
</body>
<script src="./js/theme.js"></script>
</html>