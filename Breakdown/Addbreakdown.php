    <?php
//TP GET THE DETAILS FROM THE FILLED FORMS AND INSERTING THEM TO THE ROLLING DATABASE IN BREAKDOWN TABLE
    
    
    
include('..\DBfile.php');
include('..\Connection.php');
include('..\postMessagesToSlack.php');
/* Attempt MySQL server connection. Assuming you are running MySQL
  server with default setting (user 'root' with no password) */


// Check connection
if ($link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
// FORMATING THE DATE IN THE YYYY-MM-DD , AS THE DATABASE SUPPORTS ONLY THIS FORMAT
$date = strtr($_REQUEST['date'], '/', '-');
$bd_date= date('Y-m-d', strtotime($date));

//$date = $_POST['date'];
//echo $date;
//$bd_date=date('Y-d-m', strtotime($date));

$hnumber = (filter_input(INPUT_POST,'hnumber'));
// GETTING THE M1SIZE AND M2SIZE FROM THE DBFILE.PHP (FROM SIZE TABLE)
$m1size = RollingBD::getInstance()->get_size_id(filter_input(INPUT_POST,'m1size'));
//echo $m1size;

$m2size = RollingBD::getInstance()->get_size_id(filter_input(INPUT_POST,'m2size'));
//echo $m2size;

$mill_1_size = (filter_input(INPUT_POST,'m1size'));
$mill_2_size = (filter_input(INPUT_POST,'m2size'));
$shift = (filter_input(INPUT_POST,'shift'));
$start_time = (filter_input(INPUT_POST,'sTime_only'));
$end_time = (filter_input(INPUT_POST,'eTime_only'));
$start_time_whole = (filter_input(INPUT_POST,'start_time'));
$end_time_whole = (filter_input(INPUT_POST,'end_time'));
$stand = (filter_input(INPUT_POST,'stand'));
$dependent_mr = (filter_input(INPUT_POST,'dependent_mr'));
$independent_mr = (filter_input(INPUT_POST,'independent_mr'));
$cutting =(filter_input(INPUT_POST,'cutting'));
$bp_3mtr = (filter_input(INPUT_POST,'bp_3mtr'));
$bp_6mtr = (filter_input(INPUT_POST,'bp_6mtr'));
$avg_3mtr = (filter_input(INPUT_POST,'avg_3mtr'));
$avg_6mtr = (filter_input(INPUT_POST,'avg_6mtr'));
$responsible_person = (filter_input(INPUT_POST,'responsible_person'));
$location_code = (filter_input(INPUT_POST,'location_code'));
$department = (filter_input(INPUT_POST,'department'));
$shift_formen = (filter_input(INPUT_POST,'shift_formen'));
$reasonid =(filter_input(INPUT_POST,'reasonid'));
$bd_action = (filter_input(INPUT_POST,'bd_action'));


//calculating time diff in decimal
/**$total_time = abs(strtotime($end_time_whole) - strtotime($start_time_whole)) / 3600;

//converting time from decimal to hh:mm format
// start by converting to seconds
 $seconds = ($total_time * 3600);
// we're given hours, so let's get those the easy way
$hours = floor($total_time);
// since we've "calculated" hours, let's remove them from the seconds variable
$seconds -= $hours * 3600;
// calculate minutes left
$minutes = floor($seconds / 60);
// remove those from seconds as well
$seconds -= $minutes * 60;
// return the time formatted HH:MM:SS
$total_time = lz($hours) . ":" . lz($minutes); //.":".lz($seconds);**/

//$total_billets_bypass = $bp3_mtr + ($bp6_mtr * 2);

//Function to check the time Difference

    
    echo filter_input(INPUT_POST,'start_time');
 
$bd_start_time  = date('Y-m-d H:i:s',strtotime(str_replace('-','/', $start_time_whole)));
$bd_end_time  = date('Y-m-d H:i:s',strtotime(str_replace('-','/', $end_time_whole)));
echo $bd_start_time;
echo "<br>"; echo $bd_end_time;

$datedif= abs(strtotime($bd_end_time)  - strtotime($bd_start_time))/3600;
echo $datedif; echo "<br>";
 $seconds = ($datedif * 3600);
echo $seconds; echo "<br>";
 $hours = floor($datedif);
echo $hours; echo "<br>";
 $seconds -= floor($hours * 3600);
 echo $seconds; echo"<br>";
$minutes = ($seconds / 60);
echo $minutes; echo "<br>";

$total_time_new = lz($hours) . ":" . lz($minutes);
echo $total_time_new;


$totaltimeinmin=$hours*60+$minutes;

function lz($num) {
    return (strlen($num) < 2) ? "0{$num}" : $num;
}

// CALCULATING TOTAL MISSROLLS (TOTAL MISSROL- INDEPENDENT + DEPENDENT MISSROLL)

$total_mr = (int)$dependent_mr + $independent_mr;
//CALCULATING TOTAL BILLETS BYPASS 

$total =(int) $bp_3mtr + ($bp_6mtr * 2);

//ALL THE PRODUCTION RELATED DATA MSUT BE CALCULATED IN METRIC TON 
//CALCULATING TOTAL MISS ROLLS PRODUCTION ( MR PRODUCTION = TOTAL MISSROLL * AVG 3MTR BILLET WEIGHT)

$mr_production= ($total_mr * $avg_3mtr)/1000;

// 3TR BILLETS BYPASS PRODUCTION ( 3MTRBILLETSBYPASS PRODUCTION = BILLETSBYPAS3MTR *AVG 3MTR BILLETSWEIGHT)/1000
$three_mtr_billets_bp_production = ($bp_3mtr * $avg_3mtr)/1000;
// 6MTR BILLETS BYPASS PRODUCTION ( 6MTR BILLETS BYPASS PRODUCTION= BILLETS BYPASS 6MTR* AVG 6MTR BILLET WEIGHT)/1000
$six_mtr_billets_bp_production = ($bp_6mtr * $avg_6mtr)/1000;

// TOAL BILLETS BYPASS PRODUCTION I.E. DUE TO 3MTR BILLETS AND 6MTRR BILLETS WEIGHT)
$total_bbp_production = $three_mtr_billets_bp_production + $six_mtr_billets_bp_production;

//TOTAL CUTTING WEIGHT
$total_cutting_wt= (int)($cutting)/10;

// GETTHE DETAILS FROM THE DEPARTMENT TABLE 
$dpt_id = RollingBD::getInstance()->get_department_id(filter_input(INPUT_POST,'department'));
//GET THE DETAILS FROM THE PERSON TABLE
$per_id = RollingBD::getInstance()->get_person_id(filter_input(INPUT_POST,'responsible_person'));
// GET THE DETAILS FROM THE LOCATION 
$loc_id = RollingBD::getInstance()->get_location_id(filter_input(INPUT_POST,'location_code'));
// GET THE DETAILS FROM THE REASON TABLE 
$rea_id = RollingBD::getInstance()->get_reason_id(filter_input(INPUT_POST,'reasonid'));

$f_bd_action=str_replace("'","\\'", $bd_action);

if(isset($_REQUEST['update'])){
 
    $bd_id=(filter_input(INPUT_POST,'bd_id'));
 
    // fetching total missroll from the breakdown Table before Updating .
    $a1= mysqli_query($link, "select `total_missroll` from jd2_rolling_breakdown where `breakdown_id`='".$bd_id."'");
$m1=mysqli_fetch_row($a1);
 $mr_dif=$total_mr-$m1[0];
//  query to update in the Breakdown Table.
 $query= "UPDATE `jd2_rolling_breakdown` SET `bd_date`='" . $bd_date . "',`heat_number`='" . $hnumber . "',`mill_1_size`='" . $mill_1_size . "',
     `mill_2_size`='" . $mill_2_size . "',`shift`='" . $shift . "',`bd_start_time`='" . $bd_start_time . "',
      `bd_end_time`='" . $bd_end_time . "',`bd_total_time`='" . $total_time_new . "',`stand`='" . $stand . "',
     `dep_missroll`='" . $dependent_mr . "',`indep_missroll`='" . $independent_mr . "',`total_missroll`='" . $total_mr . "',
     `total_cutting`='" . $cutting . "' ,`total_3mtr_billets_bypass`='" . $bp_3mtr . "',`total_6mtr_billets_bypass`='" . $bp_6mtr . "',`total_billets_bypass`='" . $total . "',
      `avg_3mtr_wt`='" . $avg_3mtr . "',`avg_6mtr_wt`='" . $avg_6mtr . "',`resp_per_id`=$responsible_person,
      `location_id`='" . $location_code . "',`dept_id`='" . $department . "',`shift_foreman_id`='" . $shift_formen . "',
      `bd_detail`='" . $bd_action . "',`reason_id`='" . $reasonid . "',`missroll_prod`='" . $mr_production . "',
     `3mtr_billets_bypass_prod`='" . $three_mtr_billets_bp_production . "',
      `6mtr_billetsbypass_prod`='".$six_mtr_billets_bp_production."',
      `total_billets_bypass_prod`='".$total_bbp_production."',`total_cutting_wt`='".$total_cutting_wt."' ,`bd_total_time_minutes`='".$totaltimeinmin."'
           WHERE  `breakdown_id`='" . $bd_id . "'";

$update= mysqli_query($link, $query);

   if(!$update){
       die(mysqli_error($link));
       exit();
   }
   else{
       echo "Record Updated";
      // Query to update Value in the Per heat Production Table that are linked to the Breakdown Table
       
       //checking that The rows exists in the Perheat table from the Same heat number and date.
         $n1=mysqli_num_rows(mysqli_query($link,"select * from jd2_rolling_per_heat_prod where per_heat_date='".$bd_date."' and `heat_number`='".$hnumber."'"));
  
  if ($n1>0)
{
   $t_missroll=0;  
   $t_mres=mysqli_fetch_row(mysqli_query($link,"select sum(`total_missroll`) from jd2_rolling_breakdown WHERE bd_date= '" . $bd_date ."' and `heat_number`='".$hnumber."'"));
  $t_missroll=$t_mres[0];
  echo"missroll"; echo $t_missroll;
  
  if(!$t_mres){
      echo mysqli_error($link);
  }
  
  
  
//$t_missroll=RollingBD::getInstance()->get_missroll_sum(filter_input(INPUT_POST,'date'), filter_input(INPUT_POST,'hnumber'));
$b30=mysqli_query($link,"update `jd2_rolling_per_heat_prod` set `total_missroll`=`$t_missroll` where per_heat_date='".$bd_date."' and `heat_number`='".$hnumber."'");


if(!$b30){
    mysqli_error($link);
}
// Updating Cum-missroll in the Per heat Table.
if (!$mr_dif == 0){
$a2 = mysqli_query ($link, "Update `jd2_rolling_per_heat_prod` set cum_missroll= (`cum_missroll`+$mr_dif) where per_heat_date='".$bd_date."' and `heat_number` >='".$hnumber."' ");

}

// End  

// updating the Billets Bypass production Deptwise from the breakdown Table to the Per heat Table.
$bbpfurnace=$bbpccm=$bbpinmill=$bbpcontr=$bbpmpeb=$bbpothers=0;
   
 if($department==1){ 
     $bbpfurnace=$total_bbp_production; 
 
 }if($department==2){
     $bbpccm=$total_bbp_production;
     
 }
 if(($department==3)||($department==4)||($department==5)){
     $bbpinmill=$total_bbp_production;
     
 }
 if($department==6){ 
     $bbpcontr=$total_bbp_production;
     
 }
 if($department==7){   
     $bbpmpeb=$total_bbp_production;
     
 }
 if($department==8){ 
     $bbpothers=$total_bbp_production;
     
 }
 $b2=mysqli_query($link,"update `jd2_rolling_per_heat_prod` set `billets_bypass_prod_mill`='".$bbpinmill."',`billets_bypass_prod_ccm`='".$bbpccm."'+`billets_bypass_only_ccm`,"
         . "`billets_bypass_prod_furnace`='".$bbpfurnace."',`billets_bypass_prod_mpeb`='".$bbpmpeb."',`billets_bypass_prod_contractor`='".$bbpcontr."' where per_heat_date='".$bd_date."' and `heat_number`='".$hnumber."'");

//End

 
 //UPDATING MILL MISSROLL AND ROUGHING MISSROLL PRODUCTION AND CCM PRODUCTION
 $rfprodmill=$mrprodmill=0;
if(($location_code==1)||($location_code==2)||($location_code==3)||($location_code==4)||($location_code==5)||($location_code==6)||($location_code==11)){  
$rfprodmill=RollingBD::getInstance()->get_mr_roughing_side(filter_input(INPUT_POST,'date'), filter_input(INPUT_POST,'hnumber'));
 
} 
 if(($location_code==7)||($location_code==8)||($location_code==9)||($location_code==10)||($location_code==11)||($location_code==12)||($location_code==13)||($location_code==16)||($location_code==17)){
$mrprodmill=RollingBD::getInstance()->get_rfmr_production_mill_side(filter_input(INPUT_POST,'date'), filter_input(INPUT_POST,'hnumber'));
 }
 
 /**
  * fetching the old ccm production and roughing missroll production
  * 
  */
 

 $totalbdtime =RollingBD::getInstance()->get_bd_down_time(filter_input(INPUT_POST,'date'), filter_input(INPUT_POST,'hnumber'));
 
 $b13= mysqli_fetch_row(mysqli_query($link,"select `ccmprod`,`rf_missroll_prod` from jd2_rolling_per_heat_prod where per_heat_date='".$bd_date."' and `heat_number`='".$hnumber."'"));

$old_ccm_prod=$b13[0];     
$old_rfmrprod=$b13[1];
 
 $b3=mysqli_query($link,"update `jd2_rolling_per_heat_prod` set `mill_missroll_prod`='".$mrprodmill."', `rf_missroll_prod`='".$rfprodmill."',
     ccmprod=`rollingprod`+`billets_bypass_prod_3stand`+$rfprodmill +$mrprodmill+$bbpinmill+$bbpccm+$bbpfurnace+$bbpcontr+$bbpothers,`total_bd_time`='".$totalbdtime."'
         where per_heat_date='".$bd_date."' and `heat_number`='".$hnumber."'");
 if(!$b3){
     echo mysqli_error($link);
 }
 
 //END

 $b13=mysqli_query($link,"update `jd2_rolling_per_heat_prod` set `total_missroll`='".$t_missroll."' where per_heat_date='".$bd_date."' and `heat_number`='".$hnumber."'");
 //FETCHING vALUES FROM THE PERHEAT TABLE TO CALCULATE CUMMULATIVE CCM PRODUCTION AND ROUGHING PRODUCTION AND HOTROLLING 
 
 $b6 = mysqli_fetch_row(mysqli_query($link, "select `ccmprod`,`billets_bypass_prod_mill`,
     `billets_bypass_prod_ccm`,`billets_bypass_prod_furnace`,`billets_bypass_prod_mpeb`,`billets_bypass_prod_contractor`,
        `billets_bypass_prod_other`,`hotrolling`,`rf_missroll_prod`,`cum_rollingprod`,
        `8rf_missroll_prod`,`10rf_missroll_prod`,`12rf_missroll_prod`,`16rf_missroll_prod`,
        `20rf_missroll_prod`,`25rf_missroll_prod`,
        `28rf_missroll_prod`,`32rf_missroll_prod`,`8cut_prod`,`10cut_prod`,`12cut_prod`,
        `16cut_prod`,`20cut_prod`,`25cut_prod`,`28cut_prod`,`32cut_prod`
         from `jd2_rolling_per_heat_prod`
        where per_heat_date='" . $bd_date . "' and `heat_number`='" . $hnumber . "'"));

//echo $b6[0];
$ccm_prod=$b6[0];
$new_hotrolling=$b6[7];
$new_rfmrprod=$b6[8];

$diff_ccm_prod= $ccm_prod-$old_ccm_prod;
//$diff_hotrolling= $new_hotrolling-$old_hot_rolling;
$diff_rfmrprod=$new_rfmrprod-$old_rfmrprod;
/*
 * Updating Cummulaive ccmprod , hot rolling and roughing missroll Production.
 */
echo "data updated4";
$b9=mysqli_query($link,"update `jd2_rolling_per_heat_prod` set `cum_ccmprod` = ($diff_ccm_prod+`cum_ccmprod`) ,`cum_rf_missroll_prod`=($diff_rfmrprod+`cum_rf_missroll_prod`),
        `cum_hotrolling`=(`cum_rollingprod`)/($diff_ccm_prod+`cum_ccmprod`)*100,`hotrolling`=`rollingprod`/('$ccm_prod')*100 
        where per_heat_date='".$bd_date."' and `heat_number` >='".$hnumber."'");

//END
echo "data updated5";
/*
 * CALCULATING PERCENTAGE OF BILLETS BYPASS PRODUCTION DEPARTMENT WISE AND UPDATING THEM IN THE TABLE
 */
$perbfurnace= $perbccm= $perbmill=$perbother=$perbmpeb=$perbcont=0;
 
if($department==1){
     $perbfurnace=$b6[3]/$ccm_prod;
     
 }
 if($department==2){ 
     $perbccm=$b6[2]/$ccm_prod; 
     
 }
 if(($department==3)||($department==4)||($department==5)){  
     $perbmill=$b6[1]/$ccm_prod; 
     
 }
 if($department==8){ 
     $perbother=$b6[5]/$ccm_prod;
     
 }
 if($department==7){
     $perbmpeb=$b6[4]/$ccm_prod; 
     
 }
 if($department==6){ 
     $perbcont=$b6[6]/$ccm_prod;
     
 }


$b7= mysqli_query($link, "update `jd2_rolling_per_heat_prod` set `perc_billets_bypass_mill`='".$perbmill."',`perc_billets_bypass_ccm`='".$perbccm."',
        `perc_billets_bypass_furnace`='".$perbfurnace."',`perc_billets_bypass_mpeb`='".$perbmpeb."',`perc_billets_bypass_contractor`='".$perbcont."',
       `perc_billets_bypass_other`='".$perbother."' where per_heat_date='".$bd_date."' and `heat_number`='".$hnumber."'");
echo "data updated6";
/*
 * CALCULATING ROUGHING AND MISSROLL PRODUCTION SIZE WISE FROM bREAKDOWN TABLE TO PER HEAT PRODUCTION TABLE
 */

$r18mm=$r110mm=$r112mm=$r116mm=$r120mm=$r125mm=$r128mm=$r132mm=$r28mm=$r210mm=$r212mm=$r216mm=$r220mm=$r225mm=$r228mm=$r232mm=$T8mm=$T10mm=$T12mm=$T16mm=$T20mm=$T25mm=$T28mm=$T32mm=$tprodrf=0;

$r18mm=RollingBD::getInstance()->get_roughing_mr_prod_mill1(filter_input(INPUT_POST,'date'),filter_input(INPUT_POST,'hnumber'),2);
$r28mm=RollingBD::getInstance()->get_roughing_mr_prod_mill2(filter_input(INPUT_POST,'date'),filter_input(INPUT_POST,'hnumber'),2);
//roughing 8mm
$T8mm=$r18mm+$r28mm;

$r110mm=RollingBD::getInstance()->get_roughing_mr_prod_mill1(filter_input(INPUT_POST,'date'),filter_input(INPUT_POST,'hnumber'),3);
$r210mm=RollingBD::getInstance()->get_roughing_mr_prod_mill2(filter_input(INPUT_POST,'date'),filter_input(INPUT_POST,'hnumber'),3);

$T10mm= $r110mm+$r210mm;

$r112mm=RollingBD::getInstance()->get_roughing_mr_prod_mill1(filter_input(INPUT_POST,'date'),filter_input(INPUT_POST,'hnumber'),4);
$r212mm=RollingBD::getInstance()->get_roughing_mr_prod_mill2(filter_input(INPUT_POST,'date'),filter_input(INPUT_POST,'hnumber'),4);

$T12mm=$r112mm+$r212mm;
$r116mm=RollingBD::getInstance()->get_roughing_mr_prod_mill1(filter_input(INPUT_POST,'date'),filter_input(INPUT_POST,'hnumber'),5);
$r216mm=RollingBD::getInstance()->get_roughing_mr_prod_mill2(filter_input(INPUT_POST,'date'),filter_input(INPUT_POST,'hnumber'),5);

$T16mm=$r116mm+$r216mm;
$r120mm=RollingBD::getInstance()->get_roughing_mr_prod_mill1(filter_input(INPUT_POST,'date'),filter_input(INPUT_POST,'hnumber'),6);
$r220mm=RollingBD::getInstance()->get_roughing_mr_prod_mill2(filter_input(INPUT_POST,'date'),filter_input(INPUT_POST,'hnumber'),6);

$T20mm=$r120mm+$r220mm;
$r125mm=RollingBD::getInstance()->get_roughing_mr_prod_mill1(filter_input(INPUT_POST,'date'),filter_input(INPUT_POST,'hnumber'),7);
$r225mm=RollingBD::getInstance()->get_roughing_mr_prod_mill2(filter_input(INPUT_POST,'date'),filter_input(INPUT_POST,'hnumber'),7);
$T25mm=$r125mm+$r225mm;

$r128mm=RollingBD::getInstance()->get_roughing_mr_prod_mill1(filter_input(INPUT_POST,'date'),filter_input(INPUT_POST,'hnumber'),8);
$r228mm=RollingBD::getInstance()->get_roughing_mr_prod_mill2(filter_input(INPUT_POST,'date'),filter_input(INPUT_POST,'hnumber'),8);
$T28mm=$r128mm+$r228mm;

$r132mm=RollingBD::getInstance()->get_roughing_mr_prod_mill1(filter_input(INPUT_POST,'date'),filter_input(INPUT_POST,'hnumber'),9);
$r232mm=RollingBD::getInstance()->get_roughing_mr_prod_mill2(filter_input(INPUT_POST,'date'),filter_input(INPUT_POST,'hnumber'),9);

$T32mm=$r132mm+$r232mm;

$tprodrf=$T8mm+$T10mm+$T12mm+$T16mm+$T20mm+$T25mm+$T28mm+$T32mm;

/**
 * CALCULATING CUTTING PRODUCTION FROM BREAKDOWN TABLE TO PER HEAT TABLE.
 */
$c110mm=$c18mm=$c112mm=$c116mm=$C120mm=$c125mm=$c128mm=$c132mm=$c28mm=$c210mm=$c212mm=$c216mm=$c220mm=$c225mm=$c228mm=$c232mm=$Tcutprod=$TC8mm=$TC10mm=$TC12mm=$TC16mm=$TC20mm=$TC25mm=$TC28mm=$TC32mm=0;

$c18mm=RollingBD::getInstance()->get_cutting_prod_mill1(filter_input(INPUT_POST,'date'),filter_input(INPUT_POST,'hnumber'),2);
$c28mm=RollingBD::getInstance()->get_cutting_prod_mill2(filter_input(INPUT_POST,'date'),filter_input(INPUT_POST,'hnumber'),2);

$TC8mm=$c18mm+$c28mm;

$c110mm=RollingBD::getInstance()->get_cutting_prod_mill1(filter_input(INPUT_POST,'date'),filter_input(INPUT_POST,'hnumber'),3);
$c210mm=RollingBD::getInstance()->get_cutting_prod_mill2(filter_input(INPUT_POST,'date'),filter_input(INPUT_POST,'hnumber'),3);

$TC10mm=$c110mm+$c210mm;

$c112mm=RollingBD::getInstance()->get_cutting_prod_mill1(filter_input(INPUT_POST,'date'),filter_input(INPUT_POST,'hnumber'),4);
$c212mm=RollingBD::getInstance()->get_cutting_prod_mill2(filter_input(INPUT_POST,'date'),filter_input(INPUT_POST,'hnumber'),4);

$TC12mm=$c112mm+$c212mm;

$c116mm=RollingBD::getInstance()->get_cutting_prod_mill1(filter_input(INPUT_POST,'date'),filter_input(INPUT_POST,'hnumber'),5);
$c216mm=RollingBD::getInstance()->get_cutting_prod_mill2(filter_input(INPUT_POST,'date'),filter_input(INPUT_POST,'hnumber'),5);

$TC16mm=$c116mm+$c216mm;

$c120mm=RollingBD::getInstance()->get_cutting_prod_mill1(filter_input(INPUT_POST,'date'),filter_input(INPUT_POST,'hnumber'),6);
$c220mm=RollingBD::getInstance()->get_cutting_prod_mill2(filter_input(INPUT_POST,'date'),filter_input(INPUT_POST,'hnumber'),6);

$TC20mm=$c120mm+$c220mm;

$c125mm=RollingBD::getInstance()->get_cutting_prod_mill1(filter_input(INPUT_POST,'date'),filter_input(INPUT_POST,'hnumber'),7);
$c225mm=RollingBD::getInstance()->get_cutting_prod_mill2(filter_input(INPUT_POST,'date'),filter_input(INPUT_POST,'hnumber'),7);

$TC25mm=$c125mm+$c225mm;

$c128mm=RollingBD::getInstance()->get_cutting_prod_mill1(filter_input(INPUT_POST,'date'),filter_input(INPUT_POST,'hnumber'),8);
$c228mm=RollingBD::getInstance()->get_cutting_prod_mill2(filter_input(INPUT_POST,'date'),filter_input(INPUT_POST,'hnumber'),8);

$TC28mm=$c128mm+$c228mm;

$c132mm=RollingBD::getInstance()->get_cutting_prod_mill1(filter_input(INPUT_POST,'date'),filter_input(INPUT_POST,'hnumber'),9);
$c232mm=RollingBD::getInstance()->get_cutting_prod_mill2(filter_input(INPUT_POST,'date'),filter_input(INPUT_POST,'hnumber'),9);

$TC32mm=$c132mm+$c232mm;

$Tcutprod=$TC8mm+$TC10mm+$TC12mm+$TC16mm+$TC20mm+$TC25mm+$TC28mm+$TC32mm;

//CALCULATING  RROUGHING PRODUCTION BEFORE UPDATE. IN THE TABLE.

$orf8mm=    RollingBD::getInstance()->get_8_rfmr(filter_input(INPUT_POST,'date'));
$orf10mm=   RollingBD::getInstance()->get_10_rfmr(filter_input(INPUT_POST,'date'));
$orf12mm=   RollingBD::getInstance()->get_12_rfmr(filter_input(INPUT_POST,'date'));
$orf16mm=   RollingBD::getInstance()->get_16_rfmr(filter_input(INPUT_POST,'date'));
$orf20mm=   RollingBD::getInstance()->get_20_rfmr(filter_input(INPUT_POST,'date'));
$orf25mm=   RollingBD::getInstance()->get_25_rfmr(filter_input(INPUT_POST,'date'));
$orf28mm=   RollingBD::getInstance()->get_28_rfmr(filter_input(INPUT_POST,'date'));
$orf32mm=   RollingBD::getInstance()->get_32_rfmr(filter_input(INPUT_POST,'date'));
/*
 * UPDATING ROUGHING AND CUTTING MISSROLL PRODUCTION IN THE PERHEAT TABLE AND ALSO  UPDATING BD TOTAL TIME IN THE PER HEAT TABLE.
 */

$b10= mysqli_query($link, "update `jd2_rolling_per_heat_prod` set `8rf_missroll_prod`='".$T8mm."',`10rf_missroll_prod`='".$T10mm."',
        `12rf_missroll_prod`='".$T12mm."',`16rf_missroll_prod`='".$T16mm."',`20rf_missroll_prod`='".$T20mm."',`25rf_missroll_prod`='".$T25mm."',`32rf_missroll_prod`='".$T32mm."',
        `total_rfmissroll_prod`='".$tprodrf."',`8cut_prod`='".$TC8mm."',`10cut_prod`='".$TC10mm."',`12cut_prod`='".$TC12mm."',`16cut_prod`='".$TC16mm."',`20cut_prod`='".$TC20mm."',
         `25cut_prod`='".$TC25mm."',`28cut_prod`='".$TC28mm."',`32cut_prod`='".$TC32mm."',`total_cutting_prod`='".$Tcutprod."',`total_bd_time`='".$total_time_new."'
        where `per_heat_date`='".$bd_date."' and `heat_number` ='".$hnumber."' ");

 }
// HERE THE END WITH THE DATA UPDATE IN THE PER HEAT TABLE .
 // NOW STARTS WITH THE DATA UPDATE IN THE KPI TABLE. 
 // first it will check whethere the row in the kpi table will exist or not.
 $n2=mysqli_fetch_row(mysqli_query($link, "select * from jd2_rolling_kpi_24hrs where kpi_date='".$bd_date."'"));
if($n2>0){
    
$rf8mm=    RollingBD::getInstance()->get_8_rfmr(filter_input(INPUT_POST,'date'));
$rf10mm=   RollingBD::getInstance()->get_10_rfmr(filter_input(INPUT_POST,'date'));
$rf12mm=   RollingBD::getInstance()->get_12_rfmr(filter_input(INPUT_POST,'date'));
$rf16mm=   RollingBD::getInstance()->get_16_rfmr(filter_input(INPUT_POST,'date'));
$rf20mm=   RollingBD::getInstance()->get_20_rfmr(filter_input(INPUT_POST,'date'));
$rf25mm=   RollingBD::getInstance()->get_25_rfmr(filter_input(INPUT_POST,'date'));
$rf28mm=   RollingBD::getInstance()->get_28_rfmr(filter_input(INPUT_POST,'date'));
$rf32mm=   RollingBD::getInstance()->get_32_rfmr(filter_input(INPUT_POST,'date'));

$Trfprod=$rf8mm+$rf10mm+$rf12mm+$rf16mm+$rf20mm+$rf25mm+$rf28mm+$rf32mm;

// Calcualting the difference between old roughing and new roughing production.

$d_8=$rf8mm-$orf8mm;
$d_10=$rf10mm-$orf10mm; 
$d_12=$rf12mm-$orf12mm; 
$d_16=$rf16mm-$orf16mm;
$d_20=$rf20mm-$orf20mm; 
$d_25=$rf25mm-$orf25mm;
$d_28=$rf28mm-$orf28mm;
$d_32=$rf32mm-$orf32mm;




//Totall Cuutong Production Size wise  in the 
$CT8mm=RollingBD::getInstance()->get_8_cut(filter_input(INPUT_POST,'date'));
$CT10mm=RollingBD::getInstance()->get_10_cut(filter_input(INPUT_POST,'date'));
$CT12mm=RollingBD::getInstance()->get_12_cut(filter_input(INPUT_POST,'date'));
$CT16mm=RollingBD::getInstance()->get_16_cut(filter_input(INPUT_POST,'date'));
$CT20mm=RollingBD::getInstance()->get_20_cut(filter_input(INPUT_POST,'date'));
$CT25mm=RollingBD::getInstance()->get_25_cut(filter_input(INPUT_POST,'date'));
$CT28mm=RollingBD::getInstance()->get_28_cut(filter_input(INPUT_POST,'date'));
$CT32mm=RollingBD::getInstance()->get_32_cut(filter_input(INPUT_POST,'date'));

$Tprodcut=$CT8mm+$CT10mm+$CT12mm+$CT16mm+$CT20mm+$CT25mm+$CT28mm+$CT32mm;
/**
 * updating total size wise production and roughing and cutting production in the 
 */
$k3= mysqli_query($link, "update jd2_rolling_kpi_24hrs"
        . " set `prod_8mm`=`prod_8mm`+$d_8,`prod_10mm`=`prod_10mm`+$d_10,"
        . "`prod_12mm`=`prod_12mm`+$d_12,`prod_16mm`=`prod_16mm`+$d_16,
        `prod_20mm`=`prod_20mm`+$d_20,`prod_25mm`=`prod_25mm`+$d_25,"
        . "`prod_28mm`=`prod_28mm`+$d_28,`prod_32mm`=`prod_32mm`+$d_32,"
        . "`rfmr_8prod`='".$rf8mm."',`rfmr_10prod`='".$rf10mm."',
        `rfmr_12prod`='".$rf12mm."',`rfmr_16prod`='".$rf16mm."',"
        . "`rfmr_20prod`='".$rf20mm."',`rfmr_25prod`='".$rf25mm."',"
        . "`rfmr_28prod`='".$rf28mm."',`rfmr_32prod`='".$rf32mm."',
        `totalrfmrprod`='".$Trfprod."',`cut_8_prod`='".$CT8mm."',"
        . "`cut_10_prod`='".$CT10mm."',`cut_12_prod`='".$CT12mm."',"
        . "`cut_16_prod`='".$CT16mm."',
        `cut_20_prod`='".$CT20mm."',`cut_25_prod`='".$CT25mm."',"
        . "`cut_28_prod`='".$CT28mm."',
       `cut_32_prod`='".$CT32mm."',"
        . "`totalcuttingprod`='".$Tprodcut."'
       where kpi_date='".$bd_date."' ");
  
//fetching total size wise production and net and endcut weight size wise from the kpi table

$k2=mysqli_fetch_row(mysqli_query($link, "select * from jd2_rolling_kpi_24hrs where kpi_date='".$bd_date."'"));
// total production of 8,10,12,16,20,25,28,32 it included roughing production size wise as well.

$f_8mm=$k2[19]; $f_10mm=$k2[20]; $f_12mm=$k2[21];$f_16mm=$k2[22]; $f_20mm=$k2[23]; $f_25mm=$k2[24]; $f_28mm=$k2[25];$f_32mm=$k2[26];

// endcut weight of 8,10,12,16,20,25,28,32  fetch from the kpi table.
$e8mm=$k2[11]/1000;
$e10mm=$k2[12]/1000;
$e12mm=$k2[13]/1000;
$e16mm=$k2[14]/1000;
$e20mm=$k2[15]/1000;
$e25mm=$k2[16]/1000;
$e28mm=$k2[17]/1000;
$e32mm=$k2[18]/1000;
$emrwt=$k2[19]/1000;
// Burning loss Calculation A
$b_8=($f_8mm*1.35)/100; $b_10=($f_10mm*1.25)/100;$b_12=($f_12mm*1.15)/100;$b_16=($f_16mm*.85)/100;$b_20=($f_20mm*.75)/100; $b_25=($f_25mm*.7)/100;
$b_28=($f_28mm*1.2)/100; $b_32=($f_32mm*.55)/100;

$Tblossprod= $b_8+$b_10+$b_12+$b_16+$b_20+$b_25+$b_28+$b_32;

$old_nettmt=$k2[80];
/**
 * total missroll calculation and cutting calculation overall and Dept wise as well.
 */
$tmrfnce=$tmr=$tmrccm=$tmrelec=$tmrmech=$tmrmill=$tmrmpeb=$tmrdep=$tmrindep=$Ncutting=$Nmillcutting=$Nccmcutting=$Nfncecutting=$Nmpebcutting=$TBYPASSCCM=$finalBYPASSCCM=0;

$Tccm_prod=RollingBD::getInstance()->get_ccm_prod(filter_input(INPUT_POST,'date'));
$tmrelec= RollingBD::getInstance()->get_total_mr(filter_input(INPUT_POST,'date'),3);
$tmrmech= RollingBD::getInstance()->get_total_mr(filter_input(INPUT_POST,'date'),4);
$tmrmill= RollingBD::getInstance()->get_total_mr(filter_input(INPUT_POST,'date'),5);
$tmrmpeb= RollingBD::getInstance()->get_total_mr(filter_input(INPUT_POST,'date'),7);
$tmrfnce=RollingBD::getInstance()->get_total_mr(filter_input(INPUT_POST,'date'),2);

$tmrdep=     RollingBD::getInstance()->get_depen_mr(filter_input(INPUT_POST,'date'));
$tmrindep=   RollingBD::getInstance()->get_indepen_mr(filter_input(INPUT_POST,'date'));
$Ncutting=   RollingBD::getInstance()->get_total_cutting(filter_input(INPUT_POST,'date'));
$Nmillcutting= RollingBD::getInstance()->get_total_cutting_mill(filter_input(INPUT_POST,'date'));
$Nccmcutting=RollingBD::getInstance()->get_total_cutting_ccm(filter_input(INPUT_POST,'date'));
$Nfncecutting=RollingBD::getInstance()->get_total_cutting_fnce(filter_input(INPUT_POST,'date'));
$Nmpebcutting=RollingBD::getInstance()->get_total_cutting_mpeb(filter_input(INPUT_POST,'date'));

$tmr=$tmrdep+$tmrindep;
/**
 * CALCULATION TOTAL BYPASS OF CCM , 3RDSTAND OF 3MTR AND 6MTR
 */


$TBYPASSCCM=$TBYPASSCCM3M=$TBYPASSCCM6M=$finalBYPASSCCM=0;

$TBYPASSELEC=$TBYPASSMECH=$TBYPASSMILL=0;$FBYPASSMILL=$TBYPASSFURNACE=$TBYPASSCONT=$TBYPASSMPEB=0;
//TOTAL BYPASS CALCULATION DEPT WISE

$TBYPASSFURNACE=RollingBD::getInstance()->get_billets_bypass(filter_input(INPUT_POST,'date'),1);
$TBYPASSCCM=RollingBD::getInstance()->get_billets_bypass(filter_input(INPUT_POST,'date'),2);
$TBYPASSELEC=RollingBD::getInstance()->get_billets_bypass(filter_input(INPUT_POST,'date'),3);
$TBYPASSMECH=RollingBD::getInstance()->get_billets_bypass(filter_input(INPUT_POST,'date'),4);
$TBYPASSMILL=RollingBD::getInstance()->get_billets_bypass(filter_input(INPUT_POST,'date'),5);
$TBYPASSCONT=RollingBD::getInstance()->get_billets_bypass(filter_input(INPUT_POST,'date'),6);
$TBYPASSMPEB=RollingBD::getInstance()->get_billets_bypass(filter_input(INPUT_POST,'date'),7);
 // TOTAL BYPASS FROM COLD CCM OF 6MTR AND 3MTR.
$TBYPASSCCM3M=RollingBD::getInstance()->get_billets_bypass_3mtr_ccm(filter_input(INPUT_POST,'date'));
$TBYPASSCCM6M=RollingBD::getInstance()->get_billets_bypass_6mtr_ccm(filter_input(INPUT_POST,'date')); 
//SUM OF THE TOTAL BYPASS OF CCM , COLD CCMBYPASS 3M AND 6M.

$finalBYPASSCCM=$TBYPASSCCM+$TBYPASSCCM3M+$TBYPASSCCM6M;



// FINAL BYPASS OF MILL ONLY.
$FBYPASSMILL=$TBYPASSELEC+$TBYPASSMECH+$TBYPASSMILL;


//   PERCENTAGE OF BYPASS PROD OF fURNACE 
$PFURNACEBYPASSPROD=0;
$PFURNACEBYPASSPROD=RollingBD::getInstance()->get_bypass_prod(filter_input(INPUT_POST,'date'),1)/$Tccm_prod;

// TOTAL CCM BYPASS PRODUCTION FROM THE BREAKDOWN TABLE AND IT IS FINALLY DIVIDED BY UPDATED CCM PRODUCTION
$PBYPASSCCM=0;
$PBYPASSCCM=RollingBD::getInstance()->billets_by_pass_prod_due_ccm(filter_input(INPUT_POST,'date'))/$Tccm_prod;

// ELECTRICAL BYPASS PRODUCTION AND PERCENTAGE BYPASS PRODUCTION

$PELECBYPASSPROD=$PMILLBYPASSPROD=$MECHBYPASSPROD=$PMECHBYPASSPROD=$FMILLBYPASSPROD=0;

$PELECBYPASSPROD=RollingBD::getInstance()->get_bypass_prod(filter_input(INPUT_POST,'date'),3)/$Tccm_prod;


// MECHANICAL BYPASS PRODUCTION AND PERCENTAGE OF BYPASS PRODUCTION
$PMILLBYPASSPROD=RollingBD::getInstance()->get_bypass_prod(filter_input(INPUT_POST,'date'),4)/$Tccm_prod;

// MILL BYAPSS PRODUCTION AND PERCENTAGE OF BYPASS PRODUCTION
$PMECHBYPASSPROD=RollingBD::getInstance()->get_bypass_prod(filter_input(INPUT_POST,'date'),5)/$Tccm_prod;

$FMILLBYPASSPROD=$PELECBYPASSPROD+$PMILLBYPASSPROD+$PMECHBYPASSPROD;
//CALCULATING HOT ROLLING AVAILABILITY.
$FROLLINGAVAILABILITY=0;
$FROLLINGAVAILABILITY=100-$FMILLBYPASSPROD;

//   PERCENTAGE OF BYPASS PROD OF CONTRACTOR 
$PCONTBYPASSPROD=0;
$PCONTBYPASSPROD=RollingBD::getInstance()->get_bypass_prod(filter_input(INPUT_POST,'date'),6)/$Tccm_prod;
        

// TOTAL BYPASS PF MPEB AND PERCENTAGE OF BYPASS PROD OF MPEB 
$PMPEBBYPASSPROD=0;
$PMPEBBYPASSPROD=RollingBD::getInstance()->get_bypass_prod(filter_input(INPUT_POST,'date'),7)/$Tccm_prod;

// DOWNTIME OF CCM , ELECTRICAL, MECHANICAL AND MILL AND MPEB
$CCMDOWNTIME=$ELECDOWNTIME=$MECHDOWNTIME=$MILLDOWNTIME=$MPEBDOWNTIME=$CONTDOWNTIME=$FURNACEDOWNTIME=0;

$CCMDOWNTIME= RollingBD::getInstance()->get_prod_down_time_dept_min((filter_input(INPUT_POST,'date')),2);
$ELECDOWNTIME= RollingBD::getInstance()->get_prod_down_time_dept_min((filter_input(INPUT_POST,'date')),3);
$MECHDOWNTIME= RollingBD::getInstance()->get_prod_down_time_dept_min((filter_input(INPUT_POST,'date')),4);
$MILLDOWNTIME=RollingBD::getInstance()->get_prod_down_time_dept_min((filter_input(INPUT_POST,'date')),5);
$CONTDOWNTIME=RollingBD::getInstance()->get_prod_down_time_dept_min((filter_input(INPUT_POST,'date')),6);
$MPEBDOWNTIME= RollingBD::getInstance()->get_prod_down_time_dept_min((filter_input(INPUT_POST,'date')),7);
$FURNACEDOWNTIME= RollingBD::getInstance()->get_prod_down_time_dept_min((filter_input(INPUT_POST,'date')),1);

// TOTAL CCM PIECES OF 3MTR ,MTR AND 3RDSTAND AND TOTAL MISSROLL.
$BP3MTR=$BP6MTR=$BP3ST3M=$BP3ST6M=$BPP=$TCCMPIECS=0;
$BP3MTR= RollingBD::getInstance()->get_billets_bypass_3mtr_ccm(filter_input(INPUT_POST,'date'));
$BP6MTR= RollingBD::getInstance()->get_billets_bypass_6mtr_ccm(filter_input(INPUT_POST,'date'));
$BP3ST3M=RollingBD::getInstance()->get_3rdstand_bypass_3mtr(filter_input(INPUT_POST,'date'));
$BP3ST6M=RollingBD::getInstance()->get_3rdstand_bypass_6mtr(filter_input(INPUT_POST,'date'));
$BPP=RollingBD::getInstance()->get_total_billets_bypass(filter_input(INPUT_POST,'date'));

$TCCMPIECS=$BP3MTR+2*$BP6MTR+$BP3ST3M+$BP3ST6M+$tmr+$BPP;


//TOTAL DOWN TIME IN HR DEPARTMENT WISE. in hr 
$DTCCMHR=$DTELECHR=$DTMECHHR=$DTMILLHR=$DTMPEBHR=$DTFURNACEHR=$DTCONTHR=0;
$DTFURNACEHR=RollingBD::getInstance()->get_prod_down_time_dept_hr((filter_input(INPUT_POST,'date')),1);
$DTCCMHR=RollingBD::getInstance()->get_prod_down_time_dept_hr((filter_input(INPUT_POST,'date')),2);
$DTELECHR=RollingBD::getInstance()->get_prod_down_time_dept_hr((filter_input(INPUT_POST,'date')),3);
$DTMECHHR=RollingBD::getInstance()->get_prod_down_time_dept_hr((filter_input(INPUT_POST,'date')),4);
$DTMILLHR=RollingBD::getInstance()->get_prod_down_time_dept_hr((filter_input(INPUT_POST,'date')),5);
$DTCONTHR=RollingBD::getInstance()->get_prod_down_time_dept_hr((filter_input(INPUT_POST,'date')),6);
$DTMPEBHR=RollingBD::getInstance()->get_prod_down_time_dept_hr((filter_input(INPUT_POST,'date')),7);


// TOTAL DOWNTIME AND PRODUCTION  AND PERCENTAGE OF PRODUCTION AND TOTAL BYPASS DUE TO REASON CODE.

$DOWNTIMEPC=$DOWNTIMEPCHR=$TBPRODPC=$PBPPRODP=$TBBPC=0;
//PASS CHANGE
$DOWNTIMEPC= RollingBD::getInstance()->get_prod_down_time_reason_min(filter_input(INPUT_POST,'date'),37);
$DOWNTIMEPCHR=RollingBD::getInstance()->get_prod_down_time_reason_hr(filter_input(INPUT_POST,'date'),37);

$TBPRODPC=RollingBD::getInstance()->get_bypass_prod_reason(filter_input(INPUT_POST,'date'),37);
$PBPPRODPC=($TBPRODPC/$Tccm_prod)*100;
$TBBPC=RollingBD::getInstance()->get_billets_bypass_reason(filter_input(INPUT_POST,'date'),37);

//SIZE CHANGE
$DOWNTIMESC=$DOWNTIMESCHR=$TBBSC=$TBPRODSC=$PBPPRODSC=0;
$DOWNTIMESC= RollingBD::getInstance()->get_prod_down_time_reason_min(filter_input(INPUT_POST,'date'),52);
$DOWNTIMESCHR=RollingBD::getInstance()->get_prod_down_time_reason_hr(filter_input(INPUT_POST,'date'),52);
$TBBSC=RollingBD::getInstance()->get_billets_bypass_reason(filter_input(INPUT_POST,'date'),52);
$TBPRODSC=RollingBD::getInstance()->get_bypass_prod_reason(filter_input(INPUT_POST,'date'),52);
$PBPPRODSC=($TBPRODSC/$Tccm_prod)*100;
//ROLL CHANGE
$DOWNTIMERC=$DOWNTIMERCHR=$TBPRODRC=$PBPPRODRC=$TBBRC=0;
$DOWNTIMERC= RollingBD::getInstance()->get_prod_down_time_reason_min(filter_input(INPUT_POST,'date'),47);
$DOWNTIMERCHR=RollingBD::getInstance()->get_prod_down_time_reason_hr(filter_input(INPUT_POST,'date'),47);
$TBPRODRC=RollingBD::getInstance()->get_bypass_prod_reason(filter_input(INPUT_POST,'date'),47);
$PBPPRODRC=($TBPRODRC/$Tccm_prod)*100;
$TBBRC=RollingBD::getInstance()->get_billets_bypass_reason(filter_input(INPUT_POST,'date'),47);
//MAINTENANCE
$DOWNTIMEMENT=$DOWNTIMEMENTHR=$TBPRODMENT=$PBPPRODMENT=$TBBMENT=0;
$DOWNTIMEMENT= RollingBD::getInstance()->get_prod_down_time_reason_min(filter_input(INPUT_POST,'date'),30);
$DOWNTIMEMENTHR=RollingBD::getInstance()->get_prod_down_time_reason_hr(filter_input(INPUT_POST,'date'),30);
$TBPRODMENT=RollingBD::getInstance()->get_bypass_prod_reason(filter_input(INPUT_POST,'date'),30);
$PBPPRODMENT=($TBPRODMENT/$Tccm_prod)*100;
$TBBMENT=RollingBD::getInstance()->get_billets_bypass_reason(filter_input(INPUT_POST,'date'),30);
//LAHAR
$DOWNTIMELR=$DOWNTIMELRHR=$TBBLR=$TBPRODLR=$PBPPRODLR=0;
$DOWNTIMELR= RollingBD::getInstance()->get_prod_down_time_reason_min(filter_input(INPUT_POST,'date'),26);
$DOWNTIMELRHR=RollingBD::getInstance()->get_prod_down_time_reason_hr(filter_input(INPUT_POST,'date'),26);
$TBBLR=RollingBD::getInstance()->get_billets_bypass_reason(filter_input(INPUT_POST,'date'),26);
$TBPRODLR=RollingBD::getInstance()->get_bypass_prod_reason(filter_input(INPUT_POST,'date'),26);
$PBPPRODLR=($TBPRODSC/$Tccm_prod)*100;
// BILLET CRACK
$DOWNTIMECK=$DOWNTIMECKHR=$TBPRODCK=$PBPPRODCK=$TBBCK=0;
$DOWNTIMECK= RollingBD::getInstance()->get_prod_down_time_reason_min(filter_input(INPUT_POST,'date'),4);
$DOWNTIMECKHR=RollingBD::getInstance()->get_prod_down_time_reason_hr(filter_input(INPUT_POST,'date'),4);
$TBPRODCK=RollingBD::getInstance()->get_bypass_prod_reason(filter_input(INPUT_POST,'date'),4);
$PBPPRODCK=($TBPRODCK/$Tccm_prod)*100;
$TBBCK=RollingBD::getInstance()->get_billets_bypass_reason(filter_input(INPUT_POST,'date'),4);


//PIPING
$DOWNTIMEPP=$DOWNTIMEPPHR=$TBPRODPP=$PBPPRODPP=$TBBCK=0;
$DOWNTIMEPP= RollingBD::getInstance()->get_prod_down_time_reason_min(filter_input(INPUT_POST,'date'),41);
$DOWNTIMEPPHR=RollingBD::getInstance()->get_prod_down_time_reason_hr(filter_input(INPUT_POST,'date'),41);
$TBPRODPP=RollingBD::getInstance()->get_bypass_prod_reason(filter_input(INPUT_POST,'date'),41);
$PBPPRODPP=($TBPRODPP/$Tccm_prod)*100;
$TBBPP=RollingBD::getInstance()->get_billets_bypass_reason(filter_input(INPUT_POST,'date'),41);

//MOUTH OPEN
$DOWNTIMEMO=$DOWNTIMEMOHR=$TBPRODMO=$PBPPRODMO=$TBBMO=0;
$DOWNTIMEMO= RollingBD::getInstance()->get_prod_down_time_reason_min(filter_input(INPUT_POST,'date'),33);
$DOWNTIMEMOHR=RollingBD::getInstance()->get_prod_down_time_reason_hr(filter_input(INPUT_POST,'date'),33);
$TBPRODMO=RollingBD::getInstance()->get_bypass_prod_reason(filter_input(INPUT_POST,'date'),33);
$PBPPRODMO=($TBPRODMO/$Tccm_prod)*100;
$TBBMO=RollingBD::getInstance()->get_billets_bypass_reason(filter_input(INPUT_POST,'date'),33);

//MILLCHILLI
$DOWNTIMEMC=$DOWNTIMEMCHR=$TBPRODMC=$PBPPRODMC=$TBBMC=0;
$DOWNTIMEMC= RollingBD::getInstance()->get_prod_down_time_reason_min(filter_input(INPUT_POST,'date'),62);
$DOWNTIMEMCHR=RollingBD::getInstance()->get_prod_down_time_reason_hr(filter_input(INPUT_POST,'date'),62);
$TBPRODMC=RollingBD::getInstance()->get_bypass_prod_reason(filter_input(INPUT_POST,'date'),62);
$PBPPRODMC=($TBPRODMC/$Tccm_prod)*100;
$TBBMC=RollingBD::getInstance()->get_billets_bypass_reason(filter_input(INPUT_POST,'date'),62);

//QUALITY CHILLI
$DOWNTIMEQC=$DOWNTIMEQCHR=$TBPRODQC=$PBPPRODQC=$TBBQC=0;
$DOWNTIMEQC= RollingBD::getInstance()->get_prod_down_time_reason_min(filter_input(INPUT_POST,'date'),10);
$DOWNTIMEQCHR=RollingBD::getInstance()->get_prod_down_time_reason_hr(filter_input(INPUT_POST,'date'),10);
$TBPRODQC=RollingBD::getInstance()->get_bypass_prod_reason(filter_input(INPUT_POST,'date'),10);
$PBPPRODQC=($TBPRODQC/$Tccm_prod)*100;
$TBBQC=RollingBD::getInstance()->get_billets_bypass_reason(filter_input(INPUT_POST,'date'),10);

// UPDATING VALUES IN THE KPI TABLE.
$n_8mm=$n_10mm=$n_12mm=$n_16mm=$n_20mm=$n_25mm=$n_28mm=$n_32mm=0;

$n_8mm=$f_8mm-$T8mm-$CT8mm-$b_8-$e8mm;
$n_10mm=$f_10mm-$T10mm-$CT10mm-$b_10-$e10mm;
$n_12mm=$f_12mm-$T12mm-$CT12mm-$b_12-$e12mm;
$n_16mm=$f_16mm-$T16mm-$CT16mm-$b_16-$e16mm;
$n_20mm=$f_20mm-$T20mm-$CT20mm-$b_20-$e20mm;
$n_25mm=$f_25mm-$T25mm-$CT25mm-$b_25-$e25mm;
$n_28mm=$f_28mm-$T28mm-$CT28mm-$b_28-$e28mm;
$n_32mm=$f_32mm-$T32mm-$CT32mm-$b_32-$e32mm;

// Mechanical  Availability
$mechanical_availability= ((1440-$MECHDOWNTIME)/1440)*100;
//Electrical Availability
$electrical_availability=((1440-$ELECDOWNTIME)/1440)*100;

$k4 = mysqli_query($link, "update jd2_rolling_kpi_24hrs  set total_missroll='" . $tmr . "',"
                    . "`total_mill_missroll`='" . $tmrmill . "',`total_elec_missroll`='" . $tmrelec . "',
        `total_mech_missroll`='" . $tmrmech . "',`total_furnace_missroll`='" . $tmrfnce . "',"
                    . "`total_mpeb_missroll`='" . $tmrmpeb . "',`total_indep_missroll`='" . $tmrdep . "',`total_dep_missroll`='" . $tmrindep . "',
        `total_ccm_missroll`='" . $tmrccm . "',`total_cutting`='" . $Ncutting . "',"
                    . "`total_mill_cutting`='" . $Nmillcutting . "',`total_ccm_cutting`='" . $Nccmcutting . "',
        `total_furnace_cutting`='" . $Nfncecutting . "',`total_mpeb_cutting`='" . $Nmpebcutting . "',"
                    . "`total_billets_bypass_ccm_only`='" . $finalBYPASSCCM . "',`total_bypass_mill`='" . $TBYPASSMILL . "',
        `total_bypass_elec`='" . $TBYPASSELEC . "',`total_bypass_mech`='" . $TBYPASSMECH . "',"
                    . "`total_ccm_prod`='" . $Tccm_prod . "',
        `percent_billets_bypass_prod_ccm`='" . $PBYPASSCCM . "',`percent_billets_bypass_prod_mill`='" . $PMILLBYPASSPROD . "',"
                    . "`billets_bypass_prod_mech_percent`='" . $PMECHBYPASSPROD . "',"
                    . "`billets_bypass_prod_elec_percent`='" . $PELECBYPASSPROD . "',
         `total_billets_bypass_mill_only`='" . $FBYPASSMILL . "',`total_bypass_mpeb`='" . $TBYPASSMPEB . "',"
                    . "`billets_bypass_prod_mpeb_percent`='" . $PMPEBBYPASSPROD . "',"
                    . "`prod_downtime_mill_min`='" . $MILLDOWNTIME . "',
         `prod_downtime_mill_hr`='" . $DTMILLHR . "',`prod_downtime_mech_min`='" . $MECHDOWNTIME . "',"
                    . "`prod_downtime_mech_hr`='" . $DTMECHHR . "',`prod_downtime_elec_min`='" . $ELECDOWNTIME . "',
        `prod_downtime_elec_hr`='" . $DTELECHR . "',`prod_downtime_mpeb_min` ='" . $MPEBDOWNTIME . "',"
                    . "`prod_downtime_mpeb_hr`='" . $DTMPEBHR . "',`total_ccm_pieces`=(total_rolling_pieces+$TCCMPIECS),
         `bloss_8mm_prod`='" . $b_8 . "',`bloss_10mm_prod`='" . $b_10 . "',`bloss_12mm_prod`='" . $b_12 . "',`bloss_16mm_prod`='" . $b_16 . "',`bloss_20mm_prod`='" . $b_20 . "',
         `bloss_25mm_prod`='" . $b_25 . "',`bloss_28mm_prod`='" . $b_28 . "',`bloss_32mm_prod`='" . $b_32 . "',`total_bloss_prod`='" . $Tblossprod . "',
         `24hrshotrolling`=((`total_rolling_pieces`)/(`total_rolling_pieces`+$TCCMPIECS))*100 ,
         `net_8mm_prod`=$n_8mm,`net_10mm_prod`=$n_10mm,`net_12mm_prod`=$n_12mm,"
                    . "`net_16mm_prod`=$n_16mm,`net_20mm_prod`=$n_20mm,`net_25mm_prod`=$n_25mm,`net_28mm_prod`=$n_28mm,`net_32mm_prod`=$n_32mm,
         `monthlyhotrolling`=((`gross_tmt`)/($Tccm_prod))*100,
         `net_tmt`=($n_8mm+$n_10mm+$n_12mm+$n_16mm+$n_20mm+$n_25mm+$n_28mm+$n_32mm-$emrwt),
         `prod_downtime_passchange_min`='" . $DOWNTIMEPC . "', 
                        `prod_downtime_passchange_hr`='" . $DOWNTIMEPCHR . "', `total_bypass_passchange`='" . $TBBPC . "', 
         `billets_bypass_prod_passchange_percent`='" . $PBPPRODPC . "', 
                  `prod_downtime_size_change_min`='" . $DOWNTIMESC . "', `prod_downtime_size_change_hr`='" . $DOWNTIMESCHR . "', 
                  `total_bypass_size_change`='" . $TBBSC . "',
        `billets_bypass_prod_size_change_percent`='" . $PBPPRODSC . "', 
                    `prod_downtime_maintenance_min`='" . $DOWNTIMEMENT . "', 
                    `prod_downtime_maintenance_hr`='" . $DOWNTIMEMENTHR . "',
                   `total_bypass_maintenance`='" . $TBBMENT . "',
        `billets_bypass_prod_maintenance_percent`='" . $PBPPRODMENT . "',
                   `prod_downtime_lahar_min`='" . $DOWNTIMELR . "',
                   `prod_downtime_lahar_hr`='" . $DOWNTIMELRHR . "', 
        `total_bypass_lahar`='" . $TBBLR . "', 
                    `billets_bypass_prod_lahar_percent`='" . $PBPPRODLR . "', 
                    `prod_downtime_crack_min`='" . $DOWNTIMECK . "', `prod_downtime_crack_hr`='" . $DOWNTIMECKHR . "',
        `total_bypass_crack`='" . $TBBCK . "', 
                    `billets_bypass_prod_crack_percent`='" . $PBPPRODCK . "', 
                    `prod_downtime_piping_min`='" . $DOWNTIMEPP . "', 
        `prod_downtime_piping_hr`='" . $DOWNTIMEPPHR . "', 
                    `total_bypass_piping`='" . $TBBPP . "', 
                    `billets_bypass_prod_piping_percent`='" . $PBPPRODP . "', 
        `prod_downtime_chill_min`='" . $DOWNTIMEMC . "', 
                    `prod_downtime_chilli_hr`='" . $DOWNTIMEMCHR . "', 
                    `total_bypass_chilli`='" . $TBBMC . "', `billets_bypass_prod_chilli_percent`='" . $PBPPRODMC . "',
        `prod_downtime_ccd_composition_min`='" . $DOWNTIMEQC . "', `prod_downtime_ccd_composition_hr`='" . $DOWNTIMEQCHR . "', 
                    `total_bypass_ccd_composition`='" . $TBBQC . "', `billets_bypass_prod_ccd_composition_percent`='" . $PBPPRODQC . "',
        `prod_downtime_mouth_open_min`='" . $DOWNTIMEMO . "',`prod_downtime_mouth_open_hr`='" . $DOWNTIMEMOHR . "',
                    `total_bypass_mouthopen`='" . $TBBMO . "',`hotrolling_availability`='" . $FROLLINGAVAILABILITY . "',
         `prod_downtime_ccm_min`='" . $CCMDOWNTIME . "',`prod_downtime_ccm_hr`='" . $DTCCMHR . "' ,
                    `prod_downtime_furnace_min`='" . $FURNACEDOWNTIME . "',`prod_downtime_furnace_hr`='" . $DTFURNACEHR . "',
        `total_bypass_furnce`='" . $TBYPASSFURNACE . "',`total_bypass_contractor`='" . $TBYPASSCONT . "',
         `billets_bypass_prod_furnace_percent`='" . $PFURNACEBYPASSPROD . "',`billets_bypass_contractor_percent`='" . $PCONTBYPASSPROD . "',
         `prod_downtime_contractor_min`='" . $CONTDOWNTIME . "',`prod_downtime_contractor_min`='" . $DTCONTHR . "',
         `total_furnace_missroll`='" . $tmrfnce . "',`mechanical_availability`='".$mechanical_availability."',
            `electrical_availability`='".$electrical_availability."'
        where `kpi_date`='" . $bd_date . "'");

            if (!$k4) {
                echo mysqli_error($link);
            }
            $f_date = date('Y-m-01', strtotime($bd_id)); // hard-coded '01' for first day
            $l_date = date('Y-m-t', strtotime($bd_date));
// FETCHING THE UPDATED DETAILS FROM THE ROLLING KPI TABLE.
            $k5 = mysqli_fetch_row(mysqli_query($link, "select * from jd2_rolling_kpi_24hrs where kpi_date='" . $bd_date . "'"));
            $new_nettmt = $k5[80];
            $endcutmisrollwt = ($k5[19] / 1000);

            $dif_nettmt = $new_nettmt - $old_nettmt;

// UPDATING THE MONTHLY NETTTMT IN THE KPI TABLE ON THE BASIS OF THE START AND END DATE
            $k6 = mysqli_query($link, "update jd2_rolling_kpi_24hrs set monthly_net_tmt=$dif_nettmt+monthly_net_tmt where kpi_date > = '" . $bd_date . "' and kpi_date<='" . $l_date . "' ");

            $TOTALMRPROD = $TPERMRPROD = $TOTALMILLMRPROD = $TPERMILLMRPROD = $TPERMILLCCMPROD = $PERCUTTINGPROD = $PERMILLCUTTINGPROD = $PERCCMCUTTINGPROD = $PERMPEBCUTTINGPROD = $PERFNCECUTTINGPROD = $perendcutwt = $permillccmprod = 0;
            $finalperendcut = 0;

// CALCULATING THE TOTAL MISSROLL PERCENTAGE
            $TOTALMRPROD = RollingBD::getInstance()->get_mr_prod(filter_input(INPUT_POST, 'date'));
            $TPERMRPROD = ($TOTALMRPROD / $new_nettmt) * 100;


// CALCULATIONG TOTAL MILL MISSROLL PERCENTAGE
            $TOTALMILLMRPROD = RollingBD::getInstance()->get_mr_prod_mill(filter_input(INPUT_POST, 'date'));
//PERMILL MISSROLL PERCENTAGE DIVIDED BY NET TMT
            $TPERMILLMRPROD=($TOTALMILLMRPROD/$new_nettmt)*100;
// PERMILL MISSROLL PERCEBNTAGE DIVIDED BY CCM PROD.
$TPERMILLCCMPROD=($TOTALMILLMRPROD/$Tccm_prod)*100;

//TOTAL PERCENTAGE OF CUTTING PRODUCTION DIVIDED BY UPDATED NET TMT
$PERCUTTINGPROD=($Ncutting*.100)/$new_nettmt;
// TOTAL PERCENTAGE OF MILL CUTTING PRODUCTION
$PERMILLCUTTINGPROD=($Nmillcutting*.100)/$new_nettmt;
// TOTAL PER OF CCM CUTTING PRODUCTION
$PERCCMCUTTINGPROD=($Nccmcutting*.100)/$new_nettmt;
// TOTAL PER OF FURNACE CUTTING PRODUCTION
$PERFNCECUTTINGPROD=($Nfncecutting*.100)/$new_nettmt;
// TOTAL PER OF MPEB CUTTING PRODUCTION 
$PERMPEBCUTTINGPROD=($Nmpebcutting*100)/$new_nettmt;

//TOTAL PERCNETAGE OF  ENDCUT MISSROLL WT PRODUCTION
$perendcutwt=($endcutmisrollwt/$new_nettmt)*100;
// FINAL PERCENTAGE OF ENDCUT 
$finalperendcut=$TPERMRPROD+$perendcutwt+$PERCUTTINGPROD;
// FINAL PERENATGE OF MILL AND CCM PRODUCTION 
$permillccmprod=($TOTALMILLMRPROD/$Tccm_prod)*100;

//updating cutting percentage and missroll percentage , ccm percentage , furnace , mpeb , missroll percenatge  and endcut missroll percentage
$k6=mysqli_query($link,"update jd2_rolling_kpi_24hrs set `total_cutting_perc`='".$PERCUTTINGPROD."',`total_cutting_mill_perc`='".$PERMILLCUTTINGPROD."',
        `total_cutting_ccm_perc`='".$PERCCMCUTTINGPROD."',`total_furnace_cutting_perc`='".$PERFNCECUTTINGPROD."',`total_mpeb_cutting_perc`='".$PERMPEBCUTTINGPROD."', 
        `missroll_percent`='".$TPERMRPROD."',`mill_missroll_percent`='".$TPERMILLMRPROD."',`endcut_missroll_perc`='".$perendcutwt."',`endcut_missroll_perc`='".$finalperendcut."',
        `mill_missroll_from_ccm_percent`='".$permillccmprod."' WHERE `kpi_date`='".$bd_date."'");
}
}


 Slack::getInstance()->postMessagesToSlack("Breakdown Updated
  Heat-Number- $hnumber
  BD-Date- $bd_date;    
  " ,"Test"
           );
header("Location: http://dataapp.moira.local/Rolling_Jd2/Home.php");
}




else{
  // QUERY TO INSERT VALUES IN TO THE BREAKDOWN TABLE 
 $sql = "INSERT INTO `jd2_rolling_breakdown`(`bd_date`, `heat_number`, `mill_1_size`, `mill_2_size`, `shift`, `bd_start_time`, 
     `bd_end_time`, `bd_total_time`, `stand`, `dep_missroll`, `indep_missroll`, `total_missroll`, `total_cutting`, `total_3mtr_billets_bypass`, 
     `total_6mtr_billets_bypass`, `total_billets_bypass`, `avg_3mtr_wt`, `avg_6mtr_wt`, `resp_per_id`, `location_id`, `dept_id`, `shift_foreman_id`, `bd_detail`,
     `reason_id`, `missroll_prod`, `3mtr_billets_bypass_prod`, `6mtr_billetsbypass_prod`, `total_billets_bypass_prod`, `total_cutting_wt`,`bd_total_time_minutes`)
     
VALUES 
     
('" . $bd_date . "','" . $hnumber . "','" . $mill_1_size . "',"
 . "'" . $mill_2_size . "', '" . $shift . "','" . $bd_start_time . "','" . $bd_end_time . "','" . $total_time_new . "',"
 . " '" . $stand . "', '" . $dependent_mr . "', "
 . "'" . $independent_mr . "', '" . $total_mr . "', '" . $cutting . "',"
 . " '" . $bp_3mtr . "', '" . $bp_6mtr . "', '" . $total . "',"
 . "'" . $avg_3mtr . "', '" . $avg_6mtr . "' ,"
 . "'" . $responsible_person . "','" . $location_code . "', "
 . "'" . $department . "', '" . $shift_formen . "','" . $f_bd_action . "',"
 . "'" . $reasonid . "','" . $mr_production ."', '" .$three_mtr_billets_bp_production . "', "
 . "'".$six_mtr_billets_bp_production."', '".$total_bbp_production."','".$total_cutting_wt."','".$totaltimeinmin."')";





$test = (mysqli_query($link, $sql) or die(mysqli_error($link)));

if (!$test) {
    echo "not added";
    echo mysqli_error($link);
    //echo "$hn";
} else {
    echo "Records added";
   
    if($dependent_mr==""){
        $dependent_mr=0;
    }
    else{
        $dependent_mr=(filter_input(INPUT_POST,'dependent_mr'));
    }
    if($independent_mr==""){
        $independent_mr=0;
    }
    else{
        $independent_mr=(filter_input(INPUT_POST,'independent_mr'));
    }
   
 if( $bp_3mtr==""){
         $bp_3mtr=0;
    }
    else{
         $bp_3mtr=(filter_input(INPUT_POST,'bp_3mtr'));
    }
    if( $bp_6mtr==""){
         $bp_6mtr=0;
    }
    else{
         $bp_6mtr=(filter_input(INPUT_POST,'bp_6mtr'));
    }
}

    // SEND MESSAGE TO SLACK IN ROLLING CHANNEL
 Slack::getInstance()->postMessagesToSlack("*Date-* *$date*
    *HN-* *`$hnumber`* *M1S-* *`$m1size`* *M2S-* *`$m2size`*
    *BD Start-* *`$start_time`* *BD End-* *`$end_time`* *Net-* *`$total_time_new`*
    *DEP-* *`$dependent_mr`*  *INDEP-* *`$independent_mr`* *TMR-* *`$total_mr`*
    *BP3-* *`$bp_3mtr`* *BP6-* *`$bp_6mtr`* *TBP-* *`$total`*
     *`$loc_id`*
     *`$rea_id`*
     *`$dpt_id`*
     *`$per_id`*
     
    "
    ,"Rolling_Jd2"
            );

}

//print 'alert("Record Added successfully..")';

mysqli_close($link);
header("Location: http://dataapp.moira.local/Rolling_Jd2/Home.php");
exit();
?>

