<?php

    include("connect.php");

    $currentJeton = 0;

    $sql = "SELECT Jeton FROM User WHERE Steamid = '".$_POST["name"]."'";
    if($res = $con->query($sql)){
        if($res->num_rows > 0){
            while($row = $res->fetch_assoc()){
                echo $row["Jeton"];
            }
        }
    }

?>