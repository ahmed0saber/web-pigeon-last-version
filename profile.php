<?php
    include 'config.php';
    //error_reporting(0);
    session_start();
    if (!isset($_SESSION['username'])) { //if user went here without login
        echo "<script>window.location.href='login.php;</script>";
        header("Location: login.php");
    }
?>

<?php
    include 'config.php';
    //error_reporting(0);
    if (isset($_POST['fav'])) {

        $msg_id = $_POST['msg_id'];
        $sql = "SELECT * FROM messages WHERE id='$msg_id'";  
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_row($result);
        // Free result set
        unset($result);
        
        if($row[5]==0){
            $sql = "UPDATE messages SET fav = 1 WHERE id = '$msg_id'";
        }else{
            $sql = "UPDATE messages SET fav = 0 WHERE id = '$msg_id'";
        }
        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo "<script>alert('Your message has been successfully added to favourite.')</script>";
            header("Location: profile.php");
        } else {
            echo "<script>alert('Woops! Something Wrong Went.')</script>";
        }
    }
?>

<?php
    $username = $_SESSION['username'];
    $sql = "SELECT * FROM users WHERE username='$username'";  
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_row($result);
    // Free result set
    unset($result);
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
    <title>Profile | Web Pigeon</title>
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
                    <a class="link active" href="profile.php"><i class="fa fa-user"></i> Profile</a>
                </div>
            </div>
            <div class="nav-btn">
                <button type="button" onclick="toggle()"><i class="fa fa-bars nav-toggler"></i></button>
            </div>
        </nav>
    </header>

    <main class="container profile">
        <section class="user-data">
            <div class="img-container">
                <div class="cover"></div>
                <img src="images/<?php echo $row[5] ?>">
            </div>
            <div class="text-container">
                <div>
                    <h1><?php echo $row[1]?></h1>
                    <p><?php echo $row[4]?></p>
                </div>
                <div class="profile-btns">
                    <a href="edit.php">Edit Profile</a>
                    <a href="logout.php">Log out</a>
                    <a class="copylink" onclick="navigator.clipboard.writeText(`
                        <?php
                            $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                            $url = explode('/', $actual_link);
                            array_pop($url);
                            $url2=implode("/",$url) ;
                            $url3 = '/send.php?username='.$row[1];
                            echo $url2 . $url3;
                        ?>
                        `);alert('Link to profile has been copied.');">Copy Link
                    </a>
                </div>
            </div>
        </section>

        <section class="messages">
            <h2>
                <?php
                    $username = $_SESSION['username'];
                    $sql = "SELECT * FROM messages WHERE receiver='$username'";  
                    $result = mysqli_query($conn, $sql);
                    $i = 0;
                    while($row = mysqli_fetch_row($result)){
                        $i++;
                    }
                    // Free result set
                    unset($result);
                    echo $i;
                ?>
                Messages Recieved
            </h2>
            <section class="rec-or-fav">
                <button onclick="show_rec()">Recieved</button>
                <button onclick="show_fav()">Favourite</button>
            </section>
            <section>
                <form action="#" method="" autocomplete="off">
                    <i class='fa fa-search icon'></i>
                    <input type="text" name="search" placeholder="some letters you remmember from the message" id="searchBar2" onkeyup="filterFunction2()">
                </form>
            </section>
            <section class="messages-container" id="myMenu2">
                <form action="" method="POST" autocomplete="off">
                    <input type="text" name="msg_id" id="fav_form_txt" value="0" hidden>
                    <input type="submit" name="fav" id="fav_form_btn" value="not here" hidden>
                </form>
                <?php
                    $username = $_SESSION['username'];
                    $sql = "SELECT * FROM messages WHERE receiver='$username' ORDER BY id DESC";  
                    $result = mysqli_query($conn, $sql);
                    while($row = mysqli_fetch_row($result)){
                        
                        if($row[5]==1){
                            $fav = "faved";
                        }else{
                            $fav = "not-fav";
                        }

                        echo '
                            <div class="msg">
                                <span class="msg-num">'.$i.'</span>
                                <i class="fa fa-star ' . $fav . '" onclick="add_to_fav(this, ' . $row[0] . ')"></i>
                                <p>'. $row[3].'</p>
                                <p class="msg-date">'.$row[4].'</p>
                            </div>
                            ';
                        $i--;
                    }
                    // Free result set
                    unset($result);
                ?>
            </section>
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
<script src="./js/search.js"></script>
<script src="./js/fav.js"></script>
<script src="./js/nav.js"></script>
</html>