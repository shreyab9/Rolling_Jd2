
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
         <script type="text/javascript" src="./ValidationRollingKPI.js"></script>
              <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
         <link rel="stylesheet" type="text/css" href="../viewTable.css" media="all">
             <meta name="viewport" content="width=device-width, initial-scale=1">
            
                 
                 </head>
</html>
<?php






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

    $sql_kpirpt = "SELECT  `kpi_date`, `heat_count`, `gross_tmt`, `total_ccm_prod`, `total_heat_time`,`heat_run_time`,`heat_gap`, `per_ton_power_units`, 
        `net_8mm_prod`, `net_10mm_prod`, `net_12mm_prod`, `net_16mm_prod`, `net_20mm_prod`, `net_25mm_prod`, `net_28mm_prod`, `net_32mm_prod`, 
         `net_tmt`,`total_rolling_pieces`, `total_ccm_pieces`, `24hrshotrolling`, `monthlyhotrolling`, `monthly_net_tmt`, `endcut_missroll_perc`, 
        `missroll_percent`, `mill_missroll_percent`, `total_missroll`, `total_mill_missroll`, `total_elec_missroll`, `total_mech_missroll`, 
        `total_ccm_missroll`, `total_furnace_missroll`, `total_mpeb_missroll`, `total_indep_missroll`, `total_dep_missroll`,`kpi_id`
          from jd2_rolling_kpi_24hrs  where `kpi_date`>= '$date1' and `kpi_date` <= '$date2' order by `kpi_date`";
$res_KPI = mysqli_query($link,  $sql_kpirpt)  or die(mysqli_error($link));

  echo"<table  class='test'>";
      echo  "<caption>JD2 24hrs Production Report 
   <div id='button' align='right'>
   <a href='../home.php'>Home</a>
    <div id='button' align='right'>
   <a href='RollingKPIExcel.php'>  ExportTo Excel</a> 
    <caption>";
      
      $count=mysqli_num_rows($res_KPI);
      if($count==0){
          
          print "<div>There are no records found between dates $date1 and $date2 ."
           . " <br>   "
        . "Please Select Correct Dates."
        . "<br>"
        ."<a href='../home.php'>Click here to go Back.</a>"
        . "</div>";

      }
      else{
      
    echo "<tr><td>KPI_Prod_Date</td>". "<td>Heat_count</td>". "<td>gross_tmt</td>"
    . "<td>ccm_prod</td>" . "<td>total_heat_time </td>". "<td>heat_run_time </td>". "<td>heat_gap </td>"  ."<td>per_ton_consumption</td>".
            "<td>net_8mm_prod</td> " . "<td>net_10mm_prod</td> " . "<td>net_12mm_prod</td> ". "<td>net_16mm_prod</td> "
    . "<td>net_20mm_prod</td> " . "<td>net_25mm_prod</td> " . "<td>net_28mm_prod</td> " . 
            "<td>net_32mm_prod</td> "."<td>net_tmt</td> "."<td>total_rolled_pcs</td> " .
            "<td>total_ccm_pieces </td> "
    ."<td>24hrshotrolling</td> " ."<td>monhotrolling</td> " .
            "<td>mon_nettmt</td> " ."<td>total_endcut(%)</td> "
    ."<td>total_missroll(%)</td> " ."<td>total_mill_missroll(%)</td> "  .
            "<td>total_missroll</td> " ."<td>total_mill_missroll</td> "
    ."<td>total_elec_mr</td> "  ."<td>total_mech_mr</td> ".
            "<td>total_ccm_mr</td> " .
            "<td>total_fnce_mr</td> " ."<td>total_mpeb_mr</td> "            
           ."<td>indep_mr </td> " ."<td>dep_mr </td> "   . "<td>Action Taken</td>"; 
            

   
    
    
  
    $res_KPI = mysqli_query($link,  $sql_kpirpt)  or die(mysqli_error($link));
   /** echo $date1;
    echo $date2;
    echo "<br>";**/
   // $num_row = mysqli_num_rows($link,$res_perheat);
    //echo $num_row;
    while ($row_kpi = mysqli_fetch_array($res_KPI)) {
       // echo 'in while';
        
        echo "<tr>";
        for ($i = 0; $i < 34; $i++) {
            echo "<td>" . $row_kpi[$i] . "</td>";
        }
        echo "<td><button id='".$row_kpi[34]."' type=\"button\" onclick=\"kpiRowDeleteSummary(this.id);\"> Delete </button></td>"; 
    }
      }
    echo "</table>";
}else {
    echo "Not Connect";
}
?>
<br><br>
