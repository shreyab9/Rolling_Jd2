<?php
/**THIS FORM IS ALL ABOUT 24 HOURS CONSOLIDATED PRODUCTION REPORT
 * 
 *Added PertonWaterConsumption of MILL and TMT
 * Water Consumption of MILL and TMT 
 * Created Seaparted message for the Dispatch team and Message is send to the JD2-prod -msg channel 
 * 
 */
require_once("..\Connection.php");
//
require_once("..\DBfile.php");
// TO SEND MESAGE TO THE SLACK CHANNEL
require_once("..\postMessagesToSlack.php");

function lz($num) {
    return (strlen($num) < 2) ? "0{$num}" : $num;
}
//$heatcount=$hotrolling24hr=$rollingprod=$Tccmproduction=$NETTMT=0;
//Perton Power Consumption
$pertonpower=filter_input(INPUT_POST,'pertonpower');
$pertonwatermill=filter_input(INPUT_POST,'pertonwatermill');
$pertonwatertmt=filter_input(INPUT_POST,'pertonwatertmt');
$endcut8wt=filter_input(INPUT_POST,'endcut8mm');
$endcut10wt=filter_input(INPUT_POST,'endcut10mm');
$endcut12wt=filter_input(INPUT_POST,'endcut12mm');
$endcut16wt=filter_input(INPUT_POST,'endcut16mm');
$endcut20wt=filter_input(INPUT_POST,'endcut20mm');
$endcut25wt=filter_input(INPUT_POST,'endcut25mm');
$endcut28wt=filter_input(INPUT_POST,'endcut28mm');
$endcut32wt=filter_input(INPUT_POST,'endcut32mm');
$endcutmrwt=filter_input(INPUT_POST,'endcutmrwt');

$bloss8mmpercent=filter_input(INPUT_POST,'bloss8mm');
$bloss10mmpercent=filter_input(INPUT_POST,'bloss10mm');
$bloss12mmpercent=filter_input(INPUT_POST,'bloss12mm');
$bloss16mmpercent=filter_input(INPUT_POST,'bloss16mm');
$bloss20mmpercent=filter_input(INPUT_POST,'bloss20mm');
$bloss25mmpercent=filter_input(INPUT_POST,'bloss25mm');
$bloss28mmpercent=filter_input(INPUT_POST,'bloss28mm');
$bloss32mmpercent=filter_input(INPUT_POST,'bloss32mm');


$isigradewisetmt=filter_input(INPUT_POST,'isigradewise');
$isiweightwisetmt=filter_input(INPUT_POST,'isiweightwise');


$coolingbedendcut=filter_input(INPUT_POST,'coolingbedendcut');

$millendcut=filter_input(INPUT_POST,'millendcut');


$TEndcut= (($endcut8wt+$endcut10wt+$endcut12wt+$endcut16wt+$endcut20wt+$endcut25wt+$endcut28wt+$endcut32wt)/1000);

//total heatcount of the day
$heatcount = RollingBD::getInstance()->get_heat_count(filter_input(INPUT_POST,'kpidate'));
echo 'heatcount:'; echo $heatcount;

//gross tmt production
$TROLLINGPROD = RollingBD::getInstance()->get_rolling_prod(filter_input(INPUT_POST,'kpidate'));
//echo 'grosstmt:'; echo $TROLLINGPROD;

//total ccm productiom
$Tccmproduction = RollingBD::getInstance()->get_ccm_prod(filter_input(INPUT_POST,'kpidate'));
//echo 'ccmprod:'; echo $Tccmproduction;

//total heat running time 
$HEATRUNTIME = RollingBD::getInstance()->get_heat_running_time(filter_input(INPUT_POST,'kpidate'));



//echo 'heat runtime:'; echo $HEATRUNTIME;


$date_1 = strtr($_REQUEST['kpidate'], '/', '-');
//echo $date_ph;
$pres_mon_value= date('m', strtotime($date_1));
//echo "monthvalue :"; echo $pres_mon_value;

$kpidate=date('Y-m-d', strtotime($date_1));
//echo "kpidate:"; echo $kpidate;



$cut8=RollingBD::getInstance()->get_8_cut(filter_input(INPUT_POST,'kpidate'));
$cut10=RollingBD::getInstance()->get_10_cut(filter_input(INPUT_POST,'kpidate'));
$cut12=RollingBD::getInstance()->get_12_cut(filter_input(INPUT_POST,'kpidate'));
$cut16=RollingBD::getInstance()->get_16_cut(filter_input(INPUT_POST,'kpidate'));
$cut20=RollingBD::getInstance()->get_20_cut(filter_input(INPUT_POST,'kpidate'));
$cut25=RollingBD::getInstance()->get_25_cut(filter_input(INPUT_POST,'kpidate'));
$cut28=RollingBD::getInstance()->get_28_cut(filter_input(INPUT_POST,'kpidate'));
$cut32=RollingBD::getInstance()->get_32_cut(filter_input(INPUT_POST,'kpidate'));
$Tcuttingprod=$cut8+$cut10+$cut12+$cut16+$cut12+$cut16+$cut20+$cut25+$cut32;
  
//Roughing Production for the Loation 14"rf, 16"rf, 16"dc, 18"rf, center line.


$rf_l8mm = RollingBD::getInstance()->get_8mm_rfside(filter_input(INPUT_POST,'kpidate'));
$rf_l10mm = RollingBD::getInstance()->get_10mm_rfside(filter_input(INPUT_POST,'kpidate'));
$rf_l12mm = RollingBD::getInstance()->get_12mm_rfside(filter_input(INPUT_POST,'kpidate'));
$rf_l16mm = RollingBD::getInstance()->get_16mm_rfside(filter_input(INPUT_POST,'kpidate'));
$rf_l20mm = RollingBD::getInstance()->get_20mm_rfside(filter_input(INPUT_POST,'kpidate'));
$rf_l25mm = RollingBD::getInstance()->get_25mm_rfside(filter_input(INPUT_POST,'kpidate'));
$rf_l28mm = RollingBD::getInstance()->get_28mm_rfside(filter_input(INPUT_POST,'kpidate'));
$rf_l32mm = RollingBD::getInstance()->get_32mm_rfside(filter_input(INPUT_POST,'kpidate'));

$trf_lprod=$rf_l8mm+$rf_l10mm+$rf_l12mm+$rf_l16mm+$rf_l20mm+$rf_l25mm+$rf_l28mm+$rf_l32mm;
//roughing Production calculated size wise and overall

