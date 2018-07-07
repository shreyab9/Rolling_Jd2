
<?php

session_start();
$date1 = $_SESSION["date1"];
$date2 = $_SESSION["date2"];


include('..\DBfile.php');
include('..\Connection.php');

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
             r.reason_code,
             p.resp_per_name,
             ps.resp_per_name,
             l.location_code,
             d.dept_name,
             b.bd_detail
            from jd2_rolling_breakdown b , jd2_rolling_size s1 ,jd2_rolling_size s2,jd2_rolling_location l , 
            jd2_rolling_department d , jd2_rolling_reason r ,jd2_rolling_responsible_person p,jd2_rolling_responsible_person ps
             where b.mill_1_size = s1.size_id
             and b.mill_2_size = s2.size_id
             and b.location_id = l.location_id
             and b.dept_id= d.dept_id
             and b.reason_id = r.reason_id
             and b.shift_foreman_id=ps.resp_per_id
             and b.resp_per_id= p.resp_per_id
             and b.bd_date >= '$date1' and b.bd_date <= '$date2' order by b.bd_date, `heat_number` asc");

if (!$res) {
    $printf = printf("Error: %s\n", mysqli_error($link));
   exit();}

//$setRec = mysqli_query($conn, $res);  

$columnHeader = '';
$columnHeader = "Date" . "\t" .
        "Heat Number" . "\t" .
        "Mill-1 Size" . "\t" .
        "Mill-2 Size" . "\t" .
        "Shift" . "\t" .
        "BD Start Time" . "\t" .
        "BD End time" . "\t" .
        "BD Total Time" . "\t" .
        "BD Total Time(in Min)" . "\t" .
        "Stand" . "\t" .
        "Dependent MR" . "\t" .
        "In Dependent MR" . "\t" .
        "Total MR" . "\t" .
        "Cutting" . "\t" .
        "3 MTR BP" . "\t" .
        "6 MTR BP" . "\t" .
        "Total Number of BP" . "\t" .
        "Reason Code" . "\t" .
        "Responsible Person" . "\t" .
        "Shift Foreman" . "\t" .
        "Location_code" . "\t" .
        "Department" . "\t" .
        "BD Detail" . "\t";

$setData = '';


while ($rec = mysqli_fetch_row($res)) {
    $rowData = '';
    foreach ($rec as $value) {
        $value = '"' . $value . '"' . "\t";
        $rowData .= $value;
    }
    $setData .= trim($rowData) . "\n";
}


header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Breakdown_excel.xls");
header("Pragma: no-cache");
header("Expires: 0");

echo ucwords($columnHeader) . "\n" . $setData . "\n";
?> 