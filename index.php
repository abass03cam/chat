<?php
    session_start();

    include("DBConnection.php");
    include("links.php");

    if(isset($_GET["userId"]))
    {
        $_SESSION["userId"] = $_GET["userId"];
        header("location: chatbox.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ChatPro</title>
</head>
<body>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Please Select Your Account </h4>
    </div>
        <div class="modal-body">
            <ol>
            <?php
                $users = mysqli_query($connect, "SELECT * FROM users")
                or die("Failed to query database".mysql_error());
                while($user = mysqli_fetch_assoc($users))
                {
                    echo '<li>
                    <a href="index.php?userId='.$user["Id"].'">'.$user["User"].'</a>
                    </li> ';
                }
            ?> 
            </ol>
             <a href="register.php" style="float:right;">Register</a>
            </div>
        </div>
    </div>
</body>
</html>