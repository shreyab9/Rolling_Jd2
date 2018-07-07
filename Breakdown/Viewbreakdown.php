
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
         <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" type="text/css" href="../viewTable.css" media="all">
            

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script type="text/javascript">
   
        function breakdownDeleteSummary(primaryKey){
    if(confirm('Are you sure you want to delete this row?')){
            $.ajax({
                url: 'DeleteBreakdownSummary.php',
                type: 'post',
                async: false,
                data: {'action': 'deleteBreakdownSummary', 'primaryKeyForDelete': primaryKey},
                success: function (result) {
                     if(result > 0){
                         
                         alert('Breakdown deleted successfully');
                         location.reload();
                     }
                     else{
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


// Form to get the Breakdown Details in between from date and to date .
session_start();
include('..\Connection.php');

extract($_GET);
$date1 = date('Y-m-d', strtotime($date1));
$date2 = date('Y-m-d', strtotime($date2));


$_SESSION["date1"] = $date1;
$_SESSION["date2"] = $date2;
// Query to get the Details from the breakdown table on the basis of from date and to date .
if ($link != NULL) {
    //$res=  mysqli_query($con,"select location.locationid,location.locationname from location INNER JOIN breakdown ON location.locationid=breakdown.breakdown_id");
   $res = mysqli_query($link, "select b.bd_date,b.heat_number,
             s1.size_name,s2.size_name,b.shift,b.bd_start_time,
             b.bd_end_time,
             b.bd_total_time,
             b.bd_total_time_minutes,
             b.stand,
             b.dep_missroll,
             b.indep_missroll,
             b.total_missroll,
             b.total_cutting,
             b.total_3mtr_billets_bypass,
             b.total_6mtr_billets_bypass,
             b.total_billets_bypass,
             b.avg_3mtr_wt,
             b.avg_6mtr_wt,             
             r.reason_code,
             p.resp_per_name,
             ps.resp_per_name,
             l.location_code,
             d.dept_name,
             b.bd_detail,
             b.breakdown_id,b.breakdown_id
             
            from jd2_rolling_breakdown b , jd2_rolling_size s1 ,jd2_rolling_size s2,jd2_rolling_location l , 
            jd2_rolling_department d , jd2_rolling_reason r ,jd2_rolling_responsible_person p,jd2_rolling_responsible_person ps
            where b.mill_1_size = s1.size_id
            and b.mill_2_size = s2.size_id
            and b.location_id = l.location_id
            and b.dept_id= d.dept_id
             and b.reason_id = r.reason_id
             and b.resp_per_id= p.resp_per_id
             and b.shift_foreman_id=ps.resp_per_id
             and b.bd_date >= '$date1' and b.bd_date <= '$date2' order by b.bd_date, `heat_number` asc ");
      
   
     if(!$res) {
         die(mysqli_error($link));
     }
     echo"<table  class='test'>";
      echo  "<caption>JD2 Breakdown Details    
   <div id='button' align='right'>
   <a href='../home.php'>Home</a>
    <div id='button' align='right'>
   <a href='Breakdown_Excel.php'> Export To Excel</a>
   
   
 <caption>";
   
   $num_row=mysqli_num_rows($res);
   if($num_row === 0){
       
          print "<div>There are no records found between dates $date1 and $date2 ."
           . " <br>   "
        . "Please Select Correct Dates."
        . "<br>"
        ."<a href='../home.php'>Click here to go Back.</a>"
        . "</div>";

   }
   
   else{
     
  
 
 
     

       echo "<tr><td> Breakdown_date  </td>"
        . "<td>Heat_Number </td>"
        . "<td> Mill_1_Size </td>"
        . "<td> Mill_2_Size </td>"
        . "<td> Shift</td>"
        . "<td>BD_Start_Time</td>"
        . "<td>BD_End_Time</td>"
        . "<td>BD_Total_Time</td>"
        . "<td>BD_Total_Time_Minutes</td>"     
        . "<td>Stand</td>"
        . "<td>Dependent_MR</td>"
        . "<td>Independent_MR</td>"
        . "<td>Total_MR</td>"
        . "<td>Cutting</td>"
        . "<td>3_MTR_BP</td>"
        . "<td>6_MTR_BP</td>"
        . "<td>Total_Billets_Bypass</td>"
        . "<td>Avg_3mtr_bwt</td>"
        . "<td>Avg_6mtr_bwt</td>"
        . "<td>Reason_Code</td>"
        . "<td>Responsible_Person</td>"
       . "<td>ShiftForeman</td>"       
        . "<td>Location_code</td>"
        . "<td>Department</td>"
        . "<td>BD_Detail</td>"
        . "<td colspan='2'>Action Taken </td>"
        ;


        //echo $date1;
   // echo $date2;
   /// $num= mysqli_num_rows($res);
    //echo $num;
 
    while ($row = mysqli_fetch_array($res)) {
        echo "<tr>";
        for ($i = 0; $i <25; $i++){
            echo "<td>" . $row[$i] . "</td>";
        }
        echo "<td><button id='".$row[25]."' type=\"button\" onclick=\"breakdownDeleteSummary(this.id);\"> Delete </button></td>";
        echo '<td><a href="UpdateBDForm.php?id='.$row['breakdown_id'].';"> <button type =\" button\" id=\"button\" class=\"submit\">Edit</button></a></td>';
    }
   }
    echo "</table>";
} else{
    echo "Not Connect";
}
?>
<br><br>

<!-- Exporting the Data to the excel sheet -->
