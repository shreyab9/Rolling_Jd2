<?php

// ADD PER HEAT FORM , ALL THE PRODUCTION RELATED VALUES MUST BE CALCULATED IN METRIC TON.
// ESTABLISHING CONNECTION TO THE DATBASE
//MODIFIED DATE -19/09/2017 , CORRECTED THE CUM-HOTROLLING PERCENTGE AND ROUGHING MR PRODUCTION
require_once("..\Connection.php");
//
require_once("..\DBfile.php");
// TO SEND MESAGE TO THE SLACK CHANNEL
require_once("..\postMessagesToSlack.php");
//INITIALLIZING VARIABLE FOR CALCULATIONS
$cum_totalmissroll=0;
$bbprodmill = $bbprodmpeb = $rfmrprod = $bbprodccm = 0;
$m1s1prod = $m1s2prod = $m2s1prod = $m2s2prod = 0;
$cum_totalrollprod=0;
$final8 =$final12 =$final10=$final16= $final20 =$final25=$final28 =$final32='';
$rpm1s1 = $rpm1s2 = $rpm2s1 = $rpm2s2 = 0;
$bwt3m3s = $bwt6m3s = '';
$bwt3mccm=$bwt6mccm=$bbp3mccm=$bbp6mccm=0;
$ccmprod = $totalrollingprod = $totalhotrolng = 0;
$prod8mm = $prod10mm = $prod12mm = $prod16mm = $prod20mm = $prod25mm = $prod28mm = $prod32mm = 0;


function lz($num) {
    return (strlen($num) < 2) ? "0{$num}" : $num;
}

//CONVERTING THE DATE IN TO YYYY-MM-DDD
$date_ph = strtr($_REQUEST['perheatdate'], '/', '-');
//echo $date_ph;
$per_date = date('Y-m-d', strtotime($date_ph));
//$per_heat_date = date('d-m-Y', strtotime($date1));

//echo $per_date;

// Sizes from the DB file 
$m1s1 = RollingBD::getInstance()->get_size_id(filter_input(INPUT_POST,'m1s1'));
$m1s2 = RollingBD::getInstance()->get_size_id(filter_input(INPUT_POST,'m1s2'));
$m2s1 = RollingBD::getInstance()->get_size_id(filter_input(INPUT_POST,'m2s1'));
$m2s2 = RollingBD::getInstance()->get_size_id(filter_input(INPUT_POST,'m2s2'));

$mill1_size1= filter_input(INPUT_POST,'m1s1');
$mill1_size2= filter_input(INPUT_POST,'m1s2');
$mill2_size1= filter_input(INPUT_POST,'m2s1');
$mill2_size2= filter_input(INPUT_POST,'m2s2');

//VALUES ENTERED IN THE FORM 
$heatnumber = filter_input(INPUT_POST,'heatnumber');
$roughing = filter_input(INPUT_POST,'roughing');
$bwt3mccm = filter_input(INPUT_POST,'3mtrbwtccm');
$bbp3mccm = filter_input(INPUT_POST,'3mtrbbpccm');
$bwt6mccm = filter_input(INPUT_POST,'6mtrbwtccm');
$bbp6mccm = filter_input(INPUT_POST,'6mtrbypassccm');
$bbp3st3m = filter_input(INPUT_POST,'bbp3mtr3s');
$bbp3st6m = filter_input(INPUT_POST,'bbp6mtr3s');
$bwt3m3s = filter_input(INPUT_POST,'bwt3m3s');
$bwt6m3s = filter_input(INPUT_POST,'bwt6m3s');
$shift=filter_input(INPUT_POST,'shift');

$heatstarttime = filter_input(INPUT_POST,'heatstarttime');
$heatendtime = filter_input(INPUT_POST,'heatendtime');



$bwt3mtm1s1 = filter_input(INPUT_POST,'3mbwtm1s1');
$rpm1s1 = filter_input(INPUT_POST,'rpm1s1');
$bwt3mtm1s2 = filter_input(INPUT_POST,'3mbwtm1s2');
$rpm1s2 = filter_input(INPUT_POST,'rpm1s2');
$bwt3mtm2s1 = filter_input(INPUT_POST,'3mbwtm2s1');
$rpm2s1 = filter_input(INPUT_POST,'rpm2s1');
$bwt3mtm2s2 = filter_input(INPUT_POST,'3mbwtm2s2');
$rpm2s2 = filter_input(INPUT_POST,'rpm2s2');



$heat_start_time  = date('Y-m-d H:i:s',strtotime(str_replace('-','/', $heatstarttime)));
$heat_end_time  = date('Y-m-d H:i:s',strtotime(str_replace('-','/', $heatendtime)));
//CALCULATED TIME IN HH:MM
$total_heat_time = abs(strtotime($heat_end_time) - strtotime($heat_start_time)) / 3600;
$seconds_ph = ($total_heat_time * 3600);
$hours_ph = floor($total_heat_time);
$seconds_ph -= $hours_ph * 3600;
$minutes_ph = floor($seconds_ph / 60);
$seconds_ph -= $minutes_ph * 60;
$totalheattime = lz($hours_ph) . ":" . lz($minutes_ph);


$sTime_only=date('H:i', strtotime($heat_start_time));
$eTime_only=date('H:i', strtotime($heat_end_time));


//BILLETS BYPASS PRODUCTION PURELY DUE TO CCM
$bbppurelyccm=0;
 $bbppurelyccm = number_format((float)(($bwt3mccm * $bbp3mccm) + ($bwt6mccm * $bbp6mccm)) / 1000, 3,'.','');


//BILLETS BYPASS PRODUCTION DUE TO 3RD STAND 
$bbpprod3st = round((($bbp3st3m * $bwt3m3s) + (($bbp3st6m * $bwt6m3s) * 2)) / 1000, 3);


//Billetsbypass furnace
$bbprodfnce=RollingBD::getInstance()->get_total_billets_bypass_production(filter_input(INPUT_POST,'perheatdate'),filter_input(INPUT_POST,'heatnumber'),1);

//Billets bypass CCM
$bbprodccm=RollingBD::getInstance()->get_total_billets_bypass_production(filter_input(INPUT_POST,'perheatdate'),filter_input(INPUT_POST,'heatnumber'),2)+$bbppurelyccm;
//Billets bypass MILL , Electrical and mechanical
$bbprodmill=RollingBD::getInstance()->get_total_billets_bypass_production(filter_input(INPUT_POST,'perheatdate'),filter_input(INPUT_POST,'heatnumber'),3)+RollingBD::getInstance()->get_total_billets_bypass_production(filter_input(INPUT_POST,'perheatdate'),filter_input(INPUT_POST,'heatnumber'),4)+RollingBD::getInstance()->get_total_billets_bypass_production(filter_input(INPUT_POST,'perheatdate'),filter_input(INPUT_POST,'heatnumber'),5);
//
$bbprodcont=RollingBD::getInstance()->get_total_billets_bypass_production(filter_input(INPUT_POST,'perheatdate'),filter_input(INPUT_POST,'heatnumber'),6);

$bbprodmpeb=RollingBD::getInstance()->get_total_billets_bypass_production(filter_input(INPUT_POST,'perheatdate'),filter_input(INPUT_POST,'heatnumber'),7);


//CALCULATION FOR THE ROUGING MISSSROLL PRODUCTION
$sql_rf = "select COALESCE(sum(missroll_prod),0) from jd2_rolling_breakdown where bd_date='" . $per_date . "' and heat_number= '" . $heatnumber . "'  and location_id in (1,2,3,4,5,6,11,14)";
$result_rf = mysqli_query($link, $sql_rf);
$row_rf = mysqli_fetch_row($result_rf);
$rfmrprod = $row_rf[0];



echo "rfmrporod:";  echo $rfmrprod; echo "<br>";

if(!$result_rf){
    echo mysqli_error($link);
}
//echo 'hi1';
// CALCULATIONS FOR THE CUMMMULATIVE ROUGHING PRODUCTION 
if ($heatnumber == 1) {
    $cum_rfmrprod = $rfmrprod;
    echo $cum_rfmrprod;
} else {
    $cummHeatNumber = ($heatnumber - 1);
    echo "cumheatno"; echo  "<br>"; echo $cummHeatNumber;
    $sql_mrprod = "select cum_rf_missroll_prod from jd2_rolling_per_heat_prod where per_heat_date='" . $per_date . "' and heat_number= '" . $cummHeatNumber . "'";
    $result_mrprod = mysqli_query($link, $sql_mrprod);
    
    if (!$result_mrprod) {
        echo 'MySQL Error: ' . mysqli_error($link);
        exit;
    }
    $row_mrprod = mysqli_fetch_row($result_mrprod);
    
    echo "missrollprod";echo $row_mrprod[0]; 
    $cum_rfmrprod = $row_mrprod[0] + $rfmrprod;

}



//CALCULATIION FOR THE MISSROLL PRODUCTION  due to mill-1 mill-1 dc, mill-2 mill-2 dc , cooling bed mill-1 , cooling bed mill-2, MILL-1 THERMEX AND MILL-2 THERMEX

$sql_1 = "select sum(missroll_prod) from jd2_rolling_breakdown where bd_date='" . $per_date . "' and heat_number= '" . $heatnumber . "' and location_id in(7,8,9,10,12,13,16,17)";
$res_1 = mysqli_query($link, $sql_1);
$row_1 = mysqli_fetch_row($res_1);
$millmrprod = $row_1[0];



$totalbdtime =RollingBD::getInstance()->get_bd_down_time(filter_input(INPUT_POST,'perheatdate'), filter_input(INPUT_POST,'heatnumber'));


$sql_mr = "select sum(total_missroll) from jd2_rolling_breakdown where bd_date='" . $per_date . "' and heat_number= '" . $heatnumber . "'  ";
$result_mr = mysqli_query($link, $sql_mr);
$row_mr = mysqli_fetch_row($result_mr);
$totalmissroll = $row_mr[0];


