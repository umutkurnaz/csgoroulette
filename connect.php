<?php
    // Change this to your connection info.
    $host = 'localhost';
    $user = 'youtube';
    $pass = '1234qqqQ';
    $name = 'csgobet';
    
    // Try and connect using the info above.
    $con = mysqli_connect($host, $user, $pass, $name);
    if (mysqli_connect_errno()) {
        // If there is an error with the connection, stop the script and display the error.
        die ('Failed to connect to MySQL: ' . mysqli_connect_error());
    }
?>
