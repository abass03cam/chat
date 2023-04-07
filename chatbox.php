<?php
    session_start();
    include("DBConnection.php");
    include("links.php");

    $users = mysqli_query($connect, "SELECT * FROM users WHERE Id = '".$_SESSION["userId"]."' ")
        or die("Failed to query database".mysql_error());
        $user = mysqli_fetch_assoc($users);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Chatbox</title>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <p>Hi <?php echo $user["User"]; ?> </p>
                <input type="text" id="fromUser" value=<?php echo $user["Id"]; ?> hidden />

                <p>Send message to:</p>
                <ul>
                    <?php
                        $msgs = mysqli_query($connect, "SELECT * FROM users")
                        or die("Failed to query database".mysqli_error());
                        while($msg = mysqli_fetch_assoc($msgs))
                        {
                            echo '<li> <a href="?toUser='.$msg["Id"].'">'.$msg["User"].'</a></li>';
                        }
                    ?>
                </ul>
                <a href="index.php"> ---return </a>
            </div>
            <div class="col-md-4">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h4>
                            <?php
                            if(isset($_GET["toUser"]))
                            {
                                $userName = mysqli_query($connect, "SELECT * FROM users WHERE Id = '".$_GET["toUser"]."' ")
                                or die("Failed to query database".mysqli_error());
                                $uName = mysqli_fetch_assoc($userName);
                                echo '<input type="text" value='.$_GET["toUser"].'id="toUser" hidden/>';
                                echo $uName["User"];
                            }else
                            {
                                $userName = mysqli_query($connect, "SELECT * FROM users")
                                or die("Failed to query database".mysqli_error());
                                $uName = mysqli_fetch_assoc($userName);
                                $_SESSION["toUser"] = $uName["Id"];
                                echo '<input type="text" value='.$_SESSION["toUser"].'id="toUser" hidden/>';
                                echo $uName["User"];
                            }
                            ?>
                        </h4>  
            </div>
            <div class="modal-body" id="msgBody" style="height:400px; overflow:scroll; overflow-x:hidden;">
                <?php
                    if(isset($_GET["toUser"])) 
                        $chats = mysqli_query($connect, "SELECT * FROM messages where (FromUser = '".$_SESSION["userId"]."' AND
                        ToUser = '".$_GET["toUser"]."') OR (FromUser = '".$_GET["toUser"]."' AND ToUser = '".$_SESSION["userId"]."')")
                        or die("Failed to query database".mysqli_error());
                        // $chat = mysqli_fetch_assoc($chats);
                     else
                        $chats = mysqli_query($connect, "SELECT * FROM messages where (FromUser = '".$_SESSION["userId"]."' AND
                        ToUser = '".$_SESSION["toUser"]."') OR (FromUser = '".$_SESSION["toUser"]."' AND ToUser = '".$_SESSION["userId"]."')")
                        or die("Failed to query database".mysqli_error());
                        
                        while ($chat = mysqli_fetch_assoc($chats))
                                
                                // while($chat = mysqli_fetch_assoc($chats))
                                {
                                    if($chat["FromUser"] == $_SESSION["userId"])
                                    echo "<div style='text-align:right;'>
                                        <p style='background-color:lightblue; word-wrap:break-word; 
                                        display:inline-block; padding: 5px; border-radius:10px; max-width:70%;'>
                                            ".$chat["Messag"]."
                                        </p>
                                        </div>";
                                    else
                                    echo "<div style='text-align:right;'>
                                            <p style='background-color:yellow; word-wrap:break-word; 
                                            display:inline-block; padding: 5px; border-radius:10px; max-width:70%;'>
                                             ".$chat["Messag"]."
                                            </p>
                                    </div>";
                                }
                            ?>  
            </div>
            <div class="modal-footer">
                <textarea id="message" class="form-control" style="height:70px;"></textarea>
                <button id="send" class="btn btn-primary" style="height:70px;">Senden</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        </div>
    </div>
</div>

</body>
    <script>
        $(document).ready(function() {
    $("#send").on("click", function(){
        $.ajax({
            url:"insertMessage.php",
            method:"POST",
            data:{
                fromUser: $("#fromUser").val(),
                toUser: $("#toUser").val(),
                message: $("#message").val(),
            },
            dataType:"Text",
            success: function(data)
            {
                $("#message").val("");
            }
        });
    });
    setInterval(function(){
        $.ajax({
            url:"realTimeChat.php",
            method: "POST",
            data:{
                fromUser:$("#fromUser").val(),
                toUser:$("#toUser").val(),
            },
        dataType:"text",
        success:function(data)
            {
                $("#msgBody").html(data);
            }
    });
    }, 700);
});
    </script>                    
</html>