<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once '..\Connection.php';
if ($_POST['action'] === "deleteBreakdownSummary") {
    $primarykey = $_POST['primaryKeyForDelete'];
    echo deleteBreakdownSummary($primarykey);
}

function deleteBreakdownSummary($primarykey) {
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $databaseName = "moira";
    $link = mysqli_connect($hostname, $username, $password, $databaseName);

    if ($link === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }

    $sql_2 = "delete from jd2_rolling_breakdown where  breakdown_id='" . $primarykey . "'";
    $res = mysqli_query($link, $sql_2);
    return $res;
   
}