//SIZE WISE AND MILL WISE PRODUCTION CALCULATION

$m1s1prod = round(($bwt3mtm1s1 * $rpm1s1) / 1000, 3);
$m1s2prod = round(($bwt3mtm1s2 * $rpm1s2) / 1000, 3);
$m2s1prod = round(($bwt3mtm2s1 * $rpm2s1) / 1000, 3);
$m2s2prod = round(($bwt3mtm2s2 * $rpm2s2) / 1000, 3);


$totalmrprod=$millmrprod+$rfmrprod;

$totalrolledpcs=$rpm1s1+$rpm1s2+$rpm2s1+$rpm2s2;
//CALCUALATION TO CALCUALTION THE PRODUCTION OF EVERY SIZE LIKE 8MM,10MM,12MM,16MM,20MM,25MM,28MM,32MM

//8mm

if ($m1s1 == 8) {
    echo 'size';
    $prod8mm = number_format((float)$m1s1prod,3,'.','');

    $final8 = $m1s1 . ' = ' . $prod8mm;

    
}
if ($m1s2 == 8) {
    $prod8mm = number_format((float)($prod8mm + $m1s2prod),3,'.','');
    $final8 = $m1s2 . ' = ' . $prod8mm;
}
if ($m2s1 == 8) {
    $prod8mm = number_format((float)($prod8mm + $m2s1prod),3,'.','');
    $final8 = $m2s1 . ' = ' . $prod8mm;
}
if ($m2s2 == 8) {
    $prod8mm = number_format((float)($prod8mm + $m2s2prod),3,'.','');
    $final8 = $m2s2 . ' = ' . $prod8mm;
}
//10 mm
//$m1s1prod=0;
//$m1s2prod=0;
//$m2s1prod=0;
//$m2s2prod=0;


if ($m1s1 == 10) {
    $prod10mm = number_format((float) $m1s1prod,3,'.','');
    $final10 = $m1s1 . ' = ' . $prod10mm;
   /** echo "10mm";
    echo $prod10mm;
    echo "<br>";**/
}

if ($m1s2 == 10) {
    $prod10mm = number_format((float)($prod10mm + $m1s2prod),3,'.','');
    $final10 = $m1s2 . ' = ' . $prod10mm;
}
if ($m2s1 == 10) {
    $prod10mm = number_format((float)($prod10mm + $m2s1prod),3,'.','');
    $final10 = $m2s1 . ' = ' . $prod10mm;
}
if ($m2s2 == 10) {
    $prod10mm = number_format((float)($prod10mm + $m2s2prod),3,'.','');
    $final10 = $m2s2 . ' = ' . $prod10mm;
}
//12mm

if ($m1s1 == 12) {
    echo 'in 12 mm';
    $prod12mm = number_format((float)$m1s1prod,3,'.','');
    $final12 = $m1s1 . ' = ' . $prod12mm;
}
if ($m1s2 == 12) {
    $prod12mm = number_format((float)($prod12mm + $m1s2prod),3,'.','');
    $final12 = $m1s2 . ' = ' . $prod12mm;
}

if ($m2s1 == 12) {
    $prod12mm = number_format((float)($prod12mm + $m2s1prod),3,'.','');
    $final12 = $m2s1 . ' = ' . $prod12mm;
}
if ($m2s2 == 12) {
    $prod12mm = number_format((float)($m2s2prod + $prod12mm),3,'.','');
    $final12 = $m2s2 . ' = ' . $prod12mm;
}
//16mm


if ($m1s1 == 16) {
    $prod16mm = number_format((float)$m1s1prod,3,'.','');
    $final16 = $m1s1 . ' = ' . $prod16mm;
}

if ($m1s2 == 16) {
    $prod16mm = number_format((float)($prod16mm + $m1s2prod),3,'.','');
    $final16 = $m1s2 . ' = ' . $prod16mm;
}

if ($m2s1 == 16) {
    $prod16mm = number_format((float)($prod16mm+$m2s1prod),3,'.','');
    $final16 = $m2s1 . ' = ' . $prod16mm;
}

if ($m2s2 == 16) {
    $prod16mm = number_format((float)($prod16mm +$m2s2prod),3,'.','');
    $final16 = $m2s2 . ' = ' . $prod16mm;
}
//20mm  
if ($m1s1 == 20) {
    $prod20mm = number_format((float)$m1s1prod ,3,'.',' ');
    $final20 = $m1s1 . ' = ' . $prod20mm;
}

if ($m1s2 == 20) {
    $prod20mm = number_format((float)($prod8mm +$m1s2prod),3,'.','');
    $final20 = $m1s2 . ' = ' . $prod20mm;
}

if ($m2s1 == 20) {
    $prod20mm = number_format((float)($prod20mm + $m2s1prod),3,'.','');
    $final20 = $m2s1 . ' = ' . $prod20mm;
}

if ($m2s2 == 20) {
    $prod20mm = number_format((float) ($prod20mm +$m2s2prod),3,'.','');
    $final20 = $m2s2 . ' = ' . $prod20mm;
}

//25mm

if ($m1s1 == 25) {
    echo $m1s1;

    $prod25mm = number_format((float)$m1s1prod,3,'.','');
    $final25 = $m1s1 . ' = ' . $prod25mm;
}

if ($m1s2 == 25) {
    $prod25mm = number_format((float)($prod25mm + $m1s2prod),3,'.','');
    $final25 = $m1s2 . ' = ' . $prod25mm;
}

if ($m2s1 == 25) {
    $prod25mm = number_format((float)($prod25mm +$m2s1prod),3,'.','');
    $final25 = $m2s1 . ' = ' . $prod25mm;
}

if ($m2s2 == 25) {
    $prod25mm = number_format((float)($prod25mm +$m2s2prod),3,'.','');
    $final25 = $m2s2 . ' = ' . $prod25mm;
}
//28mm

if ($m1s1 == 28) {
    $prod28mm = number_format((float)$m1s1prod,3,'.','');
    $final28 = $m1s1 . ' = ' . $prod28mm;
}

if ($m1s2 == 28) {
    $prod28mm = number_format((float)($prod28mm + $m1s2prod),3,'.','');
    $final28 = $m1s2 . ' = ' . $prod28mm;
}

if ($m2s1 == 28) {
    $prod28mm =number_format((float)($prod28mm + $m2s1prod),3,'.','');
    $final28 = $m2s1 . ' = ' . $prod28mm;
}

if ($m2s2 == 28) {
    $prod28mm = number_format((float)($prod28mm + $m2s2prod),3,'.','');
    $final28 = $m2s2 . ' = ' . $prod28mm;
}
//32mm


if ($m1s1 == 32) {
    $prod32mm = number_format((float)$m1s1prod,3,'.','');
    $final32 = $m1s1 . ' = ' . $prod32mm;
}

if ($m1s2 == 32) {
    $prod32mm = number_format((float) ($prod32mm +$m1s2prod),3,'.','');
    $final32 = $m1s2 . ' = ' . $prod32mm;
}

if ($m2s1 == 32) {
    $prod32mm = number_format((float)($prod32mm + $m2s1prod),3,'.','');
    $final32 = $m2s1 . ' = ' . $prod32mm;
}

if ($m2s2 == 32) {
    $prod32mm = number_format((float)($prod32mm +$m2s2prod),3,'.','');
    $final32 = $m2s2 . ' = ' . $prod32mm;
}


$varA='';


if ($final8 != '') {
    $varA =$varA . '' . $final8 . ' ' . "\n";
}
if ($final10 != '') {
    $varA = $varA . '' . $final10 . '' . "\n";
}

if ($final12 != '') {
    $varA = $varA . '' . $final12 . '' . "\n";
}
if ($final16 != '') {
    $varA = $varA . '' . $final16 . '' . "\n";
}
if ($final20 != '') {
    $varA = $varA . '' . $final20 . '' . "\n";
}
if ($final25 != '') {
    $varA = $varA . '' . $final25 . '' . "\n";
}
if ($final28 != '') {
    $varA = $varA . '' . $final28 . '' . "\n";
}
if ($final32 != '') {
    $varA = $varA . '' . $final32. '' . "\n";
}
// Calculation Heat gap

if ($heatnumber==1){
    $heatgap='00:00';
    
}
else{
    $old_heat_number=$heatnumber-1 ;
    echo "Heatnumber"; echo $heatnumber; echo $old_heat_number;
    $b1=mysqli_fetch_row(mysqli_query($link,"select `heat_end_time` from jd2_rolling_per_heat_prod where `per_heat_date`='".$per_date."' and `heat_number`='".$old_heat_number."'"));

    if(!$b1){
       echo  mysqli_error($link);
    }
    
    echo $b1[0];
$old_heat_endtime=$b1[0];

   //$new_heat_start_time= date('Y-m-d H:i:s',strtotime(str_replace('-','/', $old_heat_endtime)));
    
$datedif= abs(strtotime($heat_start_time)  - strtotime($old_heat_endtime))/3600;
 $seconds = ($datedif * 3600);
$hours = floor($datedif);
$seconds -= $hours * 3600;
$minutes = floor($seconds / 60);
$seconds -= $minutes * 60;
$heat_gap = lz($hours) . ":" . lz($minutes);
}
//TOTAL ROLLING PRODUCTION CALCULATED IN THE METRIC TON 

$totalrollingprod = number_format((float)($prod8mm + $prod10mm + $prod12mm + $prod16mm + $prod20mm + $prod25mm + $prod28mm + $prod32mm),3,'.','');
//Total CCCM PRODUCTION CALCULATED IN THE METRIC TON 
$ccmprod = ($totalrollingprod + $bbpprod3st + $bbprodmill + $bbprodccm + $bbprodcont + $bbprodmpeb  + $bbprodfnce +$millmrprod+ $rfmrprod);
//TOTAL HOT ROLLING PRODUCTION IN %
$totalhotrolng =number_format((float)($totalrollingprod / $ccmprod) * 100, 2,'.','');