$rfmr8=RollingBD::getInstance()->get_8_rfmr(filter_input(INPUT_POST,'kpidate'));
$rfmr10=RollingBD::getInstance()->get_10_rfmr(filter_input(INPUT_POST,'kpidate'));
$rfmr12=RollingBD::getInstance()->get_12_rfmr(filter_input(INPUT_POST,'kpidate'));
$rfmr16=RollingBD::getInstance()->get_16_rfmr(filter_input(INPUT_POST,'kpidate'));
$rfmr20=RollingBD::getInstance()->get_20_rfmr(filter_input(INPUT_POST,'kpidate'));
$rfmr25=RollingBD::getInstance()->get_25_rfmr(filter_input(INPUT_POST,'kpidate'));
$rfmr28=RollingBD::getInstance()->get_28_rfmr(filter_input(INPUT_POST,'kpidate'));
$rfmr32=RollingBD::getInstance()->get_32_rfmr(filter_input(INPUT_POST,'kpidate'));
$Trfmrprod=$rfmr8+$rfmr10+$rfmr12+$rfmr16+$rfmr20+$rfmr25+$rfmr28+$rfmr32;

  //Burning Loss Calculation  OF SIZE WISE 
 
  $T8mmprod=RollingBD::getInstance()->get_bl_8mm(filter_input(INPUT_POST,'kpidate'))+$rfmr8+$rf_l8mm;
  $BL8mm= ($T8mmprod*$bloss8mmpercent)/100;
  
  $T10mmprod=RollingBD::getInstance()->get_bl_10mm(filter_input(INPUT_POST,'kpidate'))+$rfmr10+$rf_l10mm;
  $BL10mm= ($T10mmprod*$bloss10mmpercent)/100; 
  
  $T12mmprod=RollingBD::getInstance()->get_bl_12mm(filter_input(INPUT_POST,'kpidate'))+$rfmr12+$rf_l12mm;
  $BL12mm= ($T12mmprod*$bloss12mmpercent)/100;
  
  $T16mmprod=RollingBD::getInstance()->get_bl_16mm(filter_input(INPUT_POST,'kpidate'))+$rfmr16+$rf_l16mm;
  $BL16mm= ($T16mmprod*$bloss16mmpercent)/100;
  
  $T20mmprod=RollingBD::getInstance()->get_bl_20mm(filter_input(INPUT_POST,'kpidate'))+$rfmr20+$rf_l20mm;
  $BL20mm= ($T20mmprod*$bloss20mmpercent)/100;
    
  $T25mmprod=RollingBD::getInstance()->get_bl_25mm(filter_input(INPUT_POST,'kpidate'))+$rfmr25+$rf_l25mm;
  $BL25mm= (($T25mmprod)*($bloss25mmpercent))/100;
  
  $T28mmprod=RollingBD::getInstance()->get_bl_28mm(filter_input(INPUT_POST,'kpidate'))+$rfmr28+$rf_l28mm;
  $BL28mm= ($T28mmprod*$bloss28mmpercent)/100;
  
  $T32mmprod=RollingBD::getInstance()->get_bl_32mm(filter_input(INPUT_POST,'kpidate'))+$rfmr32+$rf_l32mm;
  $BL32mm= ($T32mmprod*$bloss32mmpercent)/100;
  
  
  
  
 
  //TOTAL BURNING LOSS CALCULATION
$TBL=$BL8mm+$BL10mm+$BL12mm+$BL16mm+$BL20mm+$BL25mm+$BL28mm+$BL32mm;
 

//cutting production calculated size wise and overall
$net8mm=number_format((float)$T8mmprod-($rfmr8+$cut8+$BL8mm+$rf_l8mm+($endcut8wt/1000)),3,'.','');
$net10mm=number_format((float)$T10mmprod-($rfmr10+$cut10+$BL10mm+$rf_l10mm+($endcut10wt/1000)),3,'.','');
$net12mm=number_format((float)$T12mmprod-($rfmr12+$cut12+$BL12mm+$rf_l12mm+($endcut12wt/1000)),3,'.','');
$net16mm=number_format((float)$T16mmprod-($rfmr16+$cut16+$BL16mm+$rf_l16mm+($endcut16wt/1000)),3,'.','');
$net20mm=number_format((float)$T20mmprod-($rfmr20+$cut20+$BL20mm+$rf_l20mm+($endcut20wt/1000)),3,'.','');
$net25mm=number_format((float)$T25mmprod-($rfmr25+$cut25+$BL25mm+$rf_l25mm+($endcut25wt/1000)),3,'.','');
$net28mm=number_format((float)$T28mmprod-($rfmr28+$cut28+$BL28mm+$rf_l28mm+($endcut28wt/1000)),3,'.','');
$net32mm=number_format((float)$T32mmprod-($rfmr32+$cut32+$BL32mm+$rf_l32mm+($endcut32wt/1000)),3,'.','');

//Final (net tmtproduction)
$NETTMT= number_format((float)($net8mm+$net10mm+$net12mm+$net16mm+$net20mm+$net25mm+$net28mm+$net32mm)-($endcutmrwt/1000),3,'.','');



//Per ton Power consumption
$PERTONUNITS=number_format((float)$pertonpower/$NETTMT,2,'.','');
//Total number of pieces rollied in rolling mill

$PerTonWaterUnitsMILL=number_format((float)$pertonwatermill/$NETTMT,2,'.','');
$PerTonWaterUnitsTMT=number_format((float)$pertonwatertmt/$NETTMT,2,'.','');
  
//TOTAL MISSROLLS OF THAT PARTICULAR DAY  
 $Tmissrols= RollingBD::getInstance()->get_total_mr_ina_day(filter_input(INPUT_POST,'kpidate'));
  
 //total missroll due to mill
$Tmrmill= RollingBD::getInstance()->get_total_mr(filter_input(INPUT_POST,'kpidate'),5);

//Total missrolls due to Electrical
$Tmrelect= RollingBD::getInstance()->get_total_mr(filter_input(INPUT_POST,'kpidate'),3);

//Total missrolls due to Mechanical
$Tmrmech= RollingBD::getInstance()->get_total_mr(filter_input(INPUT_POST,'kpidate'),4);

//Total missrolls due to ccm
$Tmrccm= RollingBD::getInstance()->get_total_mr(filter_input(INPUT_POST,'kpidate'),2);

//Total missrolls due to furnace 
$Tmrfnce= RollingBD::getInstance()->get_total_mr(filter_input(INPUT_POST,'kpidate'),1);

//Total missrolls due to mpebpowercut
$Tmrmpeb= RollingBD::getInstance()->get_total_mr(filter_input(INPUT_POST,'kpidate'),7);


//TOTAL DEPENDENT MISSROLLS
$Tdependmr=RollingBD::getInstance()->get_depen_mr(filter_input(INPUT_POST,'kpidate'));

//TOTAL INDEPENDENT MISSROLLS
$Tindependmr=RollingBD::getInstance()->get_indepen_mr(filter_input(INPUT_POST,'kpidate'));

 //total missroll production
$Tmrprod=RollingBD::getInstance()->get_mr_prod(filter_input(INPUT_POST,'kpidate'));

//Total Missroll production in percentage
$Tpermrprod =($Tmrprod/$NETTMT)*100;

//total missroll production due to mill
$Tmrprodmill=RollingBD::getInstance()->get_mr_prod_mill(filter_input(INPUT_POST,'kpidate'));
//percentage of miss roll production due to mill

$Tperprodmill=$Tmrprodmill/$NETTMT;
/**
 * CALCULATING THE PERCENTAGE OF THE MILL MISSROLL PRODUCTION OF THE DAY SO IT IS BEING DIVIDED BY CCM PRODUCTION
 * 
 */
$Tpermrprodmillccm=number_format((float)$Tmrprod/$Tccmproduction*100,2,'.','');

