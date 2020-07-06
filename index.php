<!DOCTYPE html>
<html>
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="main.css">
    </head>
    <body onload="incrementCount(10)">
        

<?php

    require 'connect.php';
    require 'steamauth/steamauth.php';

    if(!isset($_SESSION['steamid'])) {
        loginbutton("rectangle"); //login button
    }else{
        include ('steamauth/userInfo.php'); //To access the $steamprofile array
        //Protected content
        //Burada kendi database'imize üyelik işlemini yapıcaz.
        //Eğer üyelik mevcutsa bu hesap için üyelik bilgilerini yükliycez.

        if($steamprofile['steamid'] != "" && isset($steamprofile['steamid'])){
            $steamid = $steamprofile['steamid'];

            $sql = "SELECT ID, Jeton FROM User WHERE Steamid = '".$steamid."'";

            if($res = $con->query($sql)){
                if($res->num_rows > 0){
                    //Bu hesap bizde zaten var.
                    while($row = $res->fetch_assoc()){
                        echo "<img src='".$steamprofile['avatar']."'/> <br>";
                        echo $steamprofile['personaname'];
                        echo "<div id='jeton'>Jeton: " . $row["Jeton"]."</div>";
                    }
                }else{
                    //Bu hesap bizde yok. Oluşturalım.
                    $sql = "INSERT INTO User (SteamID, isAdmin) values ('".$steamid."', 0)";
                    if($con->query($sql)){
                        //Bu hesap başarıyla bizde kayıt edildi. Alert olarak göster, sayfayı yenile.
                        echo $steamid . " adlı üyenin kaydı başarıyla oluşturuldu.";
                    }else{
                        //Bu hesap kaydı başarısız oldu. Alert olarak göster, sayfayı yenile.
                        echo $steamid . " adlı üyenin kaydı başarısız oldu.";
                    }
                }
                
                logoutbutton(); //Logout Button

                echo "
                <div id='coloredButtons'>
                    <div id='blackBet' onclick='deneme(\"Siyaha\")'>x2</div>
                    <div id='greenBet' onclick='deneme(\"Yeşile\")'>x14</div>
                    <div id='redBet' onclick='deneme(\"Kırmızıya\")'>x2</div>
                    <div id='betBut'>
                        <input type='number' id='betsize' name='betsize'>
                    </div>
                </div>
                <div style='float:clear'></div>
                <div id='rouletteInfo'>
                    <div id='rouletteWheel'>
                        <div id='line'> </div>
                        <div id='movable'>
                            <div id='blackNumber'> 11 </div>
                            <div id='redNumber'> 7 </div>
                            <div id='blackNumber'> 14 </div>
                            <div id='redNumber'> 3 </div>
                            <div id='blackNumber'> 8 </div>
                            <div id='redNumber'> 1 </div>
                            <div id='blackNumber'> 10 </div>
                            <div id='greenNumber'> 0 </div>
                            <div id='redNumber'> 6 </div>
                            <div id='blackNumber'> 13 </div>
                            <div id='redNumber'> 4 </div>
                            <div id='blackNumber'> 9 </div>
                            <div id='redNumber'> 5 </div>
                            <div id='blackNumber'> 12 </div>
                            <div id='redNumber'> 2 </div>
                            <div id='blackNumber'> 11 </div>
                            <div id='redNumber'> 7 </div>
                            <div id='blackNumber'> 14 </div>
                            <div id='redNumber'> 3 </div>
                            <div id='blackNumber'> 8 </div>
                            <div id='redNumber'> 1 </div>
                            <div id='blackNumber'> 10 </div>
                            <div id='greenNumber'> 0 </div>
                            <div id='redNumber'> 6 </div>
                            <div id='blackNumber'> 13 </div>
                            <div id='redNumber'> 4 </div>
                            <div id='blackNumber'> 9 </div>
                            <div id='redNumber'> 5 </div>
                            <div id='blackNumber'> 12 </div>
                            <div id='redNumber'> 2 </div>
                            <div id='blackNumber'> 11 </div>
                            <div id='redNumber'> 7 </div>
                            <div id='blackNumber'> 14 </div>
                            <div id='redNumber'> 3 </div>
                            <div id='blackNumber'> 8 </div>
                            <div id='redNumber'> 1 </div>
                            <div id='blackNumber'> 10 </div>
                            <div id='greenNumber'> 0 </div>
                            <div id='redNumber'> 6 </div>
                            <div id='blackNumber'> 13 </div>
                            <div id='redNumber'> 4 </div>
                            <div id='blackNumber'> 9 </div>
                            <div id='redNumber'> 5 </div>
                            <div id='blackNumber'> 12 </div>
                            <div id='redNumber'> 2 </div>
                            <div id='blackNumber'> 11 </div>
                            <div id='redNumber'> 7 </div>
                            <div id='blackNumber'> 14 </div>
                            <div id='redNumber'> 3 </div>
                            <div id='blackNumber'> 8 </div>
                            <div id='redNumber'> 1 </div>
                            <div id='blackNumber'> 10 </div>
                            <div id='greenNumber'> 0 </div>
                            <div id='redNumber'> 6 </div>
                            <div id='blackNumber'> 13 </div>
                            <div id='redNumber'> 4 </div>
                            <div id='blackNumber'> 9 </div>
                            <div id='redNumber'> 5 </div>
                            <div id='blackNumber'> 12 </div>
                            <div id='redNumber'> 2 </div>
                        </div>
                    </div>
                    <div id='rollInfo'></div>
                    <div class='container_inner' id='display_div_id'>
                    </div>
                    <div id='played'>
                    </div>
                </div>               
                ";

            }else{
                //Bu hesabın üyelik kontrolü sırasında hata oluştu. Alert olarak göster, sayfayı yenile.
                echo "Hata oluştu.";
            }
        }
    }     
