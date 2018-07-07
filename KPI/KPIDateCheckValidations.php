<?php


/**
 * VALIDATIONS FOR THE DUPLICATE POWER READING DATE 
 */
//require_once '..\Connection.php';
 //echo 'hi in function1';
if ($_POST['action'] === "kpiDateCheck") {
    $kpidate = date('Y-d-m', strtotime(filter_input(INPUT_POST,'kpiDate')));
  
   $kpiDateCheck = kpiDateCheck($kpidate);
   
 
}

function kpiDateCheck($kpidate) {
//echo "kpiDateCheck function1";
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $databaseName = "moira";
    $link = mysqli_connect($hostname, $username, $password, $databaseName);

    if ($link === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }
   
    $sql_2 = "select count(*) from `jd2_rolling_kpi_24hrs` where kpi_date='".$kpidate."'";
    $res_kpi = mysqli_query($link, $sql_2);
    $row_1 = mysqli_fetch_array($res_kpi);
    //$num_kpi=mysqli_num_rows($res_kpi);
     echo $row_1[0];
     // return $num_kpi;
     
    }