// CALCUALTION FOR THE CUMMULATIVE HOT ROLLING , ROLLING PRODUCTION ,CCM PRODUCTION AND MISSROLL

if ($heatnumber == 1) {
    $cum_ccmprod = $ccmprod;
    $cum_hotroling = $totalhotrolng;
    $cum_totalrollprod = $totalrollingprod;
    $cum_totalmissroll = $totalmissroll;
} else {
    $cummHeatNumber = ($heatnumber - 1);
    
    $sql_cum = "select `cum_ccmprod` ,`cum_rollingprod`,`cum_missroll` from  jd2_rolling_per_heat_prod where `per_heat_date` = '" . $per_date . "' and `heat_number`='" . $cummHeatNumber . "'";
    echo 'after 1';
 
    $result_cum = mysqli_query($link, $sql_cum);
    $row_cum =    mysqli_fetch_row($result_cum);
    
  
    //CUMMULATIVE CCM PRODUCTION
    $cum_ccmprod = number_format((float)($row_cum[0] + $ccmprod),3,'.','');
  
    //CUMMULATIVE ROLLING PRODUCTION
    $cum_totalrollprod=number_format((float)($row_cum[1] + $totalrollingprod),3,'.','');
    //CUMMULATIVE MISSROLL 
    $cum_totalmissroll = ($row_cum[2] + $totalmissroll);
    //CUMMULATIVE HOT ROLLING PERCENTAGE 
    $cum_hotroling=number_format((float)(($cum_totalrollprod/$cum_ccmprod)*100),2,'.','');
}

// CONVERTING CUM_HOTROLLING IN 2 PLACES OF DECIMAL
//$cum_hotroling = number_format((float) $cum_hotroling, 2, '.', '');

// CALCULATION FOR THE PERCENTAGE BILLETS BYPAAS PRODUCTION DUE TO MILL
$perbbpmill = number_format((float)(($bbprodmill / $ccmprod) * 100), 2,'.','');
// CALCULATION FOR THE PERCENTAGE BILLETS BYPAAS PRODUCTION DUE TO CCM
$perbbpccm = number_format((float)(($bbprodccm / $ccmprod) * 100), 2,'.','');
// CALCULATION FOR THE PERCENTAGE BILLETS BYPAAS PRODUCTION DUE TO 3RD STAND
$perbbp3st =  number_format((float)(($bbpprod3st/ $ccmprod) * 100), 2,'.','');
// CALCULATION FOR THE PERCENTAGE BILLETS BYPAAS PRODUCTION DUE TO FURNACE
$perbbpfnce = ($bbprodfnce / $ccmprod) * 100;
// CALCULATION FOR THE PERCENTAGE BILLETS BYPAAS PRODUCTION DUE TO CONTRACTOR
$perbbpcont = ($bbprodcont / $ccmprod) * 100;
// CALCULATION FOR THE PERCENTAGE BILLETS BYPAAS PRODUCTION DUE TOMPEB
$perbbpmpeb =  number_format((float)(($bbprodmpeb / $ccmprod) * 100),2,'.','');
// CALCULATION FOR THE PERCENTAGE BILLETS BYPAAS PRODUCTION DUE TO OTHER

//CALCULATION MISSROLL PRODUCTION PERCENTAGE CALCULATION
$permrprod= number_format((float)(($totalmrprod/ $ccmprod) * 100), 2,'.','');
echo"<br>";
echo "permrprod:" ;echo $permrprod;



if($ccmprod == 0){
    $perbbpmill=0; $perbbpccm=0; $perbbp3st=0;$perbbpfnce=0;$perbbpcont=0;$perbbpmpeb=0;
$perbbpother=0; $permrprod=0;$totalhotrolng=0; 
}

$m18mm=$m28mm=$m110mm=$m210mm=$m112mm=$m212mm=$m116mm=$m216mm=$m120mm=$m220mm=$m125mm=$m225mm=$m128mm=$m228mm=$m132mm=$m232mm=0;
$T8rfmr=$T16rfmr=$T20rfmr=$T12rfmr=$T25rfmr=$T28rfmr=$T32rfmr=0;
$m1c8mm=$m2c8mm=$m1c10mm=$m2c10mm=$m1c12mm=$m2c12mm=$m1c16mm=$m2c16mm=$m1c20mm=$m2c20mm=$m1c25mm=$m2c25mm=$m1c28mm=$m2c28mm=$m1c32mm=$m2c32mm=0;
$T8cut=$T10cut=$T12cut=$T16cut=$T20cut=$T25cut=$T28cut=$T32cut=0;
$Trfmrprod=$Tcuttingprod=0;


// Calculating Total MR Production and  Roughing Production
if(($m1s1==8 )|| ($m1s2==8)) {
$m18mm=RollingBD::getInstance()->get_roughing_mr_prod_mill1(filter_input(INPUT_POST,'perheatdate'),filter_input(INPUT_POST,'heatnumber'),2);
}
if(($m2s1==8) ||($m2s2==8)){
$m28mm=RollingBD::getInstance()->get_roughing_mr_prod_mill2(filter_input(INPUT_POST,'perheatdate'),filter_input(INPUT_POST,'heatnumber'),2);
}
$T8rfmr= ($m18mm + $m28mm);
if(($m1s1==10 )|| ($m1s2==10)) {
$m110mm=RollingBD::getInstance()->get_roughing_mr_prod_mill1(filter_input(INPUT_POST,'perheatdate'),filter_input(INPUT_POST,'heatnumber'),3);
}
if(($m2s1==10) ||($m2s2==10)){
$m210mm=RollingBD::getInstance()->get_roughing_mr_prod_mill2(filter_input(INPUT_POST,'perheatdate'),filter_input(INPUT_POST,'heatnumber'),3);
}
$T10rfmr= $m110mm + $m210mm;
if(($m1s1==12 )|| ($m1s2==12)) {
$m112mm=RollingBD::getInstance()->get_roughing_mr_prod_mill1(filter_input(INPUT_POST,'perheatdate'),filter_input(INPUT_POST,'heatnumber'),4);
}
if(($m2s1==12) ||($m2s2==12)){
$m212mm=RollingBD::getInstance()->get_roughing_mr_prod_mill2(filter_input(INPUT_POST,'perheatdate'),filter_input(INPUT_POST,'heatnumber'),4);
}
$T12rfmr= $m112mm + $m212mm;

if(($m1s1==16 )|| ($m1s2==16)) {
$m116mm=RollingBD::getInstance()->get_roughing_mr_prod_mill1(filter_input(INPUT_POST,'perheatdate'),filter_input(INPUT_POST,'heatnumber'),5);
}
if(($m2s1==16) ||($m2s2==16)){
$m216mm=RollingBD::getInstance()->get_roughing_mr_prod_mill2(filter_input(INPUT_POST,'perheatdate'),filter_input(INPUT_POST,'heatnumber'),5);
}
$T16rfmr= $m116mm + $m216mm;
if(($m1s1==20 )|| ($m1s2==20)) {
$m120mm=RollingBD::getInstance()->get_roughing_mr_prod_mill1(filter_input(INPUT_POST,'perheatdate'),filter_input(INPUT_POST,'heatnumber'),6);
}
if(($m2s1==20) ||($m2s2==20)){
$m220mm=RollingBD::getInstance()->get_roughing_mr_prod_mill2(filter_input(INPUT_POST,'perheatdate'),filter_input(INPUT_POST,'heatnumber'),6);
}
$T20rfmr= $m120mm + $m220mm;

if(($m1s1==25 )|| ($m1s2==25)) {
$m125mm=RollingBD::getInstance()->get_roughing_mr_prod_mill1(filter_input(INPUT_POST,'perheatdate'),filter_input(INPUT_POST,'heatnumber'),7);
}
if(($m2s1==25) ||($m2s2==25)){
$m225mm=RollingBD::getInstance()->get_roughing_mr_prod_mill2(filter_input(INPUT_POST,'perheatdate'),filter_input(INPUT_POST,'heatnumber'),7);
}
$T25rfmr= $m125mm + $m225mm;

if(($m1s1==28 )|| ($m1s2==28)) {
$m128mm=RollingBD::getInstance()->get_roughing_mr_prod_mill1(filter_input(INPUT_POST,'perheatdate'),filter_input(INPUT_POST,'heatnumber'),8);
}
if(($m2s1==28) ||($m2s2==28)){
$m228mm=RollingBD::getInstance()->get_roughing_mr_prod_mill2(filter_input(INPUT_POST,'perheatdate'),filter_input(INPUT_POST,'heatnumber'),8);
}
$T28rfmr= $m128mm + $m228mm;

if(($m1s1==32 )|| ($m1s2==32)) {
$m132mm=RollingBD::getInstance()->get_roughing_mr_prod_mill1(filter_input(INPUT_POST,'perheatdate'),filter_input(INPUT_POST,'heatnumber'),9);
}
if(($m2s1==32) ||($m2s2==32)){
$m232mm=RollingBD::getInstance()->get_roughing_mr_prod_mill2(filter_input(INPUT_POST,'perheatdate'),filter_input(INPUT_POST,'heatnumber'),9);
}
$T32rfmr= $m132mm + $m232mm;



$totalrfmrprod=$T8rfmr+$T10rfmr+$T12rfmr+$T16rfmr+$T20rfmr+$T25rfmr+$T32rfmr+$T28rfmr;
  //echo $totalrfmrprod;
  
  // Calculating Total Cutting Production
