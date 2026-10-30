 <?php
 // Database connection details
    $dBservername = 'localhost';
    $dBusername = 'root';
    $dBpassword = ''; 
    $dBname = 'appl_db';

    // Create connection
    $conn = new mysqli($dBservername,$dBusername,$dBpassword,$dBname);

    //Check connection
    if (!$conn) {
      die("Connection failed:" .mysql_connect_error());
    }
