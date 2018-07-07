<?php
// THIS PAGE IS TO GET THE DETAIS OF THE PERHEATPRODUCTION THE EXCEL
/** FETCH THE DATA FROM THE PER HEAT PRODUCTION TABLE AND FORMATTED IN THE EXCEL FORMAT
/**DATE        CHANGESINTHESECTION                  USERNAME 
 * 2017-09-12   CODE IS COMPLETED IN THE PROD       VAISHALI JAIN 
 * 
 * 
 * 
 * 
 * 
 */
include ('../Connection.php');

session_start();
$date1 = $_SESSION["date1"];
$date2 = $_SESSION["date2"];

 $res_ex = mysqli_query($link,"select `per_heat_date`,`roughing`,
             `mill1_size1`,`mill1_size2`,`mill2_size1`,`mill2_size2`,`heat_number`,`heat_start_time`,`heat_end_time`,
             `total_heat_time`,`total_bd_time`,`3stand_3mtr_billetsbypass`,`3stand_6mtr_billetsbypass`,
             `ccm_3mtr_billetsbypass`,`ccm_6mtr_billetsbypass`,`billets_bypass_only_ccm`,`total_missroll`,`cum_missroll`,
            `8mm_prod`,`10mm_prod`,`12mm_prod`,`16mm_prod`,`20mm_prod`,`25mm_prod`,
            `28mm_prod`,`32mm_prod`,`rollingprod`,`cum_rollingprod`,`ccmprod`,`cum_ccmprod`,`hotrolling`,`cum_hotrolling`,
            `perc_billets_bypass_mill`,`perc_billets_bypass_ccm`,`perc_billets_bypass_3stand`,`perc_billets_bypass_furnace`,`perc_billets_bypass_mpeb`,
            `perc_billets_bypass_contractor`  from jd2_rolling_per_heat_prod 
            where per_heat_date >= '$date1' and per_heat_date <= '$date2' order by  per_heat_date ,`heat_number` asc");
 
 if(!$res_ex){
     echo mysqli_error($link);
 }
 
$columnHeader = '';
$columnHeader = "Date" . "\t" .
        "Roughing" . "\t" .
        "m1s1" . "\t" .
        "m1s2" . "\t" .
        "m2s1" . "\t" .
         "m2s2" . "\t" .
        "Heat No" . "\t" .
        " Heat Start time" . "\t" .
        "Heat End time" . "\t" .
        "Total heat time" . "\t" .
        "Total BD time" . "\t" .
        "3st3mtrbbp" . "\t" .
        "3st6mtrbbp" . "\t" .
        "ccm3mtrbbp" . "\t" .
        "ccm6mtrbbp" . "\t" .
        "bbpurelyccm" . "\t" .
        "missroll" . "\t" .
        "cum-missroll" . "\t" .
        "8mm" . "\t".
        "10mm" . "\t".
        "12mm" . "\t".
        "16mm" . "\t".
        "20mm" . "\t".
        "25mm" . "\t".
        "28mm" . "\t".
        "32mm" . "\t".
        "rollingprod" . "\t".
        "cum-rollingprod" . "\t".
        "ccmprod" . "\t".
        "cum-ccmprod" . "\t".
        "hotrolling" . "\t".
        "cum-cumhotrolling" . "\t".
        "perbbpmill" . "\t".
        "perbbpccm" . "\t".
        "perbbp3st" . "\t".
        "perbbpfnce" . "\t".
        "perbbmpeb" . "\t".
        "perbbcontr" . "\t"
          
        ;

$setData = '';

while ($rec_ex = mysqli_fetch_row($res_ex)) {
    $rowData = '';
    foreach ($rec_ex as $value) {
        $value = '"' . $value . '"' . "\t";
        $rowData .= $value;
    }
    $setData .= trim($rowData) . "\n";
}


header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=PerheatProduction.xls");
header("Pragma: no-cache");
header("Expires: 0");

echo ucwords($columnHeader) . "\n" . $setData . "\n";
?> 