//TOTAL NUMBER OF CUTTING
$Tcutting=RollingBD::getInstance()->get_total_cutting(filter_input(INPUT_POST,'kpidate'));
//TOTAL CUTTING WEIGHT IN PERCENTAGE
$Tpercutprod =(($Tcutting*.100)/$NETTMT)*100;
//Total cutting due to mill , mechanical and electrical
$Tcuttingmill=RollingBD::getInstance()->get_total_cutting_mill(filter_input(INPUT_POST,'kpidate'));
//TOTAL CUTTING IN PERCENTAGE DUE TO MILL.
$percutinmill=(($Tcuttingmill*.100)/$NETTMT)*100;
//TOTAL CUTTING DUE TO MPEB
$Tcuttingmpeb=RollingBD::getInstance()->get_total_cutting_mpeb(filter_input(INPUT_POST,'kpidate'));
//TOTAL CUTTING IN PERCENTAGE DUE TO MPEB
$percutinmpeb=(($Tcuttingmpeb*.100)/$NETTMT)*100;
//TOTAL CUTTING DUE TO CCM
$Tcuttingccm=RollingBD::getInstance()->get_total_cutting_ccm(filter_input(INPUT_POST,'kpidate'));
//TOTAL CUUTING DUE TO CCM IN PERCENTAGE 
$percutinccm=(($Tcuttingccm*.100)/$NETTMT)*100;
//TOTAL CUTTING DUE TO FURNACE 
$Tcuttingfnce=RollingBD::getInstance()->get_total_cutting_fnce(filter_input(INPUT_POST,'kpidate'));
//TOTAL CUTTING IN PERCENTAGE DUE TO FURNACE 
$percutinfnce=(($Tcuttingfnce*.100)/$NETTMT)*100;
/**
 //Total Bypass due to CCM
 $Tbbypassccm=RollingBD::getInstance()->get_billets_bypass($_POST['kpidate'],2);
 echo $Tbbypassccm;
 
 //Total Bypass due to Furnace
 $Tbbypassfurnace=RollingBD::getInstance()->get_billets_bypass($_POST['kpidate'],1);
 echo $Tbbypassfurnace;
  
//Total Bypass due to MPEB
 $Tbbypassmpeb=RollingBD::getInstance()->get_billets_bypass($_POST['kpidate'],7);
  echo $Tbbypassmpeb;
 **/
  //Total bypass 3rd stand 3mtr
  $Tbbypass3st3mtr=RollingBD::getInstance()->get_3rdstand_bypass_3mtr(filter_input(INPUT_POST,'kpidate'));
  //echo $Tbbypass3st3mtr;
  
  //Total bypass 3stnad 6mtt
  $Tbbypass3st6mtr=RollingBD::getInstance()->get_3rdstand_bypass_6mtr(filter_input(INPUT_POST,'kpidate'));
  //echo $Tbbypass3st6mtr;
  

// TOTAL BILLETS BYPASS PROVIDED FROM ANY DEPARTMENT (OVER ALL SUM OF THE  NUMBER OF BILLETS BYPASS (CALCUALTED FROM THE BREAKDOWN TABLE))
$Tbbypass= RollingBD::getInstance()->get_total_billets_bypass(filter_input(INPUT_POST,'kpidate'));

$Tbbypassccm3mtr= RollingBD::getInstance()->get_billets_bypass_3mtr_ccm(filter_input(INPUT_POST,'kpidate'));
$Tbbypassccm6mtr= RollingBD::getInstance()->get_billets_bypass_6mtr_ccm(filter_input(INPUT_POST,'kpidate'));


//TOTAL OK PIECES ROLLED IN ROLLING MILL
$Tokpcsrolledinrolling= RollingBD::getInstance()->get_total_rolled_pcs(filter_input(INPUT_POST,'kpidate'));

// TOTAL PIECES COUNT COME FROM CCM INCLUDING OK PIECES OF ROLLING ALSO 
$Tpiecescountinccm=$Tmissrols+$Tokpcsrolledinrolling+$Tbbypass+$Tbbypass3st3mtr+2*$Tbbypass3st6mtr+$Tbbypassccm3mtr+2*$Tbbypassccm6mtr;
//24hours hotrolling
$HOTROLLING24HR= number_format(((float)($Tokpcsrolledinrolling/$Tpiecescountinccm)*100),2,'.','');

//ENDCUT MISSROLL CALCULATION
$encutmrweight=($endcutmrwt/1000);
$ENDCUTMRINPER=number_format((float)($encutmrweight/$NETTMT)*100,2,'.','');

//Total missroll(%) clculated as SUM OF totalcutting weight production and miss roll production and Endcut Misrroll percentage
$TcuttingMRPRODINPERCENT= number_format((float)($Tpercutprod+$Tpermrprod+$ENDCUTMRINPER),2,'.','');
//Total Bypass production due to  Furnace
$BYPASSPRODfnce= RollingBD::getInstance()->get_bypass_prod((filter_input(INPUT_POST,'kpidate')),1);
//Final Production
$PERBYPASSPRODfnce=($BYPASSPRODfnce/$Tccmproduction)*100;
//Total production down time due to Furnace
$Tdntimefncemin= RollingBD::getInstance()->get_prod_down_time_dept_min((filter_input(INPUT_POST,'kpidate')),1);
//Total Production down time in hours
$Tdntimefncehr=RollingBD::getInstance()->get_prod_down_time_dept_hr((filter_input(INPUT_POST,'kpidate')),1);

//TOTAL BILLETS BYPASS DUE TO CCM
$bbypassccm=RollingBD::getInstance()->get_billets_bypass((filter_input(INPUT_POST,'kpidate')),2);
//total bypass due to ccm 
$Tbbypassccm=$bbypassccm+$Tbbypassccm3mtr+2*$Tbbypassccm6mtr;
//Total Bypass production due to  CCM
$BYPASSPRODCCM= RollingBD::getInstance()->billets_by_pass_prod_due_ccm(filter_input(INPUT_POST,'kpidate'));
//Final Production
$PERBYPASSPRODCCM=number_format((float)($BYPASSPRODCCM/$Tccmproduction)*100,2,'.','');
//Total production down time due to CCM IN MINUTES
$Tdntimeccmmin= RollingBD::getInstance()->get_prod_down_time_dept_min((filter_input(INPUT_POST,'kpidate')),2);
//Total Production down time in hours
$Tdntimeccmhr=RollingBD::getInstance()->get_prod_down_time_dept_hr((filter_input(INPUT_POST,'kpidate')),2);

//Total production down time due to Electrical
echo"electrical down time";
$Tdntimeelecmin= RollingBD::getInstance()->get_prod_down_time_dept_min(filter_input(INPUT_POST,'kpidate'),3);
//Total Production down time in hours
$Tdntimeelechr=RollingBD::getInstance()->get_prod_down_time_dept_hr((filter_input(INPUT_POST,'kpidate')),3);


//Total Bypass production due to  Electrical
$BYPASSPRODELEC= RollingBD::getInstance()->get_bypass_prod((filter_input(INPUT_POST,'kpidate')),3);
//PERCENTAGE OF BYPASS PRODUCTION DUE TO ELECTRICAL
$PERBYPASSPRODELEC=($BYPASSPRODELEC/$Tccmproduction)*100;
//Total Bypass due to Electrical
 $Tbbypasselec=RollingBD::getInstance()->get_billets_bypass((filter_input(INPUT_POST,'kpidate')),3);


//Total production down time due to Mechanical
$Tdntimemechmin= RollingBD::getInstance()->get_prod_down_time_dept_min((filter_input(INPUT_POST,'kpidate')),4);
//Production down time in hr
$Tdntimemechhr=RollingBD::getInstance()->get_prod_down_time_dept_hr(filter_input(INPUT_POST,'kpidate'),4);
//Total Bypass production due to  Mechanical
$BYPASSPRODMECH= RollingBD::getInstance()->get_bypass_prod(filter_input(INPUT_POST,'kpidate'),4);
//PERCENTAGE OF BYPASS DUE TO MECH
$PERBYPASSPRODMECH=($BYPASSPRODMECH/$Tccmproduction)*100;
//Total Bypass due to Mechanical
$Tbbypassmech=RollingBD::getInstance()->get_billets_bypass(filter_input(INPUT_POST,'kpidate'),4);

//Total Billets Bypass due to Mill
$Tbbypassmill=RollingBD::getInstance()->get_billets_bypass((filter_input(INPUT_POST,'kpidate')),5);
//Total Bypass production due to  Mill
$BYPASSPRODMILL= RollingBD::getInstance()->get_bypass_prod((filter_input(INPUT_POST,'kpidate')),5);
//Final Production
$PERBYPASSPRODMILL=($BYPASSPRODMILL/$Tccmproduction)*100;
//Total production down time due to Mill
$Tdntimemillmin= RollingBD::getInstance()->get_prod_down_time_dept_min(filter_input(INPUT_POST,'kpidate'),5);
//Total Production down time in hours
$Tdntimemillhr=RollingBD::getInstance()->get_prod_down_time_dept_hr(filter_input(INPUT_POST,'kpidate'),5);

