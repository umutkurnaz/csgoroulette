<?php

    function getResults(){
        require("connect.php");

        $result = "-1";

        $sql = "SELECT Roll, CreationDate FROM Roulette ORDER BY CreationDate DESC LIMIT 1";
        if($res = $con->query($sql)){
            if($res->num_rows > 0){
                while($row = $res->fetch_assoc()){
                    if($row["Roll"] == 0){
                        $result = "Yeşil";
                    }else if($row["Roll"] < 8 && $row["Roll"] != 0){
                        $result = "Kırmızı";
                    }else{
                        $result = "Siyah";
                    }
                }
            }
        }

        $sql = "SELECT * FROM BetHistory WHERE isActive = 1";

        if($res = $con->query($sql)){
            if($res->num_rows > 0){
                while($row = $res->fetch_assoc()){
                    $r1 = 0;
                    $addJeton = 0;

                    if($result == $row["BetType"]){
                        $r1 = 1;
                        if($result == "Yeşil"){
                            $addJeton = $row["Jeton"] * 14;
                        }else{
                            $addJeton = $row["Jeton"] * 2;
                        }
                    }

                    $sql1 = "UPDATE BetHistory Set Result = ".$r1." , isActive = 0 WHERE ID = ".$row["ID"];
                    $con->query($sql1);
                    $sql1 = "UPDATE User Set Jeton = Jeton + ".$addJeton." WHERE Steamid = '".$row["UserID"]."'";
                    $con->query($sql1);

                }
            }
        }
    }

?>