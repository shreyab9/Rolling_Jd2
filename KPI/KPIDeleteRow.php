<?php
if ($_POST['action'] === "DeleteKPIRow"){
    $KpiId = filter_input(INPUT_POST,'KpiId');
    echo kpiDeleteSummary($KpiId);
  
}

function kpiDeleteSummary($KpiId) {
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $databaseName = "moira";
    $link = mysqli_connect($hostname, $username, $password, $databaseName);

    if ($link === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }

    $sql_2 = "delete from jd2_rolling_kpi_24hrs where  kpi_id='" . $KpiId . "'";
    $res = mysqli_query($link, $sql_2);
    return $res;
   
}