?>

</body>
</html>

<script>
    function deneme(type){
        var jeton = document.getElementById("betsize").value
        $.post("getJeton.php",
        {
            name: "<?php echo $steamprofile['steamid']; ?>",
            jeton: jeton,
            type: type
        },
        function(data, status){
            if(data != -1){
                var roll_div = document.getElementById("jeton");
                roll_div.innerText = data;
                var playedContainer = document.getElementById("played");
                var lastPlayed = document.createElement("div");
                lastPlayed.id = "lastPlayed";
                lastPlayed.innerText = jeton + " Jeton " + type + " Oynadın.";
                playedContainer.appendChild(lastPlayed);
            }else{
                alert("Yetersiz bakiye");
            }
        });
    }
</script>

<script>
    var rouletteWheel = document.getElementById("movable");
    var number = document.getElementById("redNumber");

    var wheelLength = rouletteWheel.scrollWidth;
    var numberLength = number.scrollWidth + 5 ;

    var numberPositions = {"0":[], "1":[], "2":[], "3":[], "4":[], "5":[], "6":[], "7":[], "8":[], "9":[], "10":[], "11":[], "12":[], "13":[], "14":[]};
    var childrens = rouletteWheel.children;

    for (i = 0; i < childrens.length; i++) {
        numberPositions[childrens[i].innerText].push(i);
    }
</script>

<script>
    var isFirst = true;
    var display_str = "";
    var display_div = document.getElementById("display_div_id");
    var roll_div = document.getElementById("rollInfo");

    function incrementCount(current_count){
        setInterval(function(){

            if(isFirst){
                isFirst = false;
                $.get("getLatest.php", function(data, status){
                    console.log(status);
                    roll_div.innerText = "Latest Roll: " + data;
                    var startLeft = childrens[numberPositions[data][1]].offsetLeft - rouletteWheel.offsetWidth/2 + numberLength/2;
                    rouletteWheel.style.left = "-" + startLeft + "px";
                });
            }

            // clear count
            while (display_div.hasChildNodes()) {
                display_div.removeChild(display_div.lastChild);
            }

            var day = new Date(); 
            var min = day.getMinutes();
            var seconds = day.getSeconds();

            var counter_list = [1-(min % 2), 60-seconds];
            var str_counter_0 = counter_list[0];
            var str_counter_1 = counter_list[1];

            if(counter_list[0] == 1 && counter_list[1] == 58 && !isFirst){
                $.get("getLatest.php", function(data, status){
                    roll_div.innerText = "Latest Roll: " + data;
                    var startLeft = childrens[numberPositions[data][1]].offsetLeft - rouletteWheel.offsetWidth/2 + numberLength/2;
                    rouletteWheel.style.left = "-" + startLeft + "px";
                });
            }

            if(counter_list[0] == 1 && counter_list[1] == 38){
                var jeton = document.getElementById("betsize").value
                $.post("getCurrentJeton.php",
                {
                    name: "<?php echo $steamprofile['steamid']; ?>"
                },
                function(data, status){
                    if(data != -1){
                        var roll_div = document.getElementById("jeton");
                        roll_div.innerText = data;
                    }
                });
            }

            display_str = str_counter_0.toString() + ":" + str_counter_1.toString();
            for (var i = 0; i < display_str.length; i++) {
                var new_span = document.createElement('span');
                new_span.className = 'num_tiles';
                new_span.innerText = display_str[i];
                display_div.appendChild(new_span);
            }
        },1000);
  }
</script>