<!--
javascript to delete the row from the database

here is using the program perheatDeletesummary.pphp page where we have written the program to delete the particular row from the database
-->
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
         <link rel="stylesheet" type="text/css" href="../viewTable.css" media="all">
             <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script type="text/javascript">
        
       function perheatDeleteSummary(perheatid){
          
    if(confirm('Are you sure you want to delete this row?')){
            $.ajax({
                url: 'perheatDeleteSummary.php',
                type: 'post',
                async: false,
                data: {'action': 'deletePerHeatSummary', 'perheatid': perheatid},
                success: function (result) {
                     if(result > 0){
                         //alert (result);
                         alert('Heat Number Deleted Successfully');
                         location.reload();
                     }
                     else{
                         alert(result);
                         alert('Issue in deleting Breakdown.');
                     }
                },
                error: function (xhr, desc, err) {
                    alert ('error');
                    console.log(xhr);
                    console.log("Details: " + desc + "\nError:" + err);
                }
        });
    }
}     
     
        
        </script>
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

    $sql_perheat = "select `per_heat_date`,`roughing`,
             s1.size_name,s2.size_name ,s3.size_name,s4.size_name,`heat_number`,`heat_start_time`,`heat_end_time`,
             `total_heat_time`,`total_bd_time`,`3mtrbilletwt_m1s1`,`rolledpcs_m1s1`,`3mtrbilletwt_m1s2`,`rolledpcs_m1s2`,`3mtrbilletwt_m2s1`,`rolledpcs_m2s1`,
             `3mtrbilletwt_m2s2`,`rolledpcs_m2s2`, `3stand_3mtr_billetsbypass`, `3stand_6mtr_billetsbypass`,
            `ccm_3mtr_billetsbypass`,`ccm_6mtr_billetsbypass`,`billets_bypass_only_ccm`,`total_missroll`,`cum_missroll`,
            `8mm_prod`,`10mm_prod`,`12mm_prod`,`16mm_prod`,`20mm_prod`,`25mm_prod`,`28mm_prod`,`32mm_prod`,
            `rollingprod`,`cum_rollingprod`,`ccmprod`,`cum_ccmprod`,`hotrolling`,`cum_hotrolling`,`perc_billets_bypass_mill`,
            `perc_billets_bypass_ccm`,`perc_billets_bypass_3stand`,`perc_billets_bypass_furnace`,`perc_billets_bypass_mpeb`,
            `perc_billets_bypass_contractor`,`perc_billets_bypass_other`,`per_heat_id`,`per_heat_id`  from jd2_rolling_per_heat_prod p,jd2_rolling_size s1,jd2_rolling_size s2,jd2_rolling_size s3,jd2_rolling_size s4
            where  s1.size_id=p.mill1_size1 
              and  s2.size_id=p.mill1_size2 
              and  s3.size_id=p.mill2_size1 
              and  s4.size_id=p.mill2_size2 
              and per_heat_date >= '$date1'
            and per_heat_date <= '$date2'
            order by  per_heat_date ,`heat_number` asc ";

    
    
    echo"<table  class='test'>";
      echo  "<caption>JD2 Per Heat Production    
   <div id='button' align='right'>
   <a href='../home.php'>Home</a>
    <div id='button' align='right'>
   <a href='PerheatExcel.php'> ExportToExcel </a> 
    <caption>";
      $res_perheat = mysqli_query($link, $sql_perheat)  or die(mysqli_error($link));
     $num_row= mysqli_num_rows($res_perheat);
     if($num_row==0)
      {
          print "<div>There are no records found between dates $date1 and $date2 ."
           . " <br>   "
        . "Please Select Correct Dates."
        . "<br>"
        ."<a href='../home.php'>Click here to go Back.</a>"
        . "</div>";

     }
     else{
    echo "<tr><td> PerHeat_Date </td>"
    . "<td>Roughing</td>"
    . "<td>Mill_1_size_1</td>"
    . "<td>Mill_1_size_2</td>"
    . "<td>Mill_2_size_1</td>"
    . "<td>Mill_2_size_2</td>"
    . "<td>Heat_Number</td>"
    . "<td>Heat_Start_time </td>"
    . "<td>Heat_End_Time</td>"
    . "<td>Total_heat_time </td>"
    . "<td>Total_BD_time </td>"
    . "<td>3mtrbwt(m1s1) </td>"       
   . "<td>rolledpcs(m1s1) </td>" 
     . "<td>3mtrbwt(m1s2) </td>"       
   . "<td>rolledpcs(m1s2) </td>"   
     . "<td>3mtrbwt(m2s1) </td>"       
   . "<td>rolledpcs(m2s1) </td>" 
    . "<td>3mtrbwt(m2s2) </td>"       
   . "<td>rolledpcs(m2s2) </td>"            
    . "<td>3st3mtrbbp</td>"
    . "<td>3st6mtrbbp</td>"
    . "<td>ccm3mtrbbp</td>"
    . "<td>ccm6mtrbbp</td>"
    . "<td>bbpurelyccm</td>"
    . "<td>missroll</td>"
    . "<td>Cum-missroll</td>"
    . "<td>8mm</td>"
    . "<td>10mm</td>"
    . "<td>12mm</td>"
    . "<td>16mm</td>"
    . "<td>20mm</td>"
    . "<td>25mm</td>"
    . "<td>28mm</td>"
    . "<td>32mm</td>"
    . "<td>rollingprod</td>"
    . "<td>cum-rollingprod</td>"
    . "<td>ccmprod</td>"
    . "<td>cum-ccmprod</td>"
    . "<td>hotrolling</td>"
    . "<td>cum-hotrolling</td>"
    . "<td>perbbpmill</td>"
    . "<td>perbbpccm</td>"
    . "<td>perbbp3st</td>"
    . "<td>perbbpfnce</td>"  
    . "<td>perbbpmpeb</td>"        
    . "<td>perbbpcontr</td>"
    . "<td>perbbpother</td>"
   . "<td colspan='2'>Action Taken </td>"       ;
   
   /** echo $date1;
    echo $date2;
    echo "<br>";**/
   // $num_row = mysqli_num_rows($link,$res_perheat);
    //echo $num_row;
    while ($row_perheat = mysqli_fetch_array($res_perheat)) {
       // echo 'in while';
        
        echo "<tr>";
        for ($i = 0; $i < 47; $i++) {
            echo "<td>" . $row_perheat[$i] . "</td>";
        }
      echo "<td><button id='".$row_perheat[47]."' type=\"button\" onclick=\"perheatDeleteSummary(this.id);\"> Delete </button></td>";  
     echo '<td><a href="UpdatePerHeatForm.php?id='.$row_perheat['per_heat_id'].';"> <button type =\" button\" id=\"button\" class=\"submit\">Edit</button></a></td>';
      
        } 
     }
    echo "</table>";
}else {
    echo "Not Connect";
}
?>
<br><br>
<!-- DOWNLOAD THE EXCEL FILE TO GET THE DATA ON THE BASIS OF THE SELECTED DATE    -->
 