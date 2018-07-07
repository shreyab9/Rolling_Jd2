
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
         <script type="text/javascript" src="./ValidationPowerReport.js"></script>  
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
       
         <link rel="stylesheet" type="text/css" href="../viewTable.css" media="all">
             <meta name="viewport" content="width=device-width, initial-scale=1">
                
                 </head>


</html>

<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */





/** IT IS VIEW PERHEATFORM , WHEN THE USER SELECTED THE FROM DATE AND TO DATE IN THE HOME PAGE ,
 * HE WILL BE REDIRECTED TO THE VIEWPERHEAT PAGE . THE USER CAN ALSO DOWNLOAD THE EXCEL FILE
 * ON THE  BASIS OF SLECTED DATE
 * 
 */
session_start();
include('..\Connection.php');

extract($_GET);

//START AND DATE DAT  IN YYYY-MM-DD (BECAUSE CAN ACCEPT ONLY THIS FORMAT)
$date1 = date('Y-m-d', strtotime($date1));
$date2 = date('Y-m-d', strtotime($date2));


$_SESSION["date1"] = $date1;
$_SESSION["date2"] = $date2;


if ($link != NULL) {

    $sql_prptp = "SELECT  `reading_date`, `day_count`, `reading_datetime`, `hours_dif`, 
        `mwh_daily_unit`, `mvah_daily_unit`, `mva`, `power_factor`, `mwh_monthly_unit`, `mvah_monthly_unit`, `unit-3`, 
        `ccd`, `100-hp_blower`, `moira_furnace`, `moira_lt`, `bundling_press`, 
        `mpeb_mwh_reading`, `mpeb_mvah_reading`, `mpeb_mva_reading`, 
        `daily_unit`, `monthly_unit`, `daily_power_factor`,`daily_load_factor`, 
        `monthly_power_factor`, `monthly_load factor`, `monthly_demand`, `unit-3_units`, 
        `100-hp_blower_units`, `ccd_units`, `moira_furnace_units`, `moira_lt_units`, 
        `bundling press_units`, `total_units`, `rolling_units`,`power_id` FROM `jd2_rolling_power_report` 
            where reading_date >= '$date1' and reading_date <= '$date2' order by reading_date";


       $res_prptp = mysqli_query($link,  $sql_prptp)  or die(mysqli_error($link));
                   
   echo"<table  class='test'>";
      echo  "<caption>JD2 Rolling MIll Power Report
   <div id='button' align='right'>
   <a href='../home.php'>Home</a>
    <div id='button' align='right'>
  <a href='PowerReportExcel.php'>ExportToExcel</a>    
  <caption>";
       if($row_cnt = mysqli_num_rows($res_prptp) == 0){
           print "<div>There are no records found between dates $date1 and $date2 ."
           . " <br>   "
                   
        . "Please Select Correct Dates."
        . "<br>"
        ."<a href='../home.php'>Click here to go Back.</a>"
        . "</div>";
       }
        else{  

      echo "<tr><td>DATE</td>"
    . "<td>DAY</td>"
    . "<td>READING-TIME</td>"
    . "<td>HOURS-DIFF</td>"
    . "<td>MWH(DAILY UNIT)</td>"
    . "<td>MVAH(DAILY UNIT)</td>"
    . "<td>MVA(DAILY UNIT)</td>"
    . "<td>POWER FACTOR</td>"
    . "<td>MWH(Monthly Unit)</td>"
    . "<td>MVAH(Monthly Unit)</td>"
    . "<td>Unit-3</td>"
    . "<td>CCD</td>"
    . "<td>100-HP Blower</td>"
    . "<td>MOIRA Furnace</td>"
    . "<td>MOIRALT</td>"
    . "<td>BUNDLING PRESS</td>"
    . "<td>MPEB MWH READING</td>"
    . "<td>MPEB MVAH READING</td>"
    . "<td>MPEB MVA READING</td>"
    . "<td>DAILY UNIT</td>"
    . "<td>MONTHLY UNIT</td>"
    . "<td>DAILY POWER FACTOR</td>"
    . "<td>DAILY LOAD FACTOR</td>"
    . "<td>MONTHLY POWER FACTOR</td>"
    . "<td>MONTHLY LOAD FACTOR</td>"
    . "<td>MONTHLY DEMAND</td>"
    . "<td>UNIT-3(UNITS)</td>"
    . "<td>100-HP BLOWER(UNITS)</td>"
    . "<td>CCD(UNITS)</td>"
    . "<td>MOIRA(UNITS</td>"
    . "<td>LT(UNITS)</td>"
    . "<td>BUNDLING PRESS(UNITS)</td>"
    . "<td>TOTAL-UNITS</td>"
    . "<td>ROLLING</td>"
    . "<td>delete button</td>"; 


   /** echo $date1;
    echo $date2;
    echo "<br>";**/
   // $num_row = mysqli_num_rows($link,$res_perheat);
    //echo $num_row;
    while ($row_prptp = mysqli_fetch_array($res_prptp)) {
       // echo 'in while';
        
        echo "<tr>";
        for ($i = 0; $i < 34; $i++) {
            echo "<td>" . $row_prptp[$i] . "</td>";
        }
         echo "<td><button id='".$row_prptp[34]."' type=\"button\" onclick=\"deletePowerReportReading(this.id);\"> Delete </button></td>";
    }
        }
    echo "</table>";
}else {
    echo "Not Connect";
}
?>
<br><br>
<!-- DOWNLOAD THE EXCEL FILE TO GET THE DATA ON THE BASIS OF THE SELECTED DATE    -->