if(($m1s1==8 )|| ($m1s2==8)) {
$m1c8mm=RollingBD::getInstance()->get_cutting_prod_mill1(filter_input(INPUT_POST,'perheatdate'),filter_input(INPUT_POST,'heatnumber'),2);
}
if(($m2s1==8) ||($m2s2==8)){
$m2c8mm=RollingBD::getInstance()->get_cutting_prod_mill2(filter_input(INPUT_POST,'perheatdate'),filter_input(INPUT_POST,'heatnumber'),2);
}
$T8cut= $m1c8mm + $m2c8mm;
if(($m1s1==10 )|| ($m1s2==10)) {
$m1c10mm=RollingBD::getInstance()->get_cutting_prod_mill1(filter_input(INPUT_POST,'perheatdate'),filter_input(INPUT_POST,'heatnumber'),3);
}
if(($m2s1==10) ||($m2s2==10)){
$m2c10mm=RollingBD::getInstance()->get_cutting_prod_mill2(filter_input(INPUT_POST,'perheatdate'),filter_input(INPUT_POST,'heatnumber'),3);
}
$T10cut= $m1c10mm + $m2c10mm;

if(($m1s1==12)|| ($m1s2==12)) {
$m1c12mm=RollingBD::getInstance()->get_cutting_prod_mill1(filter_input(INPUT_POST,'perheatdate'),filter_input(INPUT_POST,'heatnumber'),4);
}
if(($m2s1==12) ||($m2s2==12)){
$m2c12mm=RollingBD::getInstance()->get_cutting_prod_mill2(filter_input(INPUT_POST,'perheatdate'),filter_input(INPUT_POST,'heatnumber'),4);
}
$T12cut= $m1c12mm + $m2c12mm;
if(($m1s1==16 )|| ($m1s2==16)) {
$m1c16mm=RollingBD::getInstance()->get_cutting_prod_mill1(filter_input(INPUT_POST,'perheatdate'),filter_input(INPUT_POST,'heatnumber'),5);
}
if(($m2s1==16) ||($m2s2==16)){
$m2c16mm=RollingBD::getInstance()->get_cutting_prod_mill2(filter_input(INPUT_POST,'perheatdate'),filter_input(INPUT_POST,'heatnumber'),5);
}
$T16cut= $m1c16mm + $m2c16mm;

if(($m1s1==20 )|| ($m1s2==20)) {
$m1c20mm=RollingBD::getInstance()->get_cutting_prod_mill1(filter_input(INPUT_POST,'perheatdate'),filter_input(INPUT_POST,'heatnumber'),6);
}
if(($m2s1==20) ||($m2s2==20)){
$m2c20mm=RollingBD::getInstance()->get_cutting_prod_mill2(filter_input(INPUT_POST,'perheatdate'),filter_input(INPUT_POST,'heatnumber'),6);
}
$T20cut= $m1c20mm + $m2c20mm;

if(($m1s1==25 )|| ($m1s2==25)) {
$m1c25mm=RollingBD::getInstance()->get_cutting_prod_mill1(filter_input(INPUT_POST,'perheatdate'),filter_input(INPUT_POST,'heatnumber'),7);
}
if(($m2s1==25) ||($m2s2==25)){
$m2c25mm=RollingBD::getInstance()->get_cutting_prod_mill2(filter_input(INPUT_POST,'perheatdate'),filter_input(INPUT_POST,'heatnumber'),7);
}
$T25cut= $m1c25mm + $m2c25mm;

if(($m1s1==28 )|| ($m1s2==28)) {
$m1c28mm=RollingBD::getInstance()->get_cutting_prod_mill1(filter_input(INPUT_POST,'perheatdate'),filter_input(INPUT_POST,'heatnumber'),8);
}
if(($m2s1==28) ||($m2s2==28)){
$m2c28mm=RollingBD::getInstance()->get_cutting_prod_mill2(filter_input(INPUT_POST,'perheatdate'),filter_input(INPUT_POST,'heatnumber'),8);
}
$T28cut= $m1c28mm + $m2c28mm;

if(($m1s1==32 )|| ($m1s2==32)) {
$m1c32mm=RollingBD::getInstance()->get_cutting_prod_mill1(filter_input(INPUT_POST,'perheatdate'),filter_input(INPUT_POST,'heatnumber'),9);
}
if(($m2s1==32) ||($m2s2==32)){
$m2c32mm=RollingBD::getInstance()->get_cutting_prod_mill2(filter_input(INPUT_POST,'perheatdate'),filter_input(INPUT_POST,'heatnumber'),9);
}
$T32cut= $m1c32mm + $m2c32mm;
$totalcuttingprod=$T8cut+$T10cut+$T12cut+$T16cut+$T20cut+$T25cut+$T28cut+$T32cut;

/**
 * Roughing missroll Production.at location 1,2,3,4,5,6,7,8
 */
$F_m1s1=$F_m1s2=$F_m2s1=$F_m2s2=$F_ms1=$F_ms2=0;
$R_8mm=$R_10mm=$R_12mm=$R_20mm=$R_25mm=$R_28mm=$R_32mm=$R_16mm=0;
$TMRLRFPROD=RollingBD::getInstance()->get_mr_roughing_side(filter_input(INPUT_POST,'perheatdate'),filter_input(INPUT_POST,'heatnumber'));

if (($mill1_size1 > $mill1_size2) && ($mill1_size1 > $mill2_size1) && ($mill1_size1 > $mill2_size2)) {
    $F_m1s1 = $TMRLRFPROD;
} elseif (($mill1_size2 > $mill1_size1) && ($mill1_size2 > $mill2_size1) && ($mill1_size2 > $mill2_size2)) {
    $F_m1s2 = $TMRLRFPROD;
} elseif (($mill2_size1 > $mill1_size1) && ($mill2_size1 > $mill1_size2) && ($mill2_size1 > $mill2_size2)) {
    $F_m2s1 = $TMRLRFPROD;
} elseif (($mill2_size2 > $mill1_size1) && ($mill2_size2 > $mill1_size2) && ($mill2_size2 > $mill2_size1)) {
    $F_m2s2 = $TMRLRFPROD;
} elseif ($mill1_size1 == $mill2_size1) {
    $F_ms1 = $TMRLRFPROD;
} elseif ($mill1_size2 == $mill2_size2) {
    $F_ms2 = $TMRLRFPROD;
} elseif ($mill2_size1 == $mill2_size2) {
    $F2_ms2 = $TMRLRFPROD;
}
$missrollprod = 0;

if ($F_m1s1 !== 0) {
    $size = $mill1_size1;   
    $missrollprod = $F_m1s1;
} elseif ($F_m1s2 !== 0) {
    $size = $mill1_size2;
    $missrollprod = $F_m1s2;
} elseif ($F_m2s1 !== 0) {
    $size = $mill2_size1;
    $missrollprod = $F_m2s1;

    echo $F_m2s1;
} elseif ($F_m2s2 !== 0) {
    $size = $mill2_size2;
    $missrollprod = $F_m2s2;
} elseif ($F_ms1 !== 0) {
    $size = 2;
    $missrollprod = $F_ms1;
    echo"in1";
} elseif ($F_ms2 !== 0) {
    $size = 2;
    $missrollprod = $F_ms2;
    echo"in2";
} elseif ($F2_ms2 !== 0) {
    $size = $mill2_size2;
    $missrollprod = $F2_ms2;
    echo"in3";
}

switch ($size) {
    case 2;
        $R_8mm = $missrollprod;
        break;
    case 3;
        $R_10mm = $missrollprod;
        break;
    case 4;
        $R_12mm = $missrollprod;
        break;
    case 5;
        $R_16mm = $missrollprod;
        break;
    case 6;
        $R_20mm = $missrollprod;
        break;
    case 7;
        $R_25mm = $missrollprod;
        break;
    case 8;
        $R_28mm = $missrollprod;
        break;
    case 8;
        $R_32mm = $missrollprod;
        break;
}
/**
 * QUERY TO INSERT VALUES IN THE PER HEAT PRODUCTION TABLE 
 */