//Total Billets Bypass due to Contractor
$Tbbypasscont=RollingBD::getInstance()->get_billets_bypass((filter_input(INPUT_POST,'kpidate')),6);
//Total Bypass production due to  Contractor
$BYPASSPRODcont= RollingBD::getInstance()->get_bypass_prod((filter_input(INPUT_POST,'kpidate')),6);
//Final Production
$PERBYPASSPRODcont=($BYPASSPRODcont/$Tccmproduction)*100;
//Total production down time due to Contractor
$Tdntimecontmin= RollingBD::getInstance()->get_prod_down_time_dept_min((filter_input(INPUT_POST,'kpidate')),6);
//Total Production down time in hours
$Tdntimeconthr=RollingBD::getInstance()->get_prod_down_time_dept_hr((filter_input(INPUT_POST,'kpidate')),6);



 

//Total production down time due to MPEB
$Tdntimempebmin= RollingBD::getInstance()->get_prod_down_time_dept_min((filter_input(INPUT_POST,'kpidate')),7);
//Total Production down time in hours
$Tdntimempebhr=RollingBD::getInstance()->get_prod_down_time_dept_hr((filter_input(INPUT_POST,'kpidate')),7);
//Total Bypass production due to  MPEB
$BYPASSPRODMPEB= RollingBD::getInstance()->get_bypass_prod((filter_input(INPUT_POST,'kpidate')),7);
//PERCEBTNGE OF BBYPASSPROD DUE TO MPEB
$PERBYPASSPRODMPEB=number_format((float)($BYPASSPRODMPEB/$Tccmproduction)*100,2,'.','');
  //Total Bypass due to MPEB
$Tbbypassmpeb=RollingBD::getInstance()->get_billets_bypass((filter_input(INPUT_POST,'kpidate')),7);


 
//Porduction down time due to passchange


//down time due to passchange in hours
$Tdntimepchhr= RollingBD::getInstance()->get_prod_down_time_reason_hr(filter_input(INPUT_POST,'kpidate'),37);
$Tdntimepchmin= RollingBD::getInstance()->get_prod_down_time_reason_min((filter_input(INPUT_POST,'kpidate')),37);
echo $Tdntimepchmin;
// total bypass production due to pass change
$bypsprodpc=RollingBD::getInstance()->get_bypass_prod_reason(filter_input(INPUT_POST,'kpidate'),37);
//TOTAL PERCENTAGE OF BYPASS PRODUCTION DUE TO PASS CHANGE
$Tbypsprodpc=($bypsprodpc/$Tccmproduction)*100;
//total bypass due to pass change
$Tbbypasspc=RollingBD::getInstance()->get_billets_bypass_reason(filter_input(INPUT_POST,'kpidate'),37);

// total production down time due to size change
$Tdntimescmin= RollingBD::getInstance()->get_prod_down_time_reason_min((filter_input(INPUT_POST,'kpidate')),52);
// down time due to size change in hours
$Tdntimeschr=RollingBD::getInstance()->get_prod_down_time_reason_hr((filter_input(INPUT_POST,'kpidate')),52);
// total bypass production due to size change
$bypsprodsc=RollingBD::getInstance()->get_bypass_prod_reason((filter_input(INPUT_POST,'kpidate')),52);
//TOTAL PERCENTAGE OF BYPASS PRODUCTION DUE TO SIZE CHANGE
$Tbypsprodsc=($bypsprodsc/$Tccmproduction)*100;
// total bypass due to size change
$Tbbypasssc=RollingBD::getInstance()->get_billets_bypass_reason((filter_input(INPUT_POST,'kpidate')),52);


// total production down time due to Roll change in min
$Tdntimercmin= RollingBD::getInstance()->get_prod_down_time_reason_min((filter_input(INPUT_POST,'kpidate')),47);
// down time due to size change in hours
$Tdntimerchr=RollingBD::getInstance()->get_prod_down_time_reason_hr((filter_input(INPUT_POST,'kpidate')),47);
// total bypass production due to size change
$bypsprodrc=RollingBD::getInstance()->get_bypass_prod_reason((filter_input(INPUT_POST,'kpidate')),47);
$Tbypsprodrc=($bypsprodrc/$Tccmproduction)*100;
// total bypass due to size change
$Tbbypassrc=RollingBD::getInstance()->get_billets_bypass_reason((filter_input(INPUT_POST,'kpidate')),47);


// total production down time due to lahar
$Tdntimelrmin= RollingBD::getInstance()->get_prod_down_time_reason_min((filter_input(INPUT_POST,'kpidate')),26);
//Total Production downtimr in hr
$Tdntimelrhr=RollingBD::getInstance()->get_prod_down_time_reason_hr((filter_input(INPUT_POST,'kpidate')),26);
// total bypass production due to lahar
$bypsprodlr=RollingBD::getInstance()->get_bypass_prod_reason((filter_input(INPUT_POST,'kpidate')),26);
$Tbypsprodlr=($bypsprodlr/$Tccmproduction)*100;
// total bypass due to lahar
$Tbbypasslr=RollingBD::getInstance()->get_billets_bypass_reason((filter_input(INPUT_POST,'kpidate')),26);



// total down time due to quality chill in min
$Tdntimechillimin= RollingBD::getInstance()->get_prod_down_time_reason_min((filter_input(INPUT_POST,'kpidate')),10);
// total down time in hr in due toquality chill
$Tdntimechillihr=RollingBD::getInstance()->get_prod_down_time_reason_hr((filter_input(INPUT_POST,'kpidate')),10);
// total bypass production due to qualitychilli
$bypsprodch=RollingBD::getInstance()->get_bypass_prod_reason((filter_input(INPUT_POST,'kpidate')),10);
$Tbypsprodch=($bypsprodch/$Tccmproduction)*100;
// total bypass due to qualitychilli
$Tbbypasschilli=RollingBD::getInstance()->get_billets_bypass_reason((filter_input(INPUT_POST,'kpidate')),10);


// total down time due to mIlll chill in min
$Tdntimemillchill= RollingBD::getInstance()->get_prod_down_time_reason_min((filter_input(INPUT_POST,'kpidate')),62);
// total down time in hr in due mIlll chill
$Tdntimemillchillhr=RollingBD::getInstance()->get_prod_down_time_reason_hr((filter_input(INPUT_POST,'kpidate')),62);
// total bypass production due to mIlll CHILLI
$bypsprodmillchill=RollingBD::getInstance()->get_bypass_prod_reason(filter_input(INPUT_POST,'kpidate'),62);
$Tbypsprodmillchill=($bypsprodmillchill/$Tccmproduction)*100;
// total bypass due to mIlll CHILLI
$Tbbypassmillchill=RollingBD::getInstance()->get_billets_bypass_reason(filter_input(INPUT_POST,'kpidate'),62);



//total down time due to crack
$Tdntimecrackmin= RollingBD::getInstance()->get_prod_down_time_reason_min(filter_input(INPUT_POST,'kpidate'),4);
// total down time in hr in due to crack
$Tdntimecrackhr=RollingBD::getInstance()->get_prod_down_time_reason_hr(filter_input(INPUT_POST,'kpidate'),4);
// total bypass production due to crack
$bypsprodck=RollingBD::getInstance()->get_bypass_prod_reason(filter_input(INPUT_POST,'kpidate'),4);
//TOTAL PERCENTAGE OF BYPASS PROD DUE TO CRACK
$Tbypsprodck=($bypsprodck/$Tccmproduction)*100;
// total bypass  due to crack
$Tbbypasscrack=RollingBD::getInstance()->get_billets_bypass_reason(filter_input(INPUT_POST,'kpidate'),4);

