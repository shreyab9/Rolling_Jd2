<?php
require_once '..\Connection.php';
if ($_POST['action'] === "deletePowerReading") {
    $PowerReportId = $_POST['PowerReportId'];
    echo deletePowerReading($PowerReportId);
}

function deletePowerReading($PowerReportId) {
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $databaseName = "moira";
    $link = mysqli_connect($hostname, $username, $password, $databaseName);

    if ($link === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }

    $sql_2 = "delete from jd2_rolling_power_report where  power_id='" . $PowerReportId . "'";
    $res = mysqli_query($link, $sql_2);
    return $res;
   
}
