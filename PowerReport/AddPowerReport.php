<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//ESTABLISHING CONNECTION TO THE DATABASE
require_once("..\Connection.php");
// INCLUDING DB FILE
require_once("..\DBfile.php");
// TO SEND MESAGE TO THE SLACK CHANNEL
require_once("..\postMessagesToSlack.php");



function lz($num) {
    return (strlen($num) < 2) ? "0{$num}" : $num;
}

//INPUT VALUES FROM THE FORM WHICH IS ENTERED BY THE USET 


$datetime   = filter_input(INPUT_POST,'datetime');
$mwhdu      = filter_input(INPUT_POST,'dumwh');
$mvahdu     = filter_input(INPUT_POST,'dumvah');
$mva        = filter_input(INPUT_POST,'mva');
$pf         = filter_input(INPUT_POST,'pf');
$unit3      = filter_input(INPUT_POST,'unit3');
$ccd        = filter_input(INPUT_POST,'ccd');  
$hpblower   = filter_input(INPUT_POST,'100hpblower');
$moirafnce  = filter_input(INPUT_POST,'moirafnce');
$moiralt    = filter_input(INPUT_POST,'moiralt');
$bundlingpress  =    filter_input(INPUT_POST,'bundlingpress');
/**$mwhmpebr    =       $_POST['mwhmpebreading'];
$mvahmpebr      =    $_POST['mvahmpebreading'];

$mwhmpebreading =    $_POST['mwhmpebreading'];
$mvahmpebreading =    $_POST['mvahmpebreading'];
$mvampebreading  =     $_POST['mvampebreading'];**/
$mvampebr       =   filter_input(INPUT_POST,'mvamu');
$day=1;
echo  "entered date :" ; echo  $datetime; 
$date_value = date('d', strtotime($datetime));
echo "<br>";
echo $date_value;
$reading_date = date('Y-m-d', strtotime($datetime));
$sql_pr = mysqli_query($link,
        "select `reading_datetime`,`mwh_daily_unit`,`mvah_daily_unit`,`mva`,`power_factor`,`mwh_monthly_unit`
            ,`mvah_monthly_unit`,`unit-3`,`ccd`,`100-hp_blower`,`moira_furnace`,`moira_lt`,`bundling_press`
         from jd2_rolling_power_report where `power_id` in(select max(power_id) from jd2_rolling_power_report)");

$row_pr= mysqli_fetch_array($sql_pr);
echo 'in select query';

$last_readingtime   =   $row_pr['reading_datetime'];
$last_mwhdu         =   $row_pr['mwh_daily_unit'];
$last_mvahdu        =   $row_pr['mvah_daily_unit'];
$last_mva           =   $row_pr['mva'];
$last_pf            =   $row_pr['power_factor'];
$last_mwhmu         =   $row_pr['mwh_monthly_unit'];
$last_mvahmu        =   $row_pr['mvah_monthly_unit'];
$last_unit3         =   $row_pr['unit-3'];
$last_ccd           =      $row_pr['ccd'];
$last_100hpblower   =   $row_pr['100-hp_blower'];
$last_moirafnce  =   $row_pr['moira_furnace'];
$last_moiralt       =   $row_pr['moira_lt'];
$last_bundlingpress =   $row_pr['bundling_press'];
//$last_mwhmpebr      =   $row_pr['mpeb mwh reading'];
//$last_mvahmpebr     =   $row_pr['mpeb mvah reading'];
//$last_mvampebr      =   $row_pr['mpeb mva reading'];



echo"<br>";
echo $last_readingtime;
echo "<br>";
echo $last_mwhdu;

$hour_diff= abs(strtotime($datetime) - strtotime($last_readingtime))/3600;
//$hour_diff1=$hour_diff;

$seconds_ph = ($hour_diff * 3600);
$hours_ph = floor($hour_diff);
$seconds_ph -= $hours_ph * 3600;
$minutes_ph = floor($seconds_ph / 60);
$seconds_ph -= $minutes_ph * 60;
$hour_diff1 = lz($hours_ph) . ":" . lz($minutes_ph);



echo "<br>"; echo $hour_diff;
if($date_value == 23)
{
    $mwhmu=filter_input(INPUT_POST,'mwhmu');
    $mvahmu=filter_input(INPUT_POST,'mvahmu');
}
else{
     $mwhmu=$last_mwhmu;
    $mvahmu=$last_mvahmu;
}
if($date_value==23){
    $day_count = 1 ;
    
         echo $day_count;
}
else{
    $sql_d= mysqli_query($link,"select `day_count` from  jd2_rolling_power_report where power_id in (select max(power_id) from jd2_rolling_power_report)");
    $row_d= mysqli_fetch_array($sql_d);

    $row_val=$row_d['day_count'];
  
    $day_count= $row_val +1 ;
 

}

//DAILY UNIT CALCULATION 
$dailyunit= round(($mwhdu - $last_mwhdu)*40000);
//echo " Dailyunit: <br> "; echo $dailyunit;
//MONTHLY UNIT CALCULATION
$monthlyunit= round(($mwhdu-$mwhmu)*40000);
//DAILY POWER FACTOR
$dpf=number_format((float)($mwhdu - $last_mwhdu)/($mvahdu-$last_mvahdu),5,'.','');
//MONTHLY DEMAND
$monthlydemand= number_format((float)($mva*40000),2,'.','');
//DAILY LOAD FACTOR 
$dlf=number_format((float)($dailyunit/($monthlydemand*$hour_diff)*100),2,'.','');
//MONTHLY POWER FACTOR
$mpf=number_format((float)($mwhdu-$mwhmu)/($mvahdu-$mvahmu),5,'.','');
//MONTHLY LOAD FACTOR
$mlf=number_format((float)($monthlyunit/($monthlydemand*24*$day_count)*100),2,'.','');
//Unit 3 units Calculation
$unit3_units=number_format((float)(($unit3-$last_unit3)*100*10000),2,'.','');
//100 hp blower units calculation
$hpblower_units=number_format((float)($hpblower-$last_100hpblower),2,'.','');

//ccd units calculations
$ccd_units=number_format((float)(($ccd-$last_ccd)*1000000),2,'.','');
//MOIRA FURNACE UNIT
$moirafnce_units=number_format((float)(($moirafnce-$last_moirafnce)*10000),2,'.','');
// MOIRA L UNITS
$moiralt_units=number_format((float)(($moiralt-$last_moiralt)*1000),2,'.','');
// BUNDLING PRESS UNITS
$bundlingpress_units=number_format((float)($bundlingpress-$last_bundlingpress),2,'.','');
// TOTAL UNITS
$total_units=$bundlingpress_units +$unit3_units +$moirafnce_units +$moiralt_units +$hpblower_units +$ccd_units;
echo "units"; echo "<br>";
echo $unit3_units; echo "<br>";
echo $ccd_units; echo "<br>";
echo $hpblower_units; echo "<br>";
echo $bundlingpress_units; echo "<br>";
echo $moirafnce_units; echo "<br>";
echo $moiralt_units; echo "<br>";

echo"total units:"; echo $total_units;
// ROLLING UNITS
$rolling_units= round($dailyunit-$total_units);

echo $reading_date;
//ELECTRICAL DOWN TIME
$sql_prbd=mysqli_query($link,"select  TIME_FORMAT((SUM(`bd_total_time`)),'%H:%i') from jd2_rolling_breakdown where bd_date ='".$reading_date."' and dept_id =3");
$row_prbd=mysqli_fetch_array($sql_prbd);

$elec_down_time = $row_prbd[0];
echo $elec_down_time;
//echo $test;

//INSERTING THE data in to the power_report table 
echo "in insert query";

echo $reading_date;
echo $day_count;

$sql_prpt= "INSERT INTO 
         `jd2_rolling_power_report` 
         (`reading_date`,
         `day_count`, 
         `reading_datetime`, 
         `hours_dif`,
         `mwh_daily_unit`,
         `mvah_daily_unit`,
         `mva`, 
         `power_factor`,
         `mwh_monthly_unit`,
         `mvah_monthly_unit`, 
         `unit-3`,
         `ccd`,
         `100-hp_blower`,
         `moira_furnace`,
         `moira_lt`, 
         `bundling_press`, 
         `mpeb_mva_reading`, 
         `daily_unit`,
         `monthly_unit`, 
         `daily_power_factor`, 
         `daily_load_factor`, 
         `monthly_power_factor`, 
         `monthly_load_factor`, 
         `monthly_demand`,
         `unit-3_units`,
         `100-hp_blower_units`,
         `ccd_units`,
         `moira_furnace_units`,
         `moira_lt_units`,
         `bundling_press_units`,
         `total_units`,
         `rolling_units`, 
         `elec_down_time`) 
        VALUES 
        ('".$reading_date."',"
        . "'".$day_count."',"
        . "'".$datetime."',"
        . "'".$hour_diff1."',"
        . "'".$mwhdu."',"
        . "'".$mvahdu."',"
        . "'".$mva."',"
        . "'".$pf."',"
        . "'".$mwhmu."',"
        . "'".$mvahmu."',"
        . "'".$unit3."',"
        . "'".$ccd."',"
        . "'".$hpblower."',"
        . "'".$moirafnce."',"
        . "'".$moiralt."',"
        . "'".$bundlingpress."',"
        . "'".$mvampebr."',"
        . "'".$dailyunit."',"
        . "'".$monthlyunit."',"
        . "'".$dpf."',"
        . "'".$dlf."',"
        . "'".$mpf."',"
        . "'".$mlf."',"
        . "'".$monthlydemand."',"
        . "'".$unit3_units."',"
        . "'".$hpblower_units."',"
        . "'".$ccd_units."',"
        . "'".$moirafnce_units."',"
        . "'".$moiralt_units."',"
        . "'".$bundlingpress_units."',"
        . "'".$total_units."',"
        . "'".$rolling_units."',"
        . "'".$elec_down_time."')";
 
$result_prpt = (mysqli_query($link, $sql_prpt) or die(mysqli_error($link)));



if ($result_prpt) {
    echo"record added";
    //echo $row_cum;
} else {
    echo 'not added';
}

mysqli_close($link);

//POSt Message to Slack CHANNEL ROLLING
Slack::getInstance()->postMessagesToSlack_powerreport("
Date-On = *$reading_date*
Daily Unit= `$dailyunit`
Monthly Unit= `$monthlyunit`
Rolling =`$rolling_units`
D.P.F= *$dpf*
D.L.F.= *$dlf*
   |M.P.F|=`$mpf`
   |M.L.F| =`$mlf`
   |M.D.| = `$monthlydemand`
Unit-3 =*$unit3_units*
100 HP-Blower= *$hpblower_units*
CCD=*$ccd_units*    
Moira= *$moirafnce_units*
LT=*$moiralt_units*
Bundling Press=*$bundlingpress_units*
Elec.Down.Time=*$elec_down_time*    
         ", "Rolling_Jd2");

// ON FORM SUBMITTED REDIRECTED TO THE HOME.PHP
header("Location:http://dataapp.moira.local/Rolling_Jd2/Home.php");

exit();
  