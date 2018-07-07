<?php
/**
 * VALIDATIONS FOR THE DUPLICATE HEAT NUMBER
 */
require_once '..\Connection.php';

if ($_POST['action'] === "checkHeat") {
    $heatnumber = $_POST['heatnumber'];
    $date_ph = strtr($_REQUEST['perDate'], '/', '-');
    $perheatdate = date('Y-m-d', strtotime($date_ph));
    checkHeat($heatnumber, $perheatdate);
   
 
}

function checkHeat($heatnumber, $perheatdate) {

    $hostname = "localhost";
    $username = "root";
    $password = "";
    $databaseName = "moira";
    $link = mysqli_connect($hostname, $username, $password, $databaseName);

    if ($link === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }
    $sql_3 = "select `heat_number` from `jd2_rolling_per_heat_prod` where `heat_number`='" . $heatnumber . "' and per_heat_date ='" . $perheatdate . "'";
    $result = mysqli_query($link, $sql_3);
    $rowcount = mysqli_num_rows($result);
    echo $rowcount;
}




