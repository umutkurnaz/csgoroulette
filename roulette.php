<?php

    rulet();

    //Bu scripti her 2 dakikada bir server otomatik başlatmak zorunda. Böylece herkese aynı sonucu gösterebiliriz.
    function rulet(){
        include("connect.php");
        include("results.php");

        $server_seed = generate_random_key();
        $public_seed = generate_random_key();
        
        $hash = hash('sha256', $server_seed . "-" . $public_seed);
        $roll = hexdec(substr($hash, 0, 8)) % 15;
    
        if ($roll == 0) $roll_colour = 'green';
    
        elseif ($roll >= 1 and $roll <= 7) $roll_colour = 'red';
    
        elseif ($roll >= 8 and $roll <= 14) $roll_colour = 'black';
    
        //echo("Roll: $roll\nColour: $roll_colour");

        $sql = "INSERT INTO Roulette (Roll, Server, Public) values (".$roll.", '".$server_seed."', '".$public_seed."')";

        if($con->query($sql)){

        }else{
            $roll = rulet();
        }

        getResults();

        echo "Latest Roll: ". $roll."\n";
        return $roll;
    }

    function generate_random_key($algo = 'sha256', $length = 1024)
    {
        $key = '';
        
        // Check if the openssl_random_pseudo_bytes() function exists
        if ( function_exists('openssl_random_pseudo_bytes') )
        { // It does exist so let's use it to generate a string of random bytes to hash
            
            $data = openssl_random_pseudo_bytes($length, $cstrong) . mt_rand() . microtime();
            $key = hash($algo, $data);
        } else
        { // It doesn't exist, but have no fear... We will attempt to read from /dev/urandom instead
        
            // Even if the file_get_contents() function returns FALSE, a hash will still be generated based on
            // a random number from mt_rand() and the microtime()
            $data = mt_rand() . microtime() . file_get_contents('/dev/urandom', $length) . mt_rand() . microtime();
            $key = hash($algo, $data);
        }
        
        return $key;
    }

?>