if(isset($_REQUEST['update'])){
    $perheatid=filter_input(INPUT_POST,'per_heat_id');
    
    $p11= mysqli_fetch_row(mysqli_query($link,"select * from jd2_rolling_per_heat_prod where `per_heat_id`='".$perheatid."'"));
   $n_ccmprod=$p11[58];
  $n_rolling_prod=$p11[56];
    
  $per_sql = mysqli_query($link, "UPDATE `jd2_rolling_per_heat_prod` SET `per_heat_date`='" . $per_date . "',`shift`='" . $shift . "',
            `roughing`='" . $roughing . "',
            `mill1_size1`='" . $mill1_size1 . "',`mill1_size2`='" . $mill1_size2 . "',`mill2_size1`='" . $mill2_size1 . "',`mill2_size2`='" . $mill2_size2 . "',
           `heat_number`='" . $heatnumber . "',
          `heat_start_time`= '" . $heat_start_time . "'  , `heat_end_time`='" . $heat_end_time . "',
           `total_heat_time`='" . $totalheattime . "',`total_bd_time`='" . $totalbdtime . "',
          `3mtrbilletwt_m1s1`='" . $bwt3mtm1s1 . "',
            `rolledpcs_m1s1`='" . $rpm1s1 . "',`3mtrbilletwt_m1s2`='" . $bwt3mtm1s2 . "',`rolledpcs_m1s2`='" . $rpm1s2 . "',
          `3mtrbilletwt_m2s1`='" . $bwt3mtm2s1 . "',
         `rolledpcs_m2s1`='" . $rpm2s1 . "',`3mtrbilletwt_m2s2`='" . $bwt3mtm2s2 . "',`rolledpcs_m2s2`='" . $rpm2s2 . "',
          `total_rolled_pcs`='" . $totalrolledpcs . "',
         `3stand_3mtr_billetsbypass`='" . $bbp3st3m . "',`3stand_6mtr_billetsbypass`='" . $bbp3st6m . "',`3stand_3mtr_billetweight`='" . $bwt3m3s . "',`3stand_6mtr_billetweight`='" . $bwt6m3s . "',
         `ccm_3mtr_billetweight`='" . $bwt3mccm . "',
         `ccm_3mtr_billetsbypass`='" . $bbp3mccm . "',`ccm_6mtr_billetweight`='" . $bwt6mccm . "',`ccm_6mtr_billetsbypass`='" . $bbp6mccm . "',
        `billets_bypass_only_ccm`='" . $bbppurelyccm . "',`total_missroll`='" . $totalmissroll . "',
        `cum_missroll`='" . $cum_totalmissroll . "',`mill_missroll_prod`='" . $millmrprod . "',`rf_missroll_prod`='" . $rfmrprod . "',
        `cum_rf_missroll_prod`='" . $cum_rfmrprod . "',`billets_bypass_prod_mill`='" . $bbprodmill . "',
        `billets_bypass_prod_ccm`='" . $bbprodccm . "',`billets_bypass_prod_3stand`='" . $bbpprod3st . "',
        `billets_bypass_prod_furnace`='" . $bbprodfnce . "',`billets_bypass_prod_mpeb`='" . $bbprodmpeb . "',                                      
        `billets_bypass_prod_contractor`='" . $bbprodcont . "',
       `mill1_size1_prod`='" . $m1s1prod . "',`mill1_size2_prod`='" . $m1s2prod . "',
        `mill2_size1_prod`='" . $m2s1prod . "',`mill2_size2_prod`='" . $m2s2prod . "',`8mm_prod`='" . $prod8mm . "',`10mm_prod`='" . $prod10mm . "',`12mm_prod`='" . $prod12mm . "',
         `16mm_prod`='" . $prod16mm . "',`20mm_prod`='" . $prod20mm . "',`25mm_prod`='" . $prod25mm . "',`28mm_prod`='" . $prod28mm . "',
        `32mm_prod`='" . $prod32mm . "',`rollingprod`='" . $totalrollingprod . "',
        `cum_rollingprod`='" . $cum_totalrollprod . "',`ccmprod`='" . $ccmprod . "',`cum_ccmprod`='" . $cum_ccmprod . "',
        `hotrolling`='" . $totalhotrolng . "',`cum_hotrolling`='" . $cum_hotroling . "',
        `perc_billets_bypass_mill`='" . $perbbpmill . "',`perc_billets_bypass_ccm`='" . $perbbpccm . "',`perc_billets_bypass_3stand`='" . $perbbp3st . "',
        `perc_billets_bypass_furnace`='" . $perbbpfnce . "',`perc_billets_bypass_mpeb`='" . $perbbpmpeb . "',
        `perc_billets_bypass_contractor`='" . $perbbpcont . "',`perc_missroll_prod`='" . $permrprod . "',
        `8rf_missroll_prod`='" . $T8rfmr . "',`10rf_missroll_prod`='" . $T10rfmr . "',`12rf_missroll_prod`='" . $T12rfmr . "',`16rf_missroll_prod`='" . $T16rfmr . "',
         `20rf_missroll_prod`='" . $T20rfmr . "',`25rf_missroll_prod`='" . $T25rfmr . "',
         `28rf_missroll_prod`='" . $T28rfmr . "',`32rf_missroll_prod`='" . $T32rfmr . "',
         `8cut_prod`='" . $T8cut . "',`10cut_prod`='" . $T10cut . "',`12cut_prod`='" . $T12cut . "',`16cut_prod`='" . $T16cut . "',
        `20cut_prod`='" . $T20cut . "',`25cut_prod`='" . $T25cut . "',`28cut_prod`='" . $T28cut . "',`32cut_prod`='" . $T32cut . "',
        `total_rfmissroll_prod`='" . $totalrfmrprod . "',
        `total_cutting_prod`='" . $totalcuttingprod . "',`rf_side_8mm_prod`='" . $R_8mm . "',`rf_side_10mm_prod`='" . $R_10mm . "',
            `rf_side_12mm_prod`='" . $R_12mm . "',`rf_side_16mm_prod`='" . $R_16mm . "',
        `rf_side_20mm_prod`='" . $R_20mm . "',`rf_side_25mm_prod`='" . $R_25mm . "',`rf_side_28mm_prod`='" . $R_28mm . "',
        `rf_side_32mm_prod`='" . $R_32mm . "',`final_rf_mr_prod`='" . $TMRLRFPROD . "'
        WHERE per_heat_id='" . $perheatid . "'");


    if (!$per_sql) {
        die(mysqli_error($link));
        exit();
    } else {
        echo "Record Updated";
    }
    //Fetching endcut 8mm, 10mm, 12mm,16mm,20mm,25mm,28mm,32mm and enduct mrwt and nettmt.
    $p0 = mysqli_fetch_row(mysqli_query($link, "select * from jd2_rolling_kpi_24hrs where kpi_date='" . $per_date . "'"));

    $e8mm = $p0[11] / 1000;
    $e10mm = $p0[12] / 1000;
    $e12mm = $p0[13] / 1000;
    $e16mm = $p0[14] / 1000;
    $e20mm = $p0[15] / 1000;
    $e25mm = $p0[16] / 1000;
    $e28mm = $p0[17] / 1000;
    $e32mm = $p0[18] / 1000;
    $emrwt = $p0[19] / 1000;
    $o_nettmt = $p0[80];


//checking whethere the row in the rolling kpi table exists or not.
    $p1 = mysqli_num_rows(mysqli_query($link, "select* from jd2_rolling_kpi_24hrs where kpi_date='" . $per_date . "'"));
    if ($p1 > 0) {
        $total_rolled_pcs = $final_rolling_prod = $final_ccmprod = $missroll = $bypass = $ccm_rolled_pc = $TBBPCCM = $f_24hrhotrolling = $final_monhotrolling = 0;
        $total_rolled_pcs = RollingBD::getInstance()->get_total_rolled_pcs(filter_input(INPUT_POST, 'perheatdate'));
        $final_rolling_prod = RollingBD::getInstance()->get_rolling_prod(filter_input(INPUT_POST, 'perheatdate'));
        $thirdstandbypass = RollingBD::getInstance()->billets_bypass_prod_3rdstand(filter_input(INPUT_POST, 'perheatdate'));

        // fetching millmissroll prod,roughing missroll prod, billets bypass prod of mill,ccm,furnace,mpeb, contra,others

        $p3 = mysqli_fetch_row(mysqli_query($link, "select * from `jd2_rolling_per_heat_prod` where per_heat_date='" . $per_date . "' and `heat_number`='" . $heatnumber . "'"));

        $mrfr = $p3[34];
        $millrf = $p3[35];
        $mill = $p3[37];
        $ccm = $p3[38];
        $thirdstand = $p3[39];
        $furnace = $p3[40];
        $mpeb = $p3[41];
        $cont = $p3[42];
        $other = $p3[43];
        $n_ccmprod = $p11[58];
        $n_rolling_prod = $p11[56];

        $d_ccmprod = $ccmprod - $n_ccmprod;
        echo "ccmproddiff";
        echo $d_ccmprod;
        $d_rollingprod = $totalrollingprod - $n_rolling_prod;

        $p10 = mysqli_query($link, "update `jd2_rolling_per_heat_prod` set `cum_ccmprod`=`cum_ccmprod`+$d_ccmprod,`cum_rollingprod`=`cum_rollingprod`+$d_rollingprod 
          where per_heat_date='" . $per_date . "' and `heat_number`>='" . $heatnumber . "'");

        if (!$p10) {
            echo mysqli_error($link);
        }



        // Calculating Updated CCM production 
  //$final_ccmprod=$mrfr+$millrf+$mill+$ccm+$thirdstand+$furnace+$mpeb+$cont+$other+$final_rolling_prod;
  
  $final_ccmprod=RollingBD::getInstance()->get_ccm_prod(filter_input(INPUT_POST,'perheatdate'));
  //Calculating Updated Missroll Productiomfrom breakdown
  $missroll=RollingBD::getInstance()->get_total_mr_ina_day(filter_input(INPUT_POST,'perheatdate'));
  // Calculation total bypasss from breakdown 
  $bypass= RollingBD::getInstance()->get_total_billets_bypass(filter_input(INPUT_POST,'perheatdate'));
// Calculating Total CCM pieces.
 $bbp3mccm= RollingBD::getInstance()->get_billets_bypass_3mtr_ccm(filter_input(INPUT_POST,'perheatdate'));
$bbp6mccm= RollingBD::getInstance()->get_billets_bypass_6mtr_ccm(filter_input(INPUT_POST,'perheatdate'));

  $ccm_rolled_pc=$bbp6mccm*2+$bbp3mccm+$bbp3st3m+$bbp3st6m+$missroll+$bypass+$total_rolled_pcs;
  
  // TOTAL bILLETS BYPASS OF CCM.
  $TBBPCCM=RollingBD::getInstance()->get_billets_bypass(filter_input(INPUT_POST,'perheatdate'),2)+$bbp6mccm*2+$bbp3mccm;
  //CALCULATING UPDATED 24 HOURS OF HOT ROLLING
  $f_24hrhotrolling=($total_rolled_pcs/$ccm_rolled_pc)*100;
  // CALCULATING MONTHLY HOT ROLLING
  //$final_monhotrolling=($final_rolling_prod/$final_ccmprod)*100;
  // CALCULATING ROUGHING MISSROLL PRODUCTION OF 8,10,12,16,,20,25,28,32
 $r8=$r10=$r12=$r16=$r20=$r25=$r28=$r32=$r_total=0;
  
$r8=RollingBD::getInstance()->get_8_rfmr(filter_input(INPUT_POST,'perheatdate'));
$r10=RollingBD::getInstance()->get_10_rfmr(filter_input(INPUT_POST,'perheatdate'));
$r12=RollingBD::getInstance()->get_12_rfmr(filter_input(INPUT_POST,'perheatdate'));
$r16=RollingBD::getInstance()->get_16_rfmr(filter_input(INPUT_POST,'perheatdate'));
$r20=RollingBD::getInstance()->get_20_rfmr(filter_input(INPUT_POST,'perheatdate'));
$r25=RollingBD::getInstance()->get_25_rfmr(filter_input(INPUT_POST,'perheatdate'));
$r28=RollingBD::getInstance()->get_28_rfmr(filter_input(INPUT_POST,'perheatdate'));
$r32=RollingBD::getInstance()->get_32_rfmr(filter_input(INPUT_POST,'perheatdate'));
//get_8mm_rfside
$rfside8=$rfside10=$rfside12=$rfside16=$rfside20=$rfside25=$rfside28=$rfside32=0;
$rfside8=RollingBD::getInstance()->get_8mm_rfside(filter_input(INPUT_POST,'perheatdate'));
$rfside10=RollingBD::getInstance()->get_10mm_rfside(filter_input(INPUT_POST,'perheatdate'));
$rfside12=RollingBD::getInstance()->get_12mm_rfside(filter_input(INPUT_POST,'perheatdate'));
$rfside16=RollingBD::getInstance()->get_16mm_rfside(filter_input(INPUT_POST,'perheatdate'));
$rfside20=RollingBD::getInstance()->get_20mm_rfside(filter_input(INPUT_POST,'perheatdate'));
$rfside25=RollingBD::getInstance()->get_25mm_rfside(filter_input(INPUT_POST,'perheatdate'));
$rfside28=RollingBD::getInstance()->get_28mm_rfside(filter_input(INPUT_POST,'perheatdate'));
$rfside32=RollingBD::getInstance()->get_32mm_rfside(filter_input(INPUT_POST,'perheatdate'));

// CALCULATING TOTAL ROUGHING MISSROLL PRODUCTION 
$r_total=$r8+$r10+$r12+$r16+$r20+$r25+$r28+$r32;

// CALCULATING TOTAL SIZE WISE PRODUCTION AND BURNING LOSS PERCENTAGE.
 $T8=$B8=$T10=$B10=$T12=$B12=$T16=$B16=$B20=$B25=$T16=$T20=$T28=$B28=$T32=$B32=$b_total=0;
 
 $T8=RollingBD::getInstance()->get_bl_8mm(filter_input(INPUT_POST,'perheatdate'))+($r8)+$rfside8;
  $B8= ($T8*1.35)/100;
  
  $T10=RollingBD::getInstance()->get_bl_10mm(filter_input(INPUT_POST,'perheatdate'))+($r10)+$rfside10;
  
  $B10= ($T10*1.25)/100; 
  
  $T12=RollingBD::getInstance()->get_bl_12mm(filter_input(INPUT_POST,'perheatdate'))+($r12)+$rfside12;
  $B12= ($T12*1.15)/100;
  
  $T16=RollingBD::getInstance()->get_bl_16mm(filter_input(INPUT_POST,'perheatdate'))+($r16)+$rfside16;
  $B16= ($T16*0.85)/100;
  
  $T20=RollingBD::getInstance()->get_bl_20mm(filter_input(INPUT_POST,'perheatdate'))+($r20)+$rfside20;
  $B20= ($T20*0.75)/100;
    
  $T25=RollingBD::getInstance()->get_bl_25mm(filter_input(INPUT_POST,'perheatdate'))+($r25)+$rfside25;
  $B25= ($T25*0.7)/100;
  
  $T28=RollingBD::getInstance()->get_bl_28mm(filter_input(INPUT_POST,'perheatdate'))+($r28)+$rfside28;
  $B28= ($T28*1.2)/100;
  
  $T32=RollingBD::getInstance()->get_bl_32mm(filter_input(INPUT_POST,'perheatdate'))+($r32)+$rfside32;
  $B32= ($T32*0.55)/100;
  
  
$b_total=$B8+$B10+$B12+$B16+$B20+$B25+$B28+$B32;

//cutting production calculated size wise and overall
$c8=$c10=$c12=$c16=$c20=$c25=$c28=$c32=$c_total=0;
$c8=RollingBD::getInstance()->get_8_cut(filter_input(INPUT_POST,'perheatdate'));
$c10=RollingBD::getInstance()->get_10_cut(filter_input(INPUT_POST,'perheatdate'));
$c12=RollingBD::getInstance()->get_12_cut(filter_input(INPUT_POST,'perheatdate'));
$c16=RollingBD::getInstance()->get_16_cut(filter_input(INPUT_POST,'perheatdate'));
$c20=RollingBD::getInstance()->get_20_cut(filter_input(INPUT_POST,'perheatdate'));
$c25=RollingBD::getInstance()->get_25_cut(filter_input(INPUT_POST,'perheatdate'));
$c28=RollingBD::getInstance()->get_28_cut(filter_input(INPUT_POST,'perheatdate'));
$c32=RollingBD::getInstance()->get_32_cut(filter_input(INPUT_POST,'perheatdate'));
$c_total=$c8+$c10+$c12+$c16+$c20+$c25+$c28+$c32;
  //NET TMT CALCULATION SIZE WISE , 8MM ,10MM,12MM,16MM,20MM,25MM,28MM,32MM AND
$n8=$n10=$n12=$n16=$n20=$n25=$n28=$n32=$n_nettmt=0;

$n8= $T8-($r8+$c8+$B8+($e8mm)+$rfside8);
$n10=$T10-($r10+$c10+$B10+($e10mm)+$rfside10);
$n12=$T12-($r12+$c12+$B12+($e12mm)+$rfside12);
$n16=$T16-($r16+$c16+$B16+($e16mm)+$rfside16);
$n20=$T20-($r20+$c20+$B20+($e20mm)+$rfside20);
$n25=$T25-($r25+$c25+$B25+($e25mm)+$rfside25);
$n28=$T28-($r28+$c28+$B28+($e28mm)+$rfside28);
$n32=$T32-($r32+$c32+$B32+($e32mm)+$rfside32);
echo $n10; echo "<br>"; echo $n12;
//NETTMT CALCULATION 
$n_nettmt=$n8+$n10+$n12+$n16+$n20+$n25+$n28+$n32-$emrwt;
echo"<br>"; echo "nettmt";
echo $n_nettmt;
//DIFFERENCE OF THE NETTMT
$d_nettmt=$n_nettmt-$o_nettmt;

//FIRST DATE OF THE cURRENT MONTH
$f_date = date('Y-m-01', strtotime($per_date)); // hard-coded '01' for first day
//LAST DATE OF THE CURRENT MONTH
$l_date  = date('Y-m-t', strtotime($per_date));
//TOTAL HEAT RUN TIME
$heat_run_time = RollingBD::getInstance()->get_heat_running_time(filter_input(INPUT_POST,'perheatdate'));
//GET FIRST HEAT TIME OF CURRENT DATE AND HEAT NUMBER 
$f_heattime = RollingBD::getInstance()->get_First_Heat_Start_Time(filter_input(INPUT_POST,'perheatdate'));
//LAST HEAT TIME OF THE CURRENT DATE AND HEAT NUMBER
$l_heattime = RollingBD::getInstance()->get_Last_Heat_End_Time(filter_input(INPUT_POST,'perheatdate'));


// CALCULATION OF THE TOTAL HEAT TIME
//Difference of the Lastheat end time and first heat start time
$totaltime= abs(strtotime($l_heattime) - strtotime($f_heattime))/3600;
$seconds_ph1 = ($totaltime * 3600);
$hours_ph1 = floor($totaltime);
$seconds_ph1 -= $hours_ph1 * 3600;
$minutes_ph1 = floor($seconds_ph1 / 60);
$seconds_ph1 -= $minutes_ph1 * 60;
// Converting  them in to hh:mm format
$t_heat_time = lz($hours_ph1) . ":" . lz($minutes_ph1);
echo "<br>";



$T_heatgap=RollingBD::getInstance()->get_total_heat_gap(filter_input(INPUT_POST,'perheatdate'));
$total_bd_time=RollingBD::getInstance()->get_bd_time_of_a_day(filter_input(INPUT_POST,'perheatdate'));

$CCMBYPASS=RollingBD::getInstance()->billets_by_pass_prod_due_ccm(filter_input(INPUT_POST,'perheatdate'));
$PBYPASSCCM=$CCMBYPASS/$final_ccmprod;

$ELECBYPASSPROD=$PELECBYPASSPROD=$MILLBYPASSPROD=$PMILLBYPASSPROD=$MECHBYPASSPROD=$PMECHBYPASSPROD=$FMILLBYPASSPROD=0;

$PCONTBYPASSPROD=0;
$PCONTBYPASSPROD=RollingBD::getInstance()->get_bypass_prod(filter_input(INPUT_POST,'perheatdate'),6)/$final_ccmprod;
$PFURNACEBYPASSPROD=0;
$PFURNACEBYPASSPROD=RollingBD::getInstance()->get_bypass_prod(filter_input(INPUT_POST,'perheatdate'),1)/$final_ccmprod;

$ELECBYPASSPROD=RollingBD::getInstance()->get_bypass_prod(filter_input(INPUT_POST,'perheatdate'),3);
$PELECBYPASSPROD=$ELECBYPASSPROD/$final_ccmprod;

$MILLBYPASSPROD=RollingBD::getInstance()->get_bypass_prod(filter_input(INPUT_POST,'perheatdate'),5);
$PMILLBYPASSPROD=$MILLBYPASSPROD/$final_ccmprod;

$MECHBYPASSPROD=RollingBD::getInstance()->get_bypass_prod(filter_input(INPUT_POST,'perheatdate'),4);
$PMECHBYPASSPROD=$MECHBYPASSPROD/$final_ccmprod;

$FMILLBYPASSPROD=$PELECBYPASSPROD+$PMILLBYPASSPROD+$PMECHBYPASSPROD;

$FROLLINGAVAILABILITY=100-$FMILLBYPASSPROD;

$MPEBBYPASSPROD=$PMPEBBYPASSPROD=0;
//total bypass and bypass percentagge due to mpeb.
$MPEBBYPASSPROD=RollingBD::getInstance()->get_bypass_prod(filter_input(INPUT_POST,'perheatdate'),7);
$PMPEBBYPASSPROD=$MPEBBYPASSPROD/$final_ccmprod;


$TBPRODPC=$PBPPRODP=0;

$TBPRODPC=RollingBD::getInstance()->get_bypass_prod_reason(filter_input(INPUT_POST,'perheatdate'),37);
$PBPPRODPC=($TBPRODPC/$final_ccmprod)*100;


$TBPRODSC=$PBPPRODSC=0;

$TBPRODSC=RollingBD::getInstance()->get_bypass_prod_reason(filter_input(INPUT_POST,'perheatdate'),52);
$PBPPRODSC=($TBPRODSC/$final_ccmprod)*100;

$TBPRODRC=$PBPPRODRC=$TBBRC=0;

$TBPRODRC=RollingBD::getInstance()->get_bypass_prod_reason(filter_input(INPUT_POST,'perheatdate'),47);
$PBPPRODRC=($TBPRODRC/$final_ccmprod)*100;
$TBBRC=RollingBD::getInstance()->get_billets_bypass_reason(filter_input(INPUT_POST,'perheatdate'),47);
$TBPRODMENT=$PBPPRODMENT=0;

$TBPRODMENT=RollingBD::getInstance()->get_bypass_prod_reason(filter_input(INPUT_POST,'perheatdate'),30);
$PBPPRODMENT=($TBPRODMENT/$final_ccmprod)*100;

$TBPRODLR=$PBPPRODLR=0;



$TBPRODLR=RollingBD::getInstance()->get_bypass_prod_reason(filter_input(INPUT_POST,'perheatdate'),26);
$PBPPRODLR=($TBPRODSC/$final_ccmprod)*100;

$TBPRODCK=$PBPPRODCK=0;
$TBPRODCK=RollingBD::getInstance()->get_bypass_prod_reason(filter_input(INPUT_POST,'perheatdate'),4);
$PBPPRODCK=($TBPRODCK/$final_ccmprod)*100;

$TBPRODPP=$PBPPRODPP=0;
$TBPRODPP=RollingBD::getInstance()->get_bypass_prod_reason(filter_input(INPUT_POST,'perheatdate'),41);
$PBPPRODPP=($TBPRODPP/$final_ccmprod)*100;

$TBPRODMO=$PBPPRODMO=0;
$TBPRODMO=RollingBD::getInstance()->get_bypass_prod_reason(filter_input(INPUT_POST,'perheatdate'),33);
$PBPPRODMO=($TBPRODMO/$final_ccmprod)*100;

$TBPRODMC=$PBPPRODMC=0;
$TBPRODMC=RollingBD::getInstance()->get_bypass_prod_reason(filter_input(INPUT_POST,'perheatdate'),62);
$PBPPRODMC=($TBPRODMC/$final_ccmprod)*100;

$TBPRODQC=$PBPPRODQC=0;
$TBPRODQC=RollingBD::getInstance()->get_bypass_prod_reason(filter_input(INPUT_POST,'perheatdate'),10);
$PBPPRODQC=($TBPRODQC/$final_ccmprod)*100;

// UPDATING MONTHLY NETTMT ON THE BASIS OF THE CHANGES IN THE PER HEAT FORM.
 $p2=mysqli_query($link,"update jd2_rolling_kpi_24hrs set monthly_net_tmt=`monthly_net_tmt`+$d_nettmt where kpi_date between '$f_date' and '$l_date' ");
 
 $p12=mysqli_fetch_row(mysqli_query($link, "select  sum(gross_tmt), sum(total_ccm_prod) from `jd2_rolling_kpi_24hrs` where kpi_date between '".$f_date."' and'".$l_date."' "));
   
 echo $grosstmt=$p12[0]+$final_rolling_prod;echo"<br>";
 echo $grosccm=$p12[1]+$final_ccmprod; echo"<br>"; echo"<br>"; echo "gross tmt"; echo $p12[0];
 $final_monhotrolling=(($p12[0])/($p12[1]))*100;
 
 echo $final_monhotrolling; 

    //FINALLLY UPDATING VALUES TO THE KPI TABLE.
   
   $p4= mysqli_query($link,"update jd2_rolling_kpi_24hrs set `gross_tmt`= '" . $final_rolling_prod . "',`total_ccm_prod`='" . $final_ccmprod . "',
           `heat_run_time`='" . $heat_run_time . "',`total_heat_time`='" . $t_heat_time . "',`heat_gap`='" . $T_heatgap . "',
            `total_rolling_pieces`='" . $total_rolled_pcs . "',total_ccm_pieces='" . $ccm_rolled_pc . "',
           `prod_8mm`='" . $T8 . "',`prod_10mm`='" . $T10 . "',`prod_12mm`='" . $T12 . "',`prod_16mm`='" . $T16 . "',`prod_20mm`='" . $T20 . "',
           `prod_25mm`='" . $T25 . "',`prod_28mm`='" . $T28 . "',`prod_32mm`='" . $T32 . "',`rfmr_8prod`='" . $r8 . "',`rfmr_10prod`='" . $r10 . "',`rfmr_12prod`='" . $r12 . "',
           `rfmr_16prod`='" . $r16 . "',`rfmr_20prod`='" . $r20 . "',`rfmr_25prod`='" . $r25 . "',`rfmr_28prod`='" . $r28 . "',`rfmr_32prod`='" . $r32 . "',
           `bloss_8mm_prod`='" . $B8 . "',`bloss_10mm_prod`='" . $B10 . "',`bloss_12mm_prod`='" . $B12 . "',`bloss_16mm_prod`='" . $B16 . "',`bloss_20mm_prod`='" . $B20 . "',`bloss_25mm_prod`='" . $B25 . "',
           `bloss_28mm_prod`='" . $B28 . "' ,`bloss_32mm_prod`='" . $B32 . "',`cut_8_prod`='" . $c8 . "',`cut_10_prod`='" . $c10 . "',`cut_12_prod`='" . $c12 . "',
           `cut_16_prod`='" . $c16 . "',`cut_20_prod`='" . $c20 . "',`cut_25_prod`='" . $c25 . "',cut_28_prod ='" . $c28 . "',`cut_32_prod`='" . $c32 . "',
           `net_8mm_prod`='" . $n8 . "',`net_10mm_prod`='" . $n10 . "',`net_12mm_prod`='" . $n12 . "',`net_16mm_prod`='" . $n16 . "',`net_20mm_prod`='" . $n20 . "',
           `net_25mm_prod`='" . $n25 . "',`net_28mm_prod`='" . $n28 . "',`net_32mm_prod`='" . $n32 . "',`net_tmt`='" . $n_nettmt . "',`totalcuttingprod`='" . $c_total . "',
           `totalrfmrprod`='" . $r_total . "',`total_bloss_prod`='" . $b_total . "',`total_billets_bypass_ccm_only`= '" . $TBBPCCM . "',
           `monthlyhotrolling`='" . $final_monhotrolling . "',`24hrshotrolling`='" . $f_24hrhotrolling . "',`billets_bypass_prod_mill_percent`='" . $PMILLBYPASSPROD . "',
           `billets_bypass_prod_mech_percent`='" . $PMECHBYPASSPROD . "',`billets_bypass_prod_elec_percent`='" . $PELECBYPASSPROD . "',`billets_bypass_prod_passchange_percent`='" . $PBPPRODPC . "',`billets_bypass_prod_size_change_percent`='" . $PBPPRODSC . "',
           `billets_bypass_prod_maintenance_percent` ='" . $PBPPRODMENT . "',`billets_bypass_prod_mpeb_percent`='" . $PMPEBBYPASSPROD . "',`billets_bypass_prod_lahar_percent`='" . $PBPPRODLR . "',`billets_bypass_prod_crack_percent`='" . $PBPPRODCK . "',
            `billets_bypass_prod_piping_percent`='" . $PBPPRODPP . "',`	billets_bypass_prod_mouthopen_percent`='" . $PBPPRODMO . "',`billets_bypass_prod_chilli_percent`='" . $PBPPRODMC . "',
           `billets_bypass_prod_ccd_composition_percent`='" . $PBPPRODQC . "',`percent_billets_bypass_prod_mill`='" . $FMILLBYPASSPROD . "',
           `bilelts_bypass_prod_3stand_percent`='" . $thirdstandbypass . "',`billets_bypass_contractor_percent`='".$PCONTBYPASSPROD."',`total_bd_time`='".$total_bd_time."'
            where `kpi_date`='" . $per_date . "'");


        // calculating total missrollprod permissrollprod , cutttingprod dept wise and total permillmr prod
 $TOTALMRPROD=$TPERMRPROD=$TOTALMILLMRPROD=$TPERMILLMRPROD=$TPERMILLCCMPROD=$PERCUTTINGPROD=$PERMILLCUTTINGPROD=$PERCCMCUTTINGPROD=$PERMPEBCUTTINGPROD=$PERFNCECUTTINGPROD=$perendcutwt=$permillccmprod=0;
$finalperendcut=$Ncutting=$Nmillcutting=$Nccmcutting=$Nfncecutting=$Nmpebcutting=0;


$Ncutting=   RollingBD::getInstance()->get_total_cutting(filter_input(INPUT_POST,'perheatdate'));
$Nmillcutting= RollingBD::getInstance()->get_total_cutting_mill(filter_input(INPUT_POST,'perheatdate'));
$Nccmcutting=RollingBD::getInstance()->get_total_cutting_ccm(filter_input(INPUT_POST,'perheatdate'));
$Nfncecutting=RollingBD::getInstance()->get_total_cutting_fnce(filter_input(INPUT_POST,'perheatdate'));
$Nmpebcutting=RollingBD::getInstance()->get_total_cutting_mpeb(filter_input(INPUT_POST,'perheatdate'));

$TOTALMRPROD=RollingBD::getInstance()->get_mr_prod(filter_input(INPUT_POST,'perheatdate'));
$TPERMRPROD=($TOTALMRPROD/$n_nettmt)*100;



$TOTALMILLMRPROD=RollingBD::getInstance()->get_mr_prod_mill(filter_input(INPUT_POST,'perheatdate'));

$TPERMILLMRPROD=($TOTALMILLMRPROD/$n_nettmt)*100;
$TPERMILLCCMPROD=($TOTALMILLMRPROD/$final_ccmprod)*100;

$PERCUTTINGPROD=($Ncutting*.100)/$n_nettmt;

$PERMILLCUTTINGPROD=($Nmillcutting*.100)/$n_nettmt;
$PERCCMCUTTINGPROD=($Nccmcutting*.100)/$n_nettmt;
$PERFNCECUTTINGPROD=($Nfncecutting*.100)/$n_nettmt;
$PERMPEBCUTTINGPROD=($Nmpebcutting*100)/$n_nettmt;

$perendcutwt=($emrwt/$n_nettmt)*100;

$finalperendcut=$TPERMRPROD+$perendcutwt+$PERCUTTINGPROD;
$permillccmprod=($TOTALMILLMRPROD/$final_ccmprod)*100;


$k6=mysqli_query($link,"update jd2_rolling_kpi_24hrs set `total_cutting_perc`='".$PERCUTTINGPROD."',`total_cutting_mill_perc`='".$PERMILLCUTTINGPROD."',
        `total_cutting_ccm_perc`='".$PERCCMCUTTINGPROD."',`total_furnace_cutting_perc`='".$PERFNCECUTTINGPROD."',`total_mpeb_cutting_perc`='".$PERMPEBCUTTINGPROD."', 
        `missroll_percent`='".$TPERMRPROD."',`mill_missroll_percent`='".$TPERMILLMRPROD."',`endcut_missroll_perc`='".$perendcutwt."',`missroll_cutting_prod_percent`='".$finalperendcut."',
        `mill_missroll_from_ccm_percent`='".$permillccmprod."' WHERE `kpi_date`='".$per_date."'");
    
}  
 //Slack::getInstance()->postMessagesToSlack_perheat("PerheatProduction Updated
        //", "Test");
        
//header("Location: http://192.168.2.141/Rolling_Jd2/Home.php");

}
else{
$sql_ph = "INSERT INTO `jd2_rolling_per_heat_prod`(`per_heat_date`, `shift`,`roughing`,`heat_number`,`heat_start_time`, `heat_end_time`, 
        `total_heat_time`, `total_bd_time`,`mill1_size1`, `mill1_size2`, 
        `mill2_size1`, `mill2_size2`, `3mtrbilletwt_m1s1`, `rolledpcs_m1s1`, `3mtrbilletwt_m1s2`, `rolledpcs_m1s2`,
        `3mtrbilletwt_m2s1`, `rolledpcs_m2s1`, `3mtrbilletwt_m2s2`, `rolledpcs_m2s2`, `total_rolled_pcs`, 
        `3stand_3mtr_billetsbypass`, `3stand_6mtr_billetsbypass`, `3stand_3mtr_billetweight`, `3stand_6mtr_billetweight`,
        `ccm_3mtr_billetweight`, `ccm_3mtr_billetsbypass`, `ccm_6mtr_billetweight`, `ccm_6mtr_billetsbypass`, 
        `billets_bypass_only_ccm`, `total_missroll`, `cum_missroll`, `mill_missroll_prod`, `rf_missroll_prod`, 
        `cum_rf_missroll_prod`, `billets_bypass_prod_mill`, `billets_bypass_prod_ccm`, `billets_bypass_prod_3stand`, 
        `billets_bypass_prod_furnace`, `billets_bypass_prod_mpeb`, `billets_bypass_prod_contractor`,
        `mill1_size1_prod`, `mill1_size2_prod`, `mill2_size1_prod`, `mill2_size2_prod`, `8mm_prod`, `10mm_prod`, `12mm_prod`, 
        `16mm_prod`, `20mm_prod`, `25mm_prod`, `28mm_prod`, `32mm_prod`, `rollingprod`, `cum_rollingprod`, `ccmprod`, `cum_ccmprod`,
        `hotrolling`, `cum_hotrolling`, `perc_billets_bypass_mill`, `perc_billets_bypass_ccm`, `perc_billets_bypass_3stand`, 
        `perc_billets_bypass_furnace`, `perc_billets_bypass_mpeb`, `perc_billets_bypass_contractor`, 
        `perc_missroll_prod`, `8rf_missroll_prod`, `10rf_missroll_prod`, `12rf_missroll_prod`, `16rf_missroll_prod`, 
        `20rf_missroll_prod`, `25rf_missroll_prod`, `28rf_missroll_prod`, `32rf_missroll_prod`, `8cut_prod`, `10cut_prod`, 
        `12cut_prod`, `16cut_prod`, `20cut_prod`, `25cut_prod`, `28cut_prod`, `32cut_prod`, `total_rfmissroll_prod`, 
        `total_cutting_prod`, `rf_side_8mm_prod`, `rf_side_10mm_prod`, `rf_side_12mm_prod`, `rf_side_16mm_prod`, 
        `rf_side_20mm_prod`, `rf_side_25mm_prod`, `rf_side_28mm_prod`, `rf_side_32mm_prod`, `final_rf_mr_prod`,`heat_gap`) VALUES
     
        ('" . $per_date . "','".$shift."','" . $roughing . "','" . $heatnumber . "','" . $heat_start_time . "','" . $heat_end_time . "','" . $totalheattime . "','" . $totalbdtime . "',"
        . " '" . $mill1_size1 . "', '" . $mill1_size2 . "','" . $mill2_size1 . "','" . $mill2_size2 . "' ,'" . $bwt3mtm1s1 . "','" . $rpm1s1 . "','" . $bwt3mtm1s2 . "','" . $rpm1s2 . "',"
        . "'" . $bwt3mtm2s1 . "','" . $rpm2s1 . "','" . $bwt3mtm2s2 . "','" . $rpm2s2 . "','".$totalrolledpcs."',"
        . "'" . $bbp3st3m . "','" . $bbp3st6m . "','" . $bwt3m3s . "','" . $bwt6m3s . "','" . $bwt3mccm . "','" . $bbp3mccm . "','" . $bwt6mccm . "',"
        . "'" . $bbp6mccm . "','" . $bbppurelyccm . "','" . $totalmissroll . "','" . $cum_totalmissroll . "','" . $millmrprod . "','" . $rfmrprod . "','" . $cum_rfmrprod . "',"
        . "'" . $bbprodmill . "','" . $bbprodccm . "','" . $bbpprod3st . "','" . $bbprodfnce . "','" . $bbprodmpeb . "','" . $bbprodcont . "','" . $m1s1prod . "',"
        . "'" . $m1s2prod . "','" . $m2s1prod . "','" . $m2s2prod . "','" . $prod8mm . "','" . $prod10mm . "','" . $prod12mm . "','" . $prod16mm . "','" . $prod20mm . "','" . $prod25mm . "','" . $prod28mm . "',"
        . "'" . $prod32mm . "','" . $totalrollingprod . "','" . $cum_totalrollprod . "','" . $ccmprod . "','" . $cum_ccmprod . "','" . $totalhotrolng . "','" . $cum_hotroling . "','" . $perbbpmill . "',"
        . "'" . $perbbpccm . "','" . $perbbp3st . "','" . $perbbpfnce . "','" . $perbbpmpeb . "','" . $perbbpcont . "','" . $permrprod . "',"
        . "'" . $T8rfmr . "','" . $T10rfmr . "','" . $T12rfmr . "','" . $T16rfmr. "','" . $T20rfmr. "','" . $T25rfmr . "','" . $T28rfmr . "',"
        . "'" . $T32rfmr . "','" . $T8cut . "','" . $T10cut . "','" . $T12cut . "','" . $T16cut . "','" . $T20cut . "','" . $T25cut . "','" . $T28cut . "',"
        . "'"  .$T32cut."' ,'". $totalrfmrprod . "','" . $totalcuttingprod . "','".$R_8mm."','".$R_10mm."',"
        . "'".$R_12mm."','".$R_16mm."','".$R_20mm."','".$R_25mm."','".$R_28mm."','".$R_32mm."','".$TMRLRFPROD."','".$heat_gap."')";

$result_ph = (mysqli_query($link, $sql_ph) or die(mysqli_error($link)));



// CHECKING THAT EITHER VALUES ARE INSERTED PROPERLY OR NOT
if ($result_ph) {
    echo"record added";
    //echo $row_cum;
} else {
    echo 'not added';
}
if(!$result_ph){
    echo mysqli_error($link);
}

$bdtimetotal = substr($totalbdtime,0,5);

if ($totalmissroll == '') {
    $totalmissroll = 0;
}
if ($cum_totalmissroll == '') {
    $cum_totalmissroll = 0;
}

if($bdtimetotal==''){
    $bdtimetotal='00:00';
}




mysqli_close($link);

//PSt Message to Slack CHANNEL ROLLING
 Slack::getInstance()->postMessagesToSlack_perheat("
`$roughing` 
*$heatnumber*
*$sTime_only* = *$eTime_only*    
*$bdtimetotal*
_*`$totalhotrolng`*_ (*`$cum_hotroling`*)
    *$perbbpmill*
    *$perbbpccm*,
    *$perbbp3st*,
    *$perbbpfnce* 
    *$perbbpmpeb*    
*M.R.%* _*`$permrprod`*_         
*$totalmissroll*,(*$cum_totalmissroll*)    
 $varA (_*`$cum_totalrollprod`*_)
        ", "Rolling_Jd2");

// ON FORM SUBMITTED REDIRECTED TO THE HOME.PH
header("Location: http://dataapp/Rolling_Jd2/Home.php");
}
exit();
