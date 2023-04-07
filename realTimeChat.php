<?php
    include("DBConnection.php");
        
        $fromUser = $_POST["fromUser"];
        $toUser = $_POST["toUser"];
        $output="";

        $chats = mysqli_query($connect, "SELECT * FROM messages where (FromUser = '" .$fromUser."' 
        AND ToUser = '" .$toUser."') OR (FromUser = '" .$toUser."' AND ToUser = '" .$fromUser."') ")
        or die("Failed to query database".mysqli_error());
        while ($chat = mysqli_fetch_assoc($chats))
                                
        {
            if($chat["FromUser"] == $fromUser)
            $output.= "<div style='text-align:right;'>
                <p style='background-color:lightblue; word-wrap:break-word; 
                display:inline-block; padding: 5px; border-radius:10px; max-width:40%;'>
                    ".$chat["Messag"]."
                </p>
                </div>";
            else
            $output.= "<div style='text-align:right;'>
                    <p style='background-color:yellow; word-wrap:break-word; 
                    display:inline-block; padding: 5px; border-radius:10px; max-width:40%;'>
                     ".$chat["Messag"]."
                    </p>
            </div>";
        }
        echo $output;
       
?>