//Total down time due to piping
$Tdntimepipingmin= RollingBD::getInstance()->get_prod_down_time_reason_min(filter_input(INPUT_POST,'kpidate'),41);
// total down time in hr in due to piping
$Tdntimepipinghr=RollingBD::getInstance()->get_prod_down_time_reason_hr(filter_input(INPUT_POST,'kpidate'),41);
// total bypass production due to piping
$bypsprodpp=RollingBD::getInstance()->get_bypass_prod_reason(filter_input(INPUT_POST,'kpidate'),41);
//TOTAL PERCENTAGE OF BYPASS PRODUCTION DUE TO PIPING
$Tbypsprodpp=($bypsprodpp/$Tccmproduction)*100;
// total bypass due to piping
$Tbbypasspiping=RollingBD::getInstance()->get_billets_bypass_reason(filter_input(INPUT_POST,'kpidate'),41);

// production down time due to ccd composition
$Tdntimeccdmin= RollingBD::getInstance()->get_prod_down_time_reason_min(filter_input(INPUT_POST,'kpidate'),45);
// production down time in hr due to ccd composition
$Tdntimeccdhr=RollingBD::getInstance()->get_prod_down_time_reason_hr(filter_input(INPUT_POST,'kpidate'),45);
// bypass production due to ccd composition
$bypsprodccd=RollingBD::getInstance()->get_bypass_prod_reason(filter_input(INPUT_POST,'kpidate'),45);
//TOTAL PERCENTAGE OF BYPASS PRODUCTION DUE TO CCD
$Tbypsprodccd=($bypsprodccd/$Tccmproduction)*100;
// total bypass due to ccd composition
$Tbbypassccd=RollingBD::getInstance()->get_billets_bypass_reason(filter_input(INPUT_POST,'kpidate'),45);


// production down time due to Maintenance
$Tdntimementmin= RollingBD::getInstance()->get_prod_down_time_reason_min(filter_input(INPUT_POST,'kpidate'),30);
// production down time in hr due to Maintenance
$Tdntimementhr=RollingBD::getInstance()->get_prod_down_time_reason_hr(filter_input(INPUT_POST,'kpidate'),30);
// bypass production due to Maintenance
$bypsprodment=RollingBD::getInstance()->get_bypass_prod_reason(filter_input(INPUT_POST,'kpidate'),30);
//TOTAL PERCENTAGE OF BYPASS PRODUCTION DUE TO MAINTENANCE
$Tbypsprodment=($bypsprodment/$Tccmproduction)*100;
// total bypass due to Maintenance
$Tbbypassment =RollingBD::getInstance()->get_billets_bypass_reason(filter_input(INPUT_POST,'kpidate'),30);

// production down time due to MOUTHOPEN
$Tdntimemopenmin= RollingBD::getInstance()->get_prod_down_time_reason_min(filter_input(INPUT_POST,'kpidate'),33);
// production down time in hr due to MOUTHOPEN
$Tdntimemopenhr=RollingBD::getInstance()->get_prod_down_time_reason_hr(filter_input(INPUT_POST,'kpidate'),33);
// bypass production due to MOUTHOPEN
$bypsprodmopen=RollingBD::getInstance()->get_bypass_prod_reason(filter_input(INPUT_POST,'kpidate'),33);
//TOTAL PERCENTAHE OF  BYPASS PRODUCTION DUE TO MOUTH OPEN
$Tbypsprodmopen=($bypsprodmopen/$Tccmproduction)*100;
// total bypass due to MOUTHOPEN
$Tbbypassmopen =RollingBD::getInstance()->get_billets_bypass_reason(filter_input(INPUT_POST,'kpidate'),33);


//TOTAL PERCENTAGE OF BYPASS PRODUCTION DUE TO MILL(MILL, MECHANICAL AND ELECTRICAL)
$FINALPERBYPASSMILL=number_format((float)($PERBYPASSPRODELEC+$PERBYPASSPRODMECH+$PERBYPASSPRODMILL),2,'.','');
//
$hotrollingAvailabilty= 100-$FINALPERBYPASSMILL;
//Total number of bypass due to MILL 
$Tbypassduetomill=$Tbbypassmill+$Tbbypassmech+$Tbbypasselec;


// Total Number of bypass due to ccm
$Tbypassduetoccm=$Tbbypasspiping+$Tbbypasschilli+$Tbbypasscrack+$Tbbypassmopen;

//Total Bypass Production due to Mill ,Mechinacal and Electrical
$Tperbypassmill=$PERBYPASSPRODELEC+$PERBYPASSPRODMILL+$PERBYPASSPRODMECH;
//Total Bypass Production due to CCM
$Tperbypassccm=$Tbypsprodpp+$Tbypsprodch+$Tbypsprodck+$Tbypsprodmopen;

$Tperbbypass3st=RollingBD::getInstance()->billets_bypass_prod_3rdstand(filter_input(INPUT_POST,'kpidate'));

//Total Billets Bypass due to Furnace

$Tbbypassfnce=RollingBD::getInstance()->get_billets_bypass((filter_input(INPUT_POST,'kpidate')),1);


// Mechanical  Availability
$mechanical_availability= ((1440-$Tdntimemechmin)/1440)*100;
//Electrical Availability
$electrical_availability=((1440-$Tdntimeelecmin)/1440)*100;


//TOTAL BYPASS PRODCUTION DUE TO 3RD STAND
$perbbypass3st=RollingBD::getInstance()->billets_bypass_prod_3rdstand(filter_input(INPUT_POST,'kpidate'));
//TOTAL BYPASS PRODUCTION DUE TO 3RD STAND DIVIDE BY CCM.
$Tperbypass3st=number_format(((float)($perbbypass3st/$Tccmproduction)*100),2,'.','');
//CALCULATING HOT ROLLING PERCENTAGE AND MISSROLL OF THE DAY TIME 
$DayHotrolling=number_format(((float)RollingBD::getInstance()->get_total_hotrolling_of_shift(filter_input(INPUT_POST,'kpidate'),'Day')),2,'.','');
$DayMisrroll=RollingBD::getInstance()->get_total_missroll_of_shift(filter_input(INPUT_POST,'kpidate'),'Day');
$DayCCMMR=RollingBD::getInstance()->get_total_missroll_of_dept_in_shift(filter_input(INPUT_POST,'kpidate'),'Day',2);
$DayELECMR=RollingBD::getInstance()->get_total_missroll_of_dept_in_shift(filter_input(INPUT_POST,'kpidate'),'Day',3);
$DayMECHMR=RollingBD::getInstance()->get_total_missroll_of_dept_in_shift(filter_input(INPUT_POST,'kpidate'),'Day',4);
$DayMillMR=RollingBD::getInstance()->get_total_missroll_of_dept_in_shift(filter_input(INPUT_POST,'kpidate'),'Day',5);
$DayMPEBMR=RollingBD::getInstance()->get_total_missroll_of_dept_in_shift(filter_input(INPUT_POST,'kpidate'),'Day',7);
// CALCULATING HOT ROLLING AND MISSROLL OF THE NIGHT TIME
$NightHotrolling=number_format(((float)RollingBD::getInstance()->get_total_hotrolling_of_shift(filter_input(INPUT_POST,'kpidate'),'Night')),2,'.','');
$NightMisrroll=RollingBD::getInstance()->get_total_missroll_of_shift(filter_input(INPUT_POST,'kpidate'),'Night');
$NightCCMMR=RollingBD::getInstance()->get_total_missroll_of_dept_in_shift(filter_input(INPUT_POST,'kpidate'),'Night',2);
$NightELECMR=RollingBD::getInstance()->get_total_missroll_of_dept_in_shift(filter_input(INPUT_POST,'kpidate'),'Night',3);
$NightMECHMR=RollingBD::getInstance()->get_total_missroll_of_dept_in_shift(filter_input(INPUT_POST,'kpidate'),'Night',4);
$NightMillMR=RollingBD::getInstance()->get_total_missroll_of_dept_in_shift(filter_input(INPUT_POST,'kpidate'),'Night',5);
$NightMPEBMR=RollingBD::getInstance()->get_total_missroll_of_dept_in_shift(filter_input(INPUT_POST,'kpidate'),'Night',7);

