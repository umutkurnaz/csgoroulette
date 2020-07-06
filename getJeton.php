<?php

    include("connect.php");

    $currentJeton = 0;

    $sql = "SELECT Jeton FROM User WHERE Steamid = '".$_POST["name"]."'";
    if($res = $con->query($sql)){
        if($res->num_rows > 0){
            while($row = $res->fetch_assoc()){
                $currentJeton = $row["Jeton"];
            }
        }
    }

    $currentJeton = $currentJeton - $_POST["jeton"];

    if($currentJeton >= 0){
        $sql = "UPDATE User Set Jeton = ".$currentJeton." WHERE Steamid = '".$_POST["name"]."'";
        $con->query($sql);
        
        $type = "";
        if($_POST["type"] == "Siyaha"){
            $type = "Siyah";
        }else if($_POST["type"] == "Kırmızıya"){
            $type = "Kırmızı";
        }else{
            $type = "Yeşil";
        }
        $sql = "INSERT INTO BetHistory (UserID, BetType, Jeton) values ('".$_POST["name"]."', '".$type."', ".$_POST["jeton"].")";
        $con->query($sql);

        echo $currentJeton;
    }else{
        echo -1;
    }

?>