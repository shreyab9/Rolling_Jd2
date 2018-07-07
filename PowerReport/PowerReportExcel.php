<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */




include ('../Connection.php');

session_start();
$date1 = $_SESSION["date1"];
$date2 = $_SESSION["date2"];

 $res_ex1 = mysqli_query($link,"SELECT  `reading_date`, `day_count`, `reading_datetime`, `hours_dif`, 
        `mwh_daily_unit`, `mvah_daily_unit`, `mva`, `power_factor`, `mwh_monthly_unit`, `mvah_monthly_unit`, `unit-3`, 
        `ccd`, `100-hp_blower`, `moira_furnace`, `moira_lt`, `bundling_press`, 
        `mpeb_mwh_reading`, `mpeb_mvah_reading`, `mpeb_mva_reading`, 
        `daily_unit`, `monthly_unit`, `daily_power_factor`,`daily_load_factor`, 
        `monthly_power_factor`, `monthly_load factor`, `monthly_demand`, `unit-3_units`, 
        `100-hp_blower_units`, `ccd_units`, `moira_furnace_units`, `moira_lt_units`, 
        `bundling press_units`, `total_units`, `rolling_units`,`elec_down_time` FROM `jd2_rolling_power_report` 
            where reading_date >= '$date1' and reading_date <= '$date2' order by reading_date");
 
 
 
$columnHeader = '';
$columnHeader = "Date" . "\t" .
        "Day" . "\t" .
        "reading-time" . "\t" .
        "hours-dif" . "\t" .
        "mwhdailyunit" . "\t" .
        "mvahdailyunit" . "\t" .
        "mva" . "\t" .
        "powerfactor" . "\t" .
        "mwhmonthlyunit" . "\t" .
        "mvahmonthlyunit" . "\t" .
        "unit-3" . "\t" .
        "ccd" . "\t" .
        "100-hpblower" . "\t" .
        "moira-furnace" . "\t" .
        "moira-lt" . "\t" .
        "bundling press ". "\t" .
        "mpeb mwh reading" . "\t" .
        "mpeb mvah reading" . "\t" .
        "mpeb mva reading" . "\t" .
        "daily unit" . "\t" .
        "monthly unit" . "\t" .
        "daily power factor" . "\t" .
        "daily load factor" . "\t" .
        "monthly power factor" . "\t" .
        "monthly load factor" . "\t" .
        "monthly demand" . "\t" .
        "unit-3(units)" . "\t" .
        "100-hp blower(units)" . "\t" .
        "ccd(units)" . "\t" .
        "moira(units" . "\t" .
        "lt(units)" . "\t" .
        "bundling-press(units)" . "\t".
        "total-units" . "\t".
        "rolling" . "\t".
        "elec-down-time" . "\t"
        
       
        ;

$setData = '';

while ($rec_ex1 = mysqli_fetch_row($res_ex1)) {
    $rowData = '';
    foreach ($rec_ex1 as $value) {
        $value = '"' . $value . '"' . "\t";
        $rowData .= $value;
    }
    $setData .= trim($rowData) . "\n";
}


header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=MillPowerReport.xls");
header("Pragma: no-cache");
header("Expires: 0");

echo ucwords($columnHeader) . "\n" . $setData . "\n";
?> 