$first_date = date('Y-m-01', strtotime($date_1)); // hard-coded '01' for first day
$last_date  = date('Y-m-t', strtotime($date_1));//Last Day


echo $first_date;
echo $last_date;

/**
 * Heat Gap and Total Heat Time Calculation
 */




$firstheatsttime = RollingBD::getInstance()->get_First_Heat_Start_Time(filter_input(INPUT_POST,'kpidate'));
echo $firstheatsttime;
 //End time of the Last heat
$lastheatendtime = RollingBD::getInstance()->get_Last_Heat_End_Time(filter_input(INPUT_POST,'kpidate'));
echo $lastheatendtime;
echo"<br>"; echo"testtime"; echo "<br>";





//Difference of the Lastheat end time and first heat start time
$totaltime= abs(strtotime($lastheatendtime) - strtotime($firstheatsttime))/3600;
echo $totaltime;
$seconds_ph1 = ($totaltime * 3600);
echo $seconds_ph1; echo"<br>";
$hours_ph1 = floor($totaltime);
echo $hours_ph1; echo"<br>";
$seconds_ph1 -= $hours_ph1 * 3600;
echo $seconds_ph1;echo"<br>";
$minutes_ph1 = floor($seconds_ph1 / 60);
echo $minutes_ph1; echo"<br>";
$seconds_ph1 -= $minutes_ph1 * 60;
echo $seconds_ph1; echo"<br>";
// Converting  them in to hh:mm format
$totalheatime = lz($hours_ph1) . ":" . lz($minutes_ph1);
echo $totalheatime;
                                                                                         


$f_heatgap=RollingBD::getInstance()->get_total_heat_gap(filter_input(INPUT_POST,'kpidate'));

if($kpidate === $first_date){
   
    $monthlynettmt=$NETTMT;
    $monthlyhotrolling= number_format((float)(($TROLLINGPROD/$Tccmproduction)*100),2,'.','');
   
}
else{
  
   $row_2 = mysqli_fetch_row(mysqli_query($link, "select sum(`net_tmt`) ,sum(gross_tmt), sum(total_ccm_prod) from `jd2_rolling_kpi_24hrs` where kpi_date between '".$first_date."' and'".$last_date."' "));
   $total_nettmt=$row_2[0];
   $total_grosstmt=$row_2[1];
   $total_ccmprod=$row_2[2];
    $monthlynettmt= number_format((float)($total_nettmt+$NETTMT),3,'.','');
  
   $monthlyhotrolling= number_format((float)(($total_grosstmt+$TROLLINGPROD)/($total_ccmprod+$Tccmproduction)*100),2,'.','');
   
   
}
$total_bd_time=RollingBD::getInstance()->get_bd_time_of_a_day(filter_input(INPUT_POST,'kpidate'));

//ISI percentage according to Moira.
$perisigradewise=(($isigradewisetmt)/(1000*$NETTMT))*100;
$finalpergradewisemoira=100-$perisigradewise;

//ISI Percentage according to weight
$perisiweightwise=(($isiweightwisetmt)/(1000*$NETTMT))*100;
$finalperweightwisemoira=100-$perisiweightwise;
//percentage of Coolingbedenduct% and millendcut%

$percoolingbedendcut=(($coolingbedendcut)/(1000*$NETTMT))*100;
$permillendcut=(($millendcut)/(1000*$NETTMT))*100;


