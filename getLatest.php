<?php

    getLatest();

    function getLatest(){
        include("connect.php");
        
        //En son gelen sayıyı almamız gerekiyor.
        $sql = "SELECT Roll, CreationDate FROM Roulette ORDER BY CreationDate DESC LIMIT 1";
        if($res = $con->query($sql)){
            if($res->num_rows > 0){
                while($row = $res->fetch_assoc()){
                    echo $row["Roll"];
                }
            }
        }
    }

?>