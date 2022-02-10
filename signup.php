<?php
    include 'config.php';
    //error_reporting(0); //turn off error reporting
    session_start();

    if (isset($_SESSION['username'])) { //if logged in
        header("Location: login.php");
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $valid = true;
    if (isset($_POST['submit'])) { //because this page submits to itself
        $name = $nameErr = $email = $emailErr = "";
        if (empty($_POST["username"])) {
            $nameErr = "Name is required";
            $valid = false;
        }else{
            $name = mysqli_real_escape_string($conn, test_input($_POST['username']));
            //$name = test_input($_POST["username"]);
            // check if name only contains letters and whitespace
            if (!preg_match("/^[a-zA-Z0-9-' ]*$/",$name)) {
                $nameErr = "Only letters and numbers are allowed";
                $valid = false;
            }
            elseif (count(explode(' ', $name)) > 1) {
                $nameErr = "White spaces are not allowed";
                $valid = false;
            }
        }
        
        if (empty($_POST["email"])) {
            $emailErr = "Email is required";
            $valid = false;
        }else{
            $email = mysqli_real_escape_string($conn, test_input($_POST['email']));
            //$email = test_input($_POST["email"]);
            // check if e-mail address is well-formed
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailErr = "Invalid email format";
                $valid = false;
            }
        }
        
        if (empty($_POST["password"])) {
            $valid = false;
            echo '<script>alert("Password is required");</script>';
        }


        if($valid){
            $username = mysqli_real_escape_string($conn, test_input($_POST['username']));
            $email = mysqli_real_escape_string($conn, test_input($_POST['email']));
            $password = md5(test_input($_POST["password"]));
            $cpassword = md5(test_input($_POST["cpassword"]));

            if ($password == $cpassword) { //password confirmation
                $sql = "SELECT * FROM users WHERE username='$username'";
                $result = mysqli_query($conn, $sql);
                if (!$result->num_rows > 0) {
                    $sql = "INSERT INTO users (username, email, password, bio, img, public)
                            VALUES ('$username', '$email', '$password', 'Hello, I am using Web Pigeon.', 'default.png', 0)"; //add a row to the table
                    $result = mysqli_query($conn, $sql);
                    if ($result) {
                        $_SESSION['username'] = $username;
                        header("Location: profile.php");
                        /*echo "<script>window.location.href='myQuizzes.php;</script>";*/
                    } else {
                        echo "<script>alert('Woops! Something Wrong Went.')</script>";
                    }
                } else {
                    echo "<script>alert('Woops! Username Already Exists.')</script>";
                }
                
            } else {
                echo "<script>alert('Password Not Matched.')</script>";
            }
        }
        header("Location: signup.php");
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
    <title>Sign up | Web Pigeon</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400&display=swap" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Bree Serif' rel='stylesheet'>
    <link rel="stylesheet" href="./style.css">
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
                    <a class="link active" href="profile.php"><i class="fa fa-user"></i> Profile</a>
                </div>
            </div>
            <div class="nav-btn">
                <button type="button" onclick="toggle()"><i class="fa fa-bars nav-toggler"></i></button>
            </div>
        </nav>
    </header>

    <main class="container login">
        <section>
            <form action="" method="POST" autocomplete="off">
                <h1>Signup Form</h1>
                <p>Create a new account to continue.</p>
                <label>Enter your username:</label>
                <input type="text" class="txt" placeholder="Username" name="username">
                <label>Enter your email:</label>
                <input type="email" class="txt" placeholder="Email" name="email">
                <label>Enter your password:</label>
                <input type="password" class="txt" placeholder="Password" name="password">
                <label>Enter your password again:</label>
                <input type="password" class="txt" placeholder="Confirm Password" name="cpassword">
                <button name="submit" class="btn">Sign up</button>
            </form>
            <label>Already have account ? <a href="login.php">Log in</a></label><br>
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
        <a class="active" href="profile.php"><i class="fa fa-user"></i></a>
    </div>

    <button onclick="changeTheme()" class="btn-floating"><i class="fa fa-magic"></i></button>
</body>
<script src="./js/theme.js"></script>
<script src="./js/nav.js"></script>
</html>