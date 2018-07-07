<?php


/**
 * VALIDATIONS FOR THE DUPLICATE POWER READING DATE 
 */
require_once '..\Connection.php';

 if ($_POST['action'] === "powerDateCheck") {

     $date_ph = $_POST['readingDate'];
    //echo $date_ph;
    $reading_date = date('Y-m-d', strtotime($date_ph));
    //echo "reading"; echo"<br>";

   // echo "in powerDateCheck function";
    powerDateCheck($reading_date);
   
 
}

function powerDateCheck($reading_date) {
//echo "powerDateCheck function1";
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $databaseName = "rolling_jd2";
    $link = mysqli_connect($hostname, $username, $password, $databaseName);

    if ($link === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }

    $sql_3 = "select power_id  from `power_report` where reading_date='".$reading_date."'";
    $res = mysqli_query($link, $sql_3);
   // $row_1 = mysqli_fetch_array($res);
    $num_1=mysqli_num_rows($res);
     echo $num_1;
}

