<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$hostname = "localhost";
$username = "root";
$password = "";
$databaseName = "moira";

// connect to mysql database

$link = mysqli_connect($hostname, $username, $password, $databaseName);

if ($link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>