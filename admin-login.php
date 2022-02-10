<?php 
    include 'config.php';
    session_start();
    
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    if (isset($_POST['admin'])) {
        $username = test_input($_POST["username"]); //ahmed0saber
        $email = test_input($_POST["email"]); //ahmed0saber33@gmail.com
        $password = test_input(md5($_POST['password']));

        $admin = "ahmed0saber";
        $sql = "SELECT * FROM users WHERE username = '$admin'";  
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_row($result);

        if($row[1] == $username and $row[2] == $email and $row[3] == $password){
            $_SESSION['username'] = $row[1];
            /*echo "<script>window.location.href='myQuizzes.php;</script>";*/
            header("Location: admin-users.php");
        } else {
            echo "<script>alert('Woops! Something is Wrong.')</script>";
            header("Location: admin-login.php");
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Web Pigeon</title>
    <link rel="stylesheet" href="./admin.css">
</head>
<body>
    <div>
        <h1>Welcome to Admin</h1>
        <form method="post" action="" autocomplete="off">
            <input type="text" name="username" placeholder="username"><br>
            <input type="email" name="email" placeholder="email"><br>
            <input type="password" name="password" placeholder="password"><br>
            <button type="submit" name="admin">Log in</button>
        </form>
    </div>
</body>
</html>