<?php
    include 'config.php';
    session_start();
    $admin = "ahmed0saber";
    if($_SESSION['username'] != $admin){
        header("Location: admin-login.php");
    }
    session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Users | Web Pigeon</title>
    <link rel="stylesheet" href="./admin.css">
</head>
<body>
    <section>
        <div>
            <table>
                <thead>
                    <th>id</th>
                    <th>username</th>
                    <th>email</th>
                    <th>password</th>
                    <th>bio</th>
                    <th>img</th>
                    <th>public</th>
                </thead>
                <?php
                    $sql = "SELECT * FROM users ORDER BY id";  
                    $result = mysqli_query($conn, $sql);
                    while($row = mysqli_fetch_row($result)){
                        echo '
                        <tr>
                            <td>'. $row[0] .'</td>
                            <td>'. $row[1] .'</td>
                            <td>'. $row[2] .'</td>
                            <td>'. $row[3] .'</td>
                            <td>'. $row[4] .'</td>
                            <td>'. $row[5] .'</td>
                            <td>'. $row[6] .'</th>
                        </tr>
                        ';
                    }
                    // Free result set
                    unset($result);
                ?>
            </table>
        </div>

        <div>
            <table>
                <thead>
                    <th>id</th>
                    <th>receiver</th>
                    <th>sender</th>
                    <th>msg</th>
                    <th>date</th>
                    <th>fav</th>
                    <th>del</th>
                </thead>
                <?php
                    $username = $_SESSION['username'];
                    $sql = "SELECT * FROM messages ORDER BY id";  
                    $result = mysqli_query($conn, $sql);
                    while($row = mysqli_fetch_row($result)){
                        echo '
                        <tr>
                            <td>'. $row[0] .'</td>
                            <td>'. $row[1] .'</td>
                            <td>'. $row[2] .'</td>
                            <td>'. $row[3] .'</td>
                            <td>'. $row[4] .'</td>
                            <td>'. $row[5] .'</td>
                            <td>'. $row[6] .'</th>
                        </tr>
                        ';
                    }
                    // Free result set
                    unset($result);
                ?>
            </table>
        </div>
    </section>
</body>
</html>