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

 $res_ex = mysqli_query($link,"SELECT  *  FROM `jd2_rolling_kpi_24hrs` 
            where kpi_date >= '$date1' and kpi_date <= '$date2' order by kpi_date");
 
 
 
$columnHeader = '';
$columnHeader ="kpid" . "\t" .  "kpi_date" . "\t" . "heat_count" . "\t" ."gross_tmt" . "\t" . "ccm_prod" . "\t" ."total_heat_time" . "\t" .
        "heat_run_time " . "\t" . "heat_gap" . "\t" . "total_bd_time" . "\t" ."PowerConsumption" . "\t" . "pertonpowerunits" . "\t" .
         "E8mm" . "\t"."E10mm" . "\t". "E12mm" . "\t". "E16mm" . "\t". "E20mm" . "\t". "E25mm" . "\t". "E28mm" . "\t". "E32mm" . "\t"."Emrwt" . "\t".
      "per_bl8mm" . "\t"."per_bl10mm" . "\t". "per_bl12mm" . "\t". "per_bl16mm" . "\t". "per_bl20mm" . "\t". "per_bl25mm" . "\t". "per_bl28mm" . "\t". "per_bl32mm" . "\t".
         "gross_8mm" . "\t"."gross_10mm" . "\t". "gross_12mm" . "\t". "gross_16mm" . "\t". "gross_20mm" . "\t". "gross_25mm" . "\t". "gross_28mm" . "\t". "gross_32mm" . "\t".
       "bl_prod_8mm" . "\t"."bl_prod_10mm" . "\t". "bl_prod_12mm" . "\t". "bl_prod_16mm" . "\t". "bl_prod_20mm" . "\t". "bl_prod_25mm" . "\t". "bl_prod_28mm" . "\t". "bl_prod_32mm" . "\t".
      "missroll_prod_8mm" . "\t"."missroll_prod_10mm" . "\t". "missroll_prod_12mm" . "\t". "missroll_prod_16mm" . "\t". "missroll_prod_20mm" . "\t". "missroll_prod_25mm" . "\t". "missroll_prod_28mm" . "\t". "missroll_prod_32mm" . "\t".
     "rf_misroll_prod_8mm" . "\t"."rf_misroll_prod_10mm" . "\t". "rf_misroll_prod_12mm" . "\t". "rf_misroll_prod_16mm" . "\t". "rf_misroll_prod_20mm" . "\t". "rf_misroll_prod_25mm" . "\t". "rf_misroll_prod_28mm" . "\t". "rf_misroll_prod_32mm" . "\t". "total_rf_missroll_prod" .  "\t".
      "cutting_8mm_prod" . "\t"."cutting_10mm_prod" . "\t". "cutting_12mm_prod" . "\t". "cutting_16mm_prod" . "\t". "cutting_20mm_prod" . "\t". "cutting_25mm_prod" . "\t". "cutting_28mm_prod" . "\t". "cutting_32mm_prod" .  "\t". 
      "net8mm" . "\t"."net10mm" . "\t". "net12mm" . "\t". "net16mm" . "\t". "net20mm" . "\t". "net25mm" . "\t". "net28mm" . "\t". "net32mm" . "\t".
       "Tcutprod" ."\t"."Trfprod" . "\t"."Tblossprod" . "\t".
       "nettmt" ."\t". "OKpiecesrolled" . "\t".
        "TCCMpieces" . "\t"."24hrshotrolling" . "\t".  "monthlyhotrolling" . "\t".  "monthlynettmt".  "\t"."Tendcutmissrol(%)" . "\t".
        "total_missroll(%)" . "\t"."total_mill_missroll(%)" . "\t"."total_missroll" . "\t".  "total_mill_missroll" . "\t". "total_elec_missroll" . "\t"."TMechMR" . "\t".
        "total_ccm_missroll" . "\t"."total_furnace_missroll" . "\t"."total_mpeb_missroll" . "\t".  "indep_missroll" . "\t". "dep_missroll" . "\t".
        "Tcutting(%)" . "\t"."Tcutting" ."\t"."TcuttingMill(%)" . "\t"."TcuttingMill" ."\t". "TcuttingCCM(%)" . "\t"."TcuttingCCM". "\t". 
        "TcuttingFnce(%)" . "\t"."TcuttingFnce"  . "\t".  "TcuttingMPEB(%)" . "\t". "TcuttingMPEB" . "\t".
        "Tmrcuttingprod(%)" . "\t". 
        "prod_downtime_mill(min)" . "\t"."prod_downtime_mill(HR)" ."\t"."total_mill_bypass" . "\t"."total_mill_bypass_prod(%)" . "\t".  
        "prod_downtime_mech(min)" . "\t"."prod_downtime_mech(HR)" ."\t"."total_mech_bypass" . "\t"."total_mech_bypass_prod(%)" . "\t".  
        "prod_downtime_elec(min)" . "\t"."prod_downtime_elec(HR)" ."\t"."total_elec_bypass" . "\t"."total_elec_bypass_prod(%)" . "\t".  
        "prod_downtime_passchange(min)" . "\t"."prod_downtime_passchange(HR)" ."\t"."total_passchange_bypass" . "\t"."total_passchange_bypass_prod(%)" . "\t".  
        "prod_downtime_sizechange(min)" . "\t"."prod_downtime_sizechange(HR)" ."\t"."total_sizechange_bypass" . "\t"."total_sizechange_bypass_prod(%)" . "\t".  
        "prod_downtime_maintenance(min)" . "\t"."prod_downtime_maintenance(HR)" ."\t"."total_maintenance_bypass" . "\t"."total_maintenace_bypass_prod(%)" . "\t".    
        "prod_downtime_mpeb(min)" . "\t"."prod_downtime_mpeb(HR)" ."\t"."total_mpeb_bypass" . "\t"."total_mpeb_billets_bypass_prod(%)" . "\t".
        "prod_downtime_lahar(min)" . "\t"."prod_downtime_lahar(HR)" ."\t"."total_lahar_bypass" . "\t"."total_lahar_billets_bypass_prod(%)" . "\t".
        "prod_downtime_crack(min)" . "\t"."prod_downtime_crack(HR)" ."\t"."total_crack_bypass" . "\t"."total_crack_billets_bypass_prod(%)" . "\t".
        "prod_downtime_piping(min)" . "\t"."prod_downtime_piping(HR)" ."\t"."total_piping_bypass" . "\t"."total_piping_billets_bypass_prod(%)" . "\t".
        "prod_downtime_mouthopen(min)" . "\t"."prod_downtime_mouthopen(HR)" ."\t"."total_mouthopen_bypass" . "\t"."total_mouthopen_billets_bypass_prod(%)" . "\t".
        "prod_downtime_millchill(min)" . "\t"."prod_downtime_millchill(HR)" ."\t"." total_millchill_bypass" . "\t"."total_millchill_billets_bypass_prod(%)" . "\t".
        "prod_downtime_ccd_composition(min)" . "\t"."prod_downtime_ccd_composition(HR)" ."\t". "total_ccd_composition_bypass" . "\t"."total_ccd_composition_billets_bypass_prod(%)". "\t".
        "prod_downtime_furnace(min)" . "\t"."prod_downtime_furnace(HR)" ."\t". "total_furnace_bypass" . "\t"."total_furnace_billets_bypass_prod(%)". "\t".
        "prod_downtime_ccm(min)" . "\t"."prod_downtime_ccm(HR)"      ."\t"."total_ccm_billets_bypass_prod(%)". "\t". "total_ccm_bypass" ."\t".
       "total_bilets_bypass_prod_3rdstand(%)" ."\t".
        "prod_downtime_contractor(min)" . "\t"."prod_downtime_contractor(HR)" ."\t". "total_contractor_bypass" . "\t"."total_contractor_billets_bypass_prod(%)". "\t".
        "total_mill_bypass(mill,mechanical_elec)" . "\t"."total_mill_billets_bypass_prod(%)"."\t".
         "mill_missroll_from_ccm(%)" . "\t"."water_consumption_mill" . "\t". "per_ton_water_consumption_mill" . "\t"."water_consumption_tmt"."\t".
        "per_ton_water_consumption_tmt" . "\t"."hotrolling_availability" . "\t". "mechanical_avaialability" . "\t"."electrical_availability"."\t". 
         "Cooling_bed_endcut_missroll_wt" . "\t"."end_cut_missroll_wt" . "\t". "per_cooling_bed_mr_wt(%)" . "\t"."per_endcut_mr_wt(%)"."\t".
"tmt_out_of_grade_isi(kg)" . "\t"."tmt_out_weight_isi(kg)" . "\t". "per_isi_acc_grade(%)" . "\t"."per_isi_acc_weight(%)"."\t".
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
header("Content-Disposition: attachment; filename=RollingKPI.xls");
header("Pragma: no-cache");
header("Expires: 0");

echo ucwords($columnHeader) . "\n" . $setData . "\n";
?> 