$sql_kpi= "INSERT INTO `jd2_rolling_kpi_24hrs` (`kpi_date`, `heat_count`, `gross_tmt`, `total_ccm_prod`, `total_heat_time`, `heat_run_time`, 
`heat_gap`, `per_ton_power_consumption`, `per_ton_power_units`, `endcutwt8`, `endcutwt10`, `endcutwt12`,
 `endcutwt16`, `endcutwt20`, `endcutwt25`, `endcutwt28`, `endcutwt32`, `endcut_mr_wt`, `prod_8mm`, `prod_10mm`, 
 `prod_12mm`, `prod_16mm`, `prod_20mm`, `prod_25mm`, `prod_28mm`, `prod_32mm`, `bloss_8mm_prod`, `bloss_10mm_prod`, `bloss_12mm_prod`, 
 `bloss_16mm_prod`, `bloss_20mm_prod`, `bloss_25mm_prod`, `bloss_28mm_prod`, `bloss_32mm_prod`, `rfmr_8prod`, `rfmr_10prod`, `rfmr_12prod`, `rfmr_16prod`,
 `rfmr_20prod`, `rfmr_25prod`, `rfmr_28prod`, `rfmr_32prod`, `rf_side_8prod`, `rf_side_10prod`, `rf_side_12prod`, `rf_side_16prod`, 
 `rf_side_20prod`, `rf_side_25prod`, `rf_side_28prod`, `rf_side_32prod`, `Total_rf_side_prod`, `cut_8_prod`, `cut_10_prod`, `cut_12_prod`,
 `cut_16_prod`, `cut_20_prod`, `cut_25_prod`, `cut_28_prod`, `cut_32_prod`, `net_8mm_prod`, `net_10mm_prod`, `net_12mm_prod`, `net_16mm_prod`, 
 `net_20mm_prod`, `net_25mm_prod`, `net_28mm_prod`, `net_32mm_prod`, `totalcuttingprod`, `totalrfmrprod`, `total_bloss_prod`, `net_tmt`, `total_rolling_pieces`,
 `total_ccm_pieces`, `24hrshotrolling`, `monthlyhotrolling`, `monthly_net_tmt`, `endcut_missroll_perc`, `missroll_percent`, `mill_missroll_percent`, `total_missroll`, 
 `total_mill_missroll`, `total_elec_missroll`, `total_mech_missroll`, `total_ccm_missroll`, `total_furnace_missroll`, `total_mpeb_missroll`, `total_indep_missroll`, 
 `total_dep_missroll`, `total_cutting_perc`, `total_cutting`, `total_cutting_mill_perc`, `total_mill_cutting`, `total_cutting_ccm_perc`, `total_ccm_cutting`,
 `total_furnace_cutting_perc`,`total_furnace_cutting`, `total_mpeb_cutting_perc`, `total_mpeb_cutting`, `missroll_cutting_prod_percent`, `prod_downtime_mill_min`, `prod_downtime_mill_hr`, `total_bypass_mill`, 
 `billets_bypass_prod_mill_percent`, `prod_downtime_mech_min`, `prod_downtime_mech_hr`, `total_bypass_mech`, `billets_bypass_prod_mech_percent`, `prod_downtime_elec_min`, 
 `prod_downtime_elec_hr`, `total_bypass_elec`, `billets_bypass_prod_elec_percent`, `prod_downtime_passchange_min`, `prod_downtime_passchange_hr`, `total_bypass_passchange`,
 `billets_bypass_prod_passchange_percent`, `prod_downtime_size_change_min`, `prod_downtime_size_change_hr`, `total_bypass_size_change`, `billets_bypass_prod_size_change_percent`, 
 `prod_downtime_maintenance_min`, `prod_downtime_maintenance_hr`, `total_bypass_maintenance`, `billets_bypass_prod_maintenance_percent`, `prod_downtime_mpeb_min`, `prod_downtime_mpeb_hr`,
 `total_bypass_mpeb`, `billets_bypass_prod_mpeb_percent`, `prod_downtime_lahar_min`, `prod_downtime_lahar_hr`, `total_bypass_lahar`, `billets_bypass_prod_lahar_percent`, 
 `prod_downtime_crack_min`, `prod_downtime_crack_hr`, `total_bypass_crack`, `billets_bypass_prod_crack_percent`, `prod_downtime_piping_min`, `prod_downtime_piping_hr`, 
 `total_bypass_piping`, `billets_bypass_prod_piping_percent`, `prod_downtime_mouth_open_min`, `prod_downtime_mouth_open_hr`, `total_bypass_mouthopen`, 
 `billets_bypass_prod_mouthopen_percent`, `prod_downtime_chill_min`, `prod_downtime_chilli_hr`, `total_bypass_chilli`, `billets_bypass_prod_chilli_percent`,
 `prod_downtime_ccd_composition_min`, `prod_downtime_ccd_composition_hr`, `total_bypass_ccd_composition`, `billets_bypass_prod_ccd_composition_percent`, 
 `prod_downtime_furnace_min`, `prod_downtime_furnace_hr`,`billets_bypass_prod_furnace_percent`, `total_bypass_furnce`, 
 `prod_downtime_ccm_min`, `prod_downtime_ccm_hr`, `percent_billets_bypass_prod_ccm`, `total_billets_bypass_ccm_only`,
 `prod_downtime_contractor_min`, `prod_downtime_contractor_hr`, `total_bypass_contractor`,
 `billets_bypass_contractor_percent`,`bilelts_bypass_prod_3stand_percent`, `total_billets_bypass_mill_only`, `percent_billets_bypass_prod_mill`, 
 `mill_missroll_from_ccm_percent`, `water_consumption_mill`, `per_ton_water_consumption_mill`, `water_consumption_tmt`, 
 `per_ton_consumption_tmt`, `hotrolling_availability`,`mechanical_availability`,`electrical_availability`,
 `coolingbed_endcut_wt`,`mill_endcut_wt`,`perc_coolingbed_endcut`,`perc_mill_endcut_wt`,
 `tmt_wt_out_of_isi_grade` ,`tmt_out_of_isi_weight`,`per_isi_acc_grade`,`per_isi_acc_weight`,
 `per_bloss_8mm`,`per_bloss_10mm`,`per_bloss_12mm`,`per_bloss_16mm`,`per_bloss_20mm`,
 `per_bloss_25mm`,`per_bloss_28mm`,`per_bloss_32mm`,`total_bd_time`) values   
          ('" . $kpidate . "','" . $heatcount . "','" . $TROLLINGPROD . "','" . $Tccmproduction . "','" . $totalheatime . "','" . $HEATRUNTIME . "','" . $f_heatgap . "',"
        . "'" . $pertonpower . "', '" . $PERTONUNITS . "','" . $endcut8wt . "','" . $endcut10wt . "','" . $endcut12wt . "','" . $endcut16wt . "',"
        . "'" . $endcut20wt . "','" . $endcut25wt . "','" . $endcut28wt . "','" . $endcut32wt . "','" . $endcutmrwt . "',"
        . "'" . $T8mmprod . "','" . $T10mmprod . "','" . $T12mmprod . "','" . $T16mmprod . "','" . $T20mmprod . "','" . $T25mmprod . "','" . $T28mmprod . "','" . $T32mmprod . "',"
        . "'" . $BL8mm . "','" . $BL10mm . "','" . $BL12mm . "','" . $BL16mm . "','" . $BL20mm . "','" . $BL25mm . "','" . $BL28mm . "','" . $BL32mm . "',"
        . "'" . $rfmr8 . "','" . $rfmr10 . "','" . $rfmr12 . "','" . $rfmr16 . "','" . $rfmr20 . "','" . $rfmr25 . "','" . $rfmr28 . "','" . $rfmr32 . "',"
        . "'" . $rf_l8mm . "','" . $rf_l10mm . "','" . $rf_l12mm . "','" . $rf_l16mm . "','" . $rf_l20mm . "','" . $rf_l25mm . "','" . $rf_l28mm . "','" . $rf_l32mm . "','" . $trf_lprod . "',"
        . "'" . $cut8 . "','" . $cut10 . "','" . $cut12 . "','" . $cut16 . "','" . $cut20 . "','" . $cut25 . "','" . $cut28 . "','" . $cut32 . "',"
        . "'" . $net8mm . "','" . $net10mm . "','" . $net12mm . "','" . $net16mm . "','" . $net20mm . "','" . $net25mm . "','" . $net28mm . "','" . $net32mm . "',"
        . "'" . $Tcuttingprod . "','" . $Trfmrprod . "','" . $TBL . "','" . $NETTMT . "','" . $Tokpcsrolledinrolling . "','" . $Tpiecescountinccm . "',"
        . "'" . $HOTROLLING24HR . "','" . $monthlyhotrolling . "','" . $monthlynettmt . "','" . $ENDCUTMRINPER . "','" . $Tpermrprod . "',"
        . "'" . $Tperprodmill . "','" . $Tmissrols . "','" . $Tmrmill . "','" . $Tmrelect . "','" . $Tmrmech . "',"
        . "'" . $Tmrccm . "','" . $Tmrfnce . "','" . $Tmrmpeb . "','" . $Tindependmr . "','" . $Tdependmr . "',"
        . "'" . $Tpercutprod . "','" . $Tcutting . "','" . $percutinmill . "','" . $Tcuttingmill . "',"
        . "'" . $percutinccm . "','" . $Tcuttingccm . "','" . $Tcuttingfnce . "','" . $percutinfnce . "','" . $percutinmpeb . "',"
        . "'" . $Tcuttingmpeb . "','" . $TcuttingMRPRODINPERCENT . "',"
        . "'" . $Tdntimemillmin . "','" . $Tdntimemillhr . "','" . $Tbbypassmill . "','" . $PERBYPASSPRODMILL . "',"
        . "'" . $Tdntimemechmin . "','" . $Tdntimemechhr . "','" . $Tbbypassmech . "','" . $PERBYPASSPRODMECH . "',"
        . "'" . $Tdntimeelecmin . "','" . $Tdntimeelechr . "','" . $Tbbypasselec . "','" . $PERBYPASSPRODELEC . "',"
        . "'" . $Tdntimepchmin . "','" . $Tdntimepchhr . "','" . $Tbbypasspc . "','" . $Tbypsprodpc . "',"
        . "'" . $Tdntimescmin . "','" . $Tdntimeschr . "','" . $Tbbypasssc . "','" . $Tbypsprodsc . "',"
        . "'" . $Tdntimementmin . "','" . $Tdntimementhr . "','" . $Tbbypassment . "','" . $Tbypsprodment . "',"
        . "'" . $Tdntimempebmin . "','" . $Tdntimempebhr . "','" . $Tbbypassmpeb . "','" . $PERBYPASSPRODMPEB . "',"
        . "'" . $Tdntimelrmin . "','" . $Tdntimelrhr . "','" . $Tbbypasslr . "','" . $Tbypsprodlr . "',"
        . "'" . $Tdntimecrackmin . "','" . $Tdntimecrackhr . "','" . $Tbbypasscrack . "','" . $Tbypsprodck . "',"
        . "'" . $Tdntimepipingmin . "','" . $Tdntimepipinghr . "','" . $Tbbypasspiping . "','" . $Tbypsprodpp . "',"
        . "'" . $Tdntimemopenmin . "','" . $Tdntimemopenhr . "','" . $Tbbypassmopen . "','" . $Tbypsprodmopen . "',"
        . "'" . $Tdntimechillimin . "','" . $Tdntimechillihr . "','" . $Tbbypasschilli . "','" . $Tbypsprodch . "',"
        . "'" . $Tdntimeccdmin . "','" . $Tdntimeccdhr . "','" . $Tbbypassccd . "','" . $Tbypsprodccd . "',"
        . "'" . $Tdntimefncemin . "','" . $Tdntimefncehr . "','" . $PERBYPASSPRODfnce . "','" . $Tbbypassfnce . "',"
         . "'" . $Tdntimeccmmin . "','" . $Tdntimeccmhr . "','" . $PERBYPASSPRODCCM . "','" . $Tbbypassccm . "',"
        . "'" . $Tdntimecontmin . "', '" . $Tdntimeconthr . "','" . $Tbbypasscont . "','" . $PERBYPASSPRODcont . "',"
        . "'" . $Tperbbypass3st . "','" . $Tbypassduetomill . "','" . $PERBYPASSPRODMILL . "', '" . $Tpermrprodmillccm . "',"
        . "'" . $pertonwatermill . "','" . $PerTonWaterUnitsMILL . "','" . $pertonwatertmt . "','" . $PerTonWaterUnitsTMT . "',"
        . "'" . $hotrollingAvailabilty . "','".$mechanical_availability."','".$electrical_availability."',"
        . "'".$coolingbedendcut."','".$millendcut."','".$percoolingbedendcut."','".$permillendcut."',"
        . "'".$isigradewisetmt."','".$isiweightwisetmt."','".$finalpergradewisemoira."','".$finalperweightwisemoira."',"
        . "'".$bloss8mmpercent."','".$bloss10mmpercent."','".$bloss12mmpercent."','".$bloss16mmpercent."',"
        . "'".$bloss20mmpercent."','".$bloss25mmpercent."','".$bloss28mmpercent."','".$bloss32mmpercent."','".$total_bd_time."')";



$result_kpi = (mysqli_query($link, $sql_kpi) or die(mysqli_error($link)));


// CHECKING THAT EITHER VALUES ARE INSERTED PROPERLY OR NOT
if ($result_kpi) {
    echo"record added";
    //echo $row_cum;
} else {
    echo 'not added';
}

$varA='';
$var8="8mm"; $var10="10mm"; $var12="12mm"; $var16="16mm";$var20="20mm";$var25="25mm";$var28="28mm";$var32="32mm";

if ($net8mm!= 0) {
    $varA =$var8 . ' = ' .  $net8mm . ' ' . "\n";
}
if ($net10mm != 0) {
    $varA = $varA.''.$var10 . ' = ' . $net10mm . '' . "\n";
}
if ($net12mm != 0) {
    $varA = $varA.''. $var12 . ' = ' . $net12mm . '' . "\n";
}
if ($net16mm != 0) {
    $varA = $varA.''.$var16 . ' = ' . $net16mm . '' . "\n";
}
if ($net20mm != 0) {
    $varA = $varA.''.$var20 . ' = ' . $net20mm . '' . "\n";
}
if ($net25mm != 0) {
    $varA = $varA.''.$var25 . ' = ' . $net25mm . '' . "\n";
}
if ($net28mm != 0) {
    $varA = $varA.''.$var28 . ' = ' . $net28mm . '' . "\n";
}
if ($net32mm != 0) {
    $varA = $varA.''.$var32 . ' = ' . $net32mm. '' . "\n";
}
mysqli_close($link);
//POSt Message to Slack CHANNEL ROLLING
Slack::getInstance()->postMessagesToSlack_rollingkpi("
 *Date* - `$kpidate` 
 *24 Hours Hot Rolling %*  = *$HOTROLLING24HR*
 *Monthly hot rolling %*   = *$monthlyhotrolling*
 *Running Time* = `$HEATRUNTIME`
 *Total Miss roll %* =`$TcuttingMRPRODINPERCENT`
 *End Cut Miss roll %* = `$ENDCUTMRINPER`
 *Per ton Power units* = `$PERTONUNITS`
 *Per ton MILL Water Units*=`$PerTonWaterUnitsMILL`   
*Per ton TMT Water Units*=`$PerTonWaterUnitsTMT`   
*$NETTMT* (_*`$monthlynettmt`*_)
 $varA
        ", "Rolling_Jd2");

 if ($Tmrmech === null) {
    $Tmrmech = 0;
}if ($Tmrelect === null) {
    $Tmrelect = 0;
}
if ($Tmissrols === null) {
    $Tmissrols = 0;
}
if ($Tmrccm === null) {
    $Tmrccm = 0;
}if ($Tmrmpeb ===null ) {
    $Tmrmpeb = 0;
}
if ($DayMisrroll === null) {
    $DayMisrroll = 0;
} if ($DayMillMR ===null) {
    $DayMillMR = 0;
}if ($DayMECHMR === null) {
    $DayMECHMR = 0;
}if ($DayELECMR === null) {
    $DayELECMR = 0;
}if ($DayMPEBMR === null) {
    $DayMPEBMR = 0;
}
if($DayCCMMR=== null){
    $DayCCMMR=0;
}

if ($NightMisrroll === null) {
    $NightMisrroll = 0;
} if ($NightMillMR === null) {
    $NightMillMR = 0;
}
if ($NightMECHMR === null) {
    $NightMECHMR = 0;
}if ($NightELECMR === null) {
    $NightELECMR = 0;
}if ($NightMPEBMR === null) {
    $NightMPEBMR = 0;
}
if($NightCCMMR===null){
    $NightCCMMR=0;
}
$postMessagesToSlack_productionreport = Slack::getInstance()->postMessagesToSlack_productionreport("
 *Date =*   `$kpidate`
*Total Heat-*          `$heatcount`
 *Total Hot Rolling%*  `$HOTROLLING24HR`
 *CCM ByPass %-*       `$PERBYPASSPRODCCM`    
 *MILL ByPass %-*      `$FINALPERBYPASSMILL`
 *PowerCut By MPEB%*- `$PERBYPASSPRODMPEB`   
 *Bypass 3rd Stand%-*  `$Tperbypass3st`
 *Mill Missroll%*    `$Tpermrprodmillccm`
 *Total Missroll-*`$Tmissrols`    
 *Mill Missroll-* `$Tmrmill` 
 *Mech Missroll-* `$Tmrmech` 
 *Elec Missroll-* `$Tmrelect`    
 *CCM  Missroll-* `$Tmrccm`
 *MPEB Missroll-* `$Tmrmpeb`
 _*`DAY SHIFT`*_
 *Hot Rolling%=*  `$DayHotrolling`
 *Total Missroll-*`$DayMisrroll`
 *Mill Missroll-* `$DayMillMR`
 *Mech Missroll-* `$DayMECHMR`
 *Elec Missroll-* `$DayELECMR`
 *CCM Missroll-*  `$DayCCMMR`     
 *MPEB Missroll-* `$DayMPEBMR`
_*`NIGHT SHIFT`*_
 *Hot Rolling%=*   `$NightHotrolling`
 *Total Missroll-* `$NightMisrroll`
 *Mill Missroll-*  `$NightMillMR`
 *Mech Missroll-*  `$NightMECHMR`
 *Elec Missroll-*  `$NightELECMR`
 *CCM Missroll-*   `$NightCCMMR`      
 *MPEB Missroll-*  `$NightMPEBMR`      
   
         ","Rolling_Jd2");
Slack::getInstance()->postMessagesToSlack_productionreportpart2("
$varA
NetTMT= *$NETTMT*
MonthlyNetTMT= *$monthlynettmt* 
GrossTMT= *$TROLLINGPROD*
CCM-PROD= *$Tccmproduction*    
MR-Prod=*$Tmrprod*
EndCut-Prod=*$TEndcut*
Burning-loss =*$TBL*    
        ","jd2-prod-msg");
// ON FORM SUBMITTED REDIRECTED TO THE HOME.PHP
header("Location:http://dataapp.moira.local/Rolling_Jd2/Home.php");

exit();
