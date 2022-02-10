<?php
    include 'config.php';
    //error_reporting(0);
    session_start();
    if (!isset($_SESSION['username'])) { //if user went here without login
        echo "<script>window.location.href='login.php;</script>";
        header("Location: login.php");
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>

<?php
    include 'config.php';
    //error_reporting(0);
    if(isset($_POST['submit'])){
        $username = mysqli_real_escape_string($conn, test_input($_SESSION['username']));
        //$username = $_SESSION['username'];
        $bio = mysqli_real_escape_string($conn, test_input($_POST['bio']));
        //$bio = test_input($_POST["bio"]);
        $sql = "UPDATE users SET bio = '$bio' WHERE username = '$username'";
        $result = mysqli_query($conn, $sql);
    }
?>

<?php
    if(isset($_POST['upload'])) {
        $img_name = $_FILES['image']['name'];
        $img_type = $_FILES['image']['type'];
        $tmp_name = $_FILES['image']['tmp_name'];
        $img_explode = explode('.',$img_name);
        $img_ext = end($img_explode);
        $extensions = ["jpeg", "png", "jpg"];
        if(in_array($img_ext, $extensions) === true){
            $types = ["image/jpeg", "image/jpg", "image/png"];
            if(in_array($img_type, $types) === true){
                $time = time();
                $new_img_name = test_input($time.$img_name);
                if(move_uploaded_file($tmp_name,"images/".$new_img_name)){
                    $username = $_SESSION['username'];

                    // delete the previous image
                    $sql = "SELECT * FROM users WHERE username='$username'";  
                    $result = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_row($result);
                    // Free result set
                    unset($result);
                    if($row[5]!='default.png'){
                        unlink("./images/$row[5]");
                    }
                    
                    $insert_query = mysqli_query($conn, "UPDATE  users SET img = '$new_img_name' WHERE username = '$username'");
                }
            }else{
                echo "Please upload an image file - jpeg, png, jpg";
            }
        }else{
            echo "Please upload an image file - jpeg, png, jpg";
        }
        header("Location: edit.php");
    }
?>

<?php
    if(isset($_POST['public'])) {
        $username = $_SESSION['username'];
        $sql = "SELECT * FROM users WHERE username='$username'";  
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_row($result);
        // Free result set
        unset($result);
        if($row[6]==0){
            $insert_query = mysqli_query($conn, "UPDATE  users SET public = 1 WHERE username = '$username'");
        }else{
            $insert_query = mysqli_query($conn, "UPDATE  users SET public = 0 WHERE username = '$username'");
        }
        header("Location: edit.php");
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
    <title>Edit Profile | Web Pigeon</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400&display=swap" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Bree Serif' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/popup.css">
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
                <div>
                    <p>
                        <?php
                            if($row[6]==0){
                                echo "Your profile is set to private, Private profiles are NOT displayed in search page.";
                            }else{
                                echo "Your profile is set to public, Public profiles are displayed in search page.";
                            }
                        ?>
                    </p>
                </div>
                <div class="profile-btns">
                    <form method="POST" action="" enctype="multipart/form-data">
                        <button type="button" id="myBtn"><i class="fa fa-plus-circle"></i> Upload Picture</button>
                        
                        <button type="submit" name="public">
                            <i class="fa fa-users"></i> Set Profile As
                            <?php
                                if($row[6]==0){
                                    echo "Public";
                                }else{
                                    echo "Private";
                                }
                            ?>
                        </button>

                        <div style="margin-top:12px">
                            <a href="profile.php"><i class="fa fa-arrow-left"></i> Back To Profile</a>
                        </div>
                        <!-- The Modal -->
                        <div id="myModal" class="modal">

                            <!-- Modal content -->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <span class="close">&times;</span>
                                </div>
                                <div class="modal-body">
                                    <input type="file" name="image" id="file_btn" class="imgfile" accept="image/x-png,image/gif,image/jpeg,image/jpg" hidden>
                                    <button type="button" class="btn" id="choose_btn" onclick="click_file()">Choose From Device</button><br>
                                    <button type="submit" name="upload" class="next action-button" >Upload</button> <button type="button" name="previous" class="previous action-button-previous" onclick="modal.style.display = 'none'">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <section class="container edit">
            <section>
                <form action="" method="POST" autocomplete="off">
                    <h1>Edit Profile</h1>
                    <p>Change your account information.</p>
                    <label>Username can't be changed.</label>
                    <input type="text" class="txt" placeholder="Username" value="<?php echo $row[1]?>" name="username" disabled>
                    <label>Enter your new bio:</label>
                    <input type="text" class="txt" placeholder="Bio" value="<?php echo $row[4]?>" name="bio">
                    <label>Email can't be changed.</label>
                    <input type="email" class="txt" placeholder="Email" value="<?php echo $row[2]?>" name="email" disabled>
                    <button name="submit" class="btn">Save</button>
                </form>
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
<script src="js/popup.js"></script>
<script src="./js/nav.js"></script>
</html>