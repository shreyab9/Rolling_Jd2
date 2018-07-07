<?php



session_start();

require_once("..\Connection.php");

  
 extract($_GET);
 
 $_SESSION['id']=$id;
 echo"<br>";


 $select= mysqli_query($link, "select * from jd2_rolling_breakdown  where  `breakdown_id`='$id' ");
 if(!$select){
       die(mysqli_error($link));
       exit();
   }
 $fetch= mysqli_fetch_array($select); 

 $dept_id=$fetch['bd_start_time'];
$bd_st_time=date('m/d/Y h:i A',strtotime(str_replace('/','-', $fetch['bd_start_time'])));
$bd_end_time=date('m/d/Y h:i A',strtotime(str_replace('/','-', $fetch['bd_end_time'])));
?>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <link rel="shortcut icon" type="image/png"  href="../favicon.png"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                <title>JD-2 Breakdown Detail</title>
                <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

                <script src="js/refreshform.js"></script>
                <link rel="stylesheet" type="text/css" href="view.css" media="all">

                    <!-- Load jQuery from Google's CDN -->

                    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">

                        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
                        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
                        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>  


                            <link href="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
                                <script language="javascript" src="http://ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular.js"></script>
                                <script language="javascript" src="http://ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular-animate.js"></script>
                                <script language="javascript" src="http://angular-ui.github.io/bootstrap/ui-bootstrap-tpls-0.14.3.js"></script>


                                <link rel="stylesheet" href="./css/bootstrap.min.css" />
                                <link rel="stylesheet" href="./css/bootstrap-datetimepicker.min.css" />
                                <link rel="stylesheet" href="./css/font-awesome-min.css" />
                                <script type="text/javascript" src="../js/jquery.min.js"></script>
                                <script type="text/javascript" src="../js/moment.min.js"></script>
                                <script type="text/javascript" src="../js/bootstrap.min.js"></script>
                                <script type="text/javascript" src="../js/bootstrap-datetimepicker.min.js"></script>                
                                <script type="text/javascript" src="./BDValidations.js"></script>
                                     <!-- VALIDATIONS OF THE TEXT BOX FIELD  -->
                                
                                 
                                     <script type="text/javascript">
                                     function fillUpdateElements(){
                                  
                                    
                                         var millSize1 = document.getElementById('mill1size');
                                         for (var i = 1; i < millSize1.options.length-1; i++) {
                                          
                                            if (parseInt(millSize1.options[i].value) === parseInt("<?php echo $fetch['mill_1_size']?>")) {
                                        
                                                
                                                
                                              millSize1.selectedIndex = i;
                                                break;
                                            }
                                        if("<?php echo $fetch['mill_1_size']?>" === 1){
                                            millSize1.selectedIndex=millSize1.options.length-1;
                                            
    }                                       
}      
                                         


                                       var millSize2 = document.getElementById('mill2size');
                                         for (var j = 1; j < millSize2.options.length-1; j++) {
                                            
                                            if (parseInt(millSize2.options[j].value) === parseInt("<?php echo $fetch['mill_2_size']?>")) {
                                            
                                           millSize2.selectedIndex = j;
                                                break;
                                            }
                                        
                                        if("<?php echo $fetch['mill_2_size']?>" === 1){
                                            millSize2.selectedIndex=millSize2.options.length-1;
                                            
    }
    
                                        
}      




                                     var dept = document.getElementById('dept');
                                  for (var a = 1; a < dept.options.length; a++) {
                      
                              if (parseInt(dept.options[a].value) === parseInt(<?php echo $fetch['dept_id']?>)) {
                                                dept.selectedIndex=a;
 
                                                            break;
                                            }
                                        
                                        
                                     }
                                     var loc_code = document.getElementById('loc_code');
                                    
                                         for (var b = 0; b < loc_code.options.length; b++){
                                            if (parseInt(loc_code.options[b].value) === parseInt(<?php echo $fetch['location_id']?>)) {
                                                loc_code.selectedIndex=b;
                                                            break;
                                            }
                                        
                                        
                                     }
                                     var res_person = document.getElementById('resp_person');
                               
                                           for (var c = 0; c < res_person.options.length; c++) {
                                            if (parseInt(res_person.options[c].value) === parseInt(<?php echo $fetch['resp_per_id']?>)) {
                                                res_person.selectedIndex=c;
                                                            break;
                                            }
                                        
                                        
                                     }
                                      var shift_formen = document.getElementById('shift_formen');
                                    
                                              for (var d = 0; d < shift_formen.options.length; d++) {
                                            if (parseInt(shift_formen.options[d].value) === parseInt(<?php echo $fetch['shift_foreman_id']?>)) {
                                                shift_formen.selectedIndex=d;
                                                      break;
                                            }
                                        
                                        
                                     }
                                      var reason_code = document.getElementById('reason_code');

                                                  for (var e = 0; e < reason_code.options.length; e++) {
                                            if (parseInt(reason_code.options[e].value) === parseInt(<?php echo $fetch['reason_id']?>)) {
                                                reason_code.selectedIndex=e;
 
                                                            break;
                                            }
                                        
                                        
                                     }
                                     var stand = document.getElementById('stand');
                               
                                                  for (var f = 0; f <stand.options.length; f++) {
                                            if (parseInt(stand.options[f].value) === parseInt(<?php echo $fetch['stand']?>)) {
                                                stand.selectedIndex=f;
 
                                                            break;
                                            }
                                        
                                        
                                     }
                                     
                                     
                                     
                                     var shift=  document.getElementById('shift_id');
                                     
                                                  for (var h = 1; h < shift.options.length; h++){
                                                      
                                            if (shift.options[h].text === ("<?php echo $fetch['shift']?>")) {
                                                shift.selectedIndex=h;
 
                                                            break;
                                            }
                                        
                                        
                                     }
                                      var heat_no = document.getElementById('hnumber');
                                    
                                                  for (var g = 1; g < heat_no.options.length; g++) {
                                            if (parseInt(heat_no.options[g].value) === parseInt("<?php echo $fetch['heat_number']?>")) {
                                                heat_no.selectedIndex=g;
 
                                                            break;
                                            }
                                        
                                        
                                     }
                                     


      
                                     var date1 = new Date(<?php echo strtotime($fetch['bd_date'])?> * 1000);
                                     var curr_date = date1.getDate();
                                     var monthNames = [
                                        "01", "02", "03",
                                        "04", "05", "06", "07",
                                        "08", "09", "10",
                                        "11", "12"
                                      ];
                                        var curr_month_index = date1.getMonth();
                                        var curr_year = date1.getFullYear();
                                    document.getElementById('dateId').value = curr_date+"/"+monthNames[curr_month_index]+"/"+curr_year;
                                 
                                     }
                                     function parseDate(input) {
                                        var parts = input.split('-');
                                        // new Date(year, month [, day [, hours[, minutes[, seconds[, ms]]]]])
                                        return new Date(parts[0], parts[1]-1, parts[2]); // Note: months are 0-based
                                      }
									                             
                                      
                                     </script>
                                </head>
                                <body id="main_body"  style="Padding:20px;" onload="fillUpdateElements()">
                                    <img id="top" src="top.png" alt="">
                                        <div id="form_container">

                                        
                                           <form id="form_28072" class="appnitro"  method="post" action="Addbreakdown.php" onsubmit="return onFormSubmit(); return confirm('Are you sure you want to submit this form?');">
                                                <div class="form_description">
    <h1>JD-2 Rolling Breakdown </h1>
                                                    <h2>JD-2 Rolling Breakdown Form </h2>
                                                       <p> <a href="http://dataapp/Rolling_jd2/Home.php"> Home </a> </p>
                                                </div>						
                                                <ul >
                                                    <li id="li_1" >
                                                        <div ng-app="myApp" ng-controller="myCntrl"> 

                                                            <label class="description" for="element_1">Date </label>
                                                            <div>

                                                                <input type="text" uib-datepicker-popup="{{dateformat}}" ng-model="dt" is-open="showdp" name="date" id="dateId" required/>
                                                                <span>  
                                                                    <button type="button" class="btn btn-default" ng-click="showcalendar($event)">
                                                                        <i class="glyphicon glyphicon-calendar"></i>
                                                                    </button>
                                                                </span>
                                                            </div>
                                                            <script language="javascript">
                                                                        angular.module('myApp', ['ngAnimate', 'ui.bootstrap']);
                                                                        angular.module('myApp').controller('myCntrl', function ($scope) {
                                                                            $scope.today = function () {
                                                                                $scope.dt = new Date();
                                                                            };
                                                                            $scope.dateformat = "dd/MM/yyyy";
                                                                            $scope.today();
                                                                            $scope.showcalendar = function ($event) {
                                                                                $scope.showdp = true;
                                                                            };
                                                                            $scope.showdp = false;
                                                                           $scope.dtmax = new Date();
                                                                        });

                                                            </script>

                                                        </div> 

                                                    </li>
                                                    
                                                    
                                                    <li>
                                                        <input type="hidden" name="bd_id" value="<?php  echo $id; ?>" />
                                              
                                                    </li>
                                                  	<li id="li_15" >
                                                        <label class="description" for="element_15">Shift<span class="required">*</span> </label>
                                                        <div>
                                                            <select class="element select medium" id="shift_id" name="shift"  required/>
                                                        <option disabled selected value> -- select -- </option>
                                                        <option value="Day">Day </option>
                                                        <option value="Night">Night</option>
                                                          
                                                        </select>
                                                        </div>
                                                    </li>		<li id="li_16" >
                                                        <label class="description" for="element_16">Heat No <span class="required">*</span> </label>
                                                        <div>
                                                            <select class="element select medium" id="hnumber" name="hnumber"  required/>
                                                            <option disabled selected value> -- select -- </option>

                                                            <option value="1">1</option>
                                                            <option value="2">2</option>
                                                            <option value="3">3</option>
                                                            <option value="4">4</option>
                                                            <option value="5">5</option>
                                                            <option value="6">6</option>
                                                            <option value="7">7</option>
                                                            <option value="8">8</option>
                                                            <option value="9">9</option>
                                                            <option value="10">10</option>
                                                            <option value="11">11</option>
                                                            <option value="12">12</option>
                                                            <option value="13">13</option>
                                                            <option value="14">14</option>

                                                            </select>
                                                        </div> 
                                                    </li>		
                                                    <li id="li_17">
                                                        <label class="description" for="element_17">Mill-1 Size<span class="required">*</span> </label>
                                                        <div>
                                                            <select class="element select medium" id="mill1size" name="m1size" required/>
                                                            <option disabled selected value> -- select-- </option>
                                                            <?php
// mysql select query
                                                            $q2 = "SELECT * FROM `jd2_rolling_size` order by size_id asc";

// for method 1
                                                            $r2 = mysqli_query($link, $q2);
                                                            ?>
                                                            <?php while ($row1 = mysqli_fetch_array($r2)): ?>
                                                                <option value="<?php echo $row1[0]; ?>"><?php echo $row1[1]; ?></option>
<?php endwhile; ?>

                                                            </select>
                                                        </div> 
                                                    </li>		<li id="li_18" >
                                                        <label class="description" for="element_18">Mill-2 Size<span class="required">*</span> </label>
                                                        <div>
                                                            <select class="element select medium" id="mill2size" name="m2size" required/>
                                                         <option disabled selected value> -- select-- </option>
                                                            <?php
// mysql select query
                                                            $q3 = "SELECT * FROM `jd2_rolling_size`order by size_id asc";
// for method 
                                                            $r3 = mysqli_query($link, $q3 );
                                                            ?>
                                                            <?php while ($row1 = mysqli_fetch_array($r3)): ?>
                                                                <option value="<?php echo $row1[0]; ?>"><?php echo $row1[1]; ?></option>
<?php endwhile; ?>
                                                            </select>
                                                        </div> 
                                                    </li>	
                                                    <li id="li_2">
                                                        <label class="description" for="element_111">Start time<span class="required">*</span> </label>
                                                     <!-- <div class="container"> 
                                                          <!--  <div class='col-md-5'> -->
                                                                <div class="form-group">
                                                                    <div class='input-group date' id='datetimepicker6'>
                                                                        <input type="text" name ="start_time" id="starttime" value="<?php echo $bd_st_time; ?>" required/>
                                                                        <span class="input-group-addon">
                                                                            <span class="glyphicon glyphicon-calendar"></span>
                                                                        </span>
                                                                        
                                                                 
                                                                    </div>
                                                             
                                                                   </div>
                                                             <!--</div>-->
                                                           
                                                            
                                                            <label class="description" for="element_1121">End time<span class="required">*</span> </label>
                                                          <!-- <div class='col-md-5'> -->
                                                           <div class="form-group">
                                                                    <div class='input-group date' id='datetimepicker7'>
                                                                        <input type="text"  name ="end_time" id="endtime" onblur="checkIfSameDate()" value="<?php echo $bd_end_time;?>" required/>
                                                                        <span class="input-group-addon">
                                                                            <span class="glyphicon glyphicon-calendar"></span>
                                                                            </span> 
                                                                     
                                                                   </div>
                                                             </div>
                                                           
                                                         <!--   </div>--> 
                                                    <!--    </div>-->
                                                        
                                                        <!-- Hidden Input type for the -->
                                                        
                                                        <input type='hidden' name ="sTime_only" id="sTime_only"/>
                                                        <input type='hidden' name ="eTime_only" id="eTime_only"/>
                                                        
                                                        <script type="text/javascript">
                                                                
                                                              $(function () {
                                                          
                                                               $('#datetimepicker6').datetimepicker();
                                                                $('#datetimepicker7').datetimepicker({
                                                                 useCurrent: false //Important! See issue #1075 
                                                                });
                                                                $("#datetimepicker6").on("dp.change", function (e) {
                                                                $('#datetimepicker7').data("DateTimePicker").minDate(e.date);
                                                                });
                                                                $("#datetimepicker7").on("dp.change", function (e) {
                                                                $('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
                                                                });
                                                               
                                                                    });



  </script>
                                                        <li id="li_19" >
                                                            <label class="description" for="element_19">Stand <span class="required">*</span> </label>
                                                            <div>
                                                                <select class="element select medium" id="stand" name="stand" required> 
                                                                   <option disabled selected value> -- select an option -- </option>
                                                                  
                                                                          <option disabled selected value> -- select an option -- </option>
                                                                    <option value="1" >1</option>
                                                                    <option value="2" >2</option>
                                                                    <option value="3" >3</option>
                                                                    <option value="4" >4</option>
                                                                    <option value="5" >5</option>
                                                                    <option value="6" >6</option>

                                                              
                                                                </select>
                                                            </div> 
                                                        </li>	


                                                        <li id="li_4" >
                                                            <label class="description" for="element_4">No. Of Dependent Miss Roll</label>
                                                            <div>
                                                                <input id="element_4" name="dependent_mr"  type="text" maxlength="6" value="<?php echo $fetch['dep_missroll'];?>" />
                                                                   

                                                            </div> 
                                                           
                                                        </li>		
                                                        
                                                        <li id="li_5" >
                                                            <label class="description" for="element_5">No. Of InDependent Miss Roll</label>
                                                            <div>
                                                                <input id="indep_mr" name="independent_mr"  type="number" min="0" maxlength="6" value="<?php echo $fetch['indep_missroll'];?>" />
                                                                
                                                            </div> 
                                                        
                                                         
                                                        </li>		
                                                                                         

                                                        <li id="li_6" >
                                                            <label class="description" for="element_6">No. Of Cutting </label>
                                                            <div>
                                                                <input id="element_6" name="cutting"  type="number" min="0" maxlength="6" value="<?php echo $fetch['total_cutting'];?>" />
                                                               
                                                            </div> 
                                                             
                                                        </li>		<li id="li_7" >
                                                            <label class="description" for="element_7">Average 3 mt Billet Wt </label>
                                                            <div>
                                                                <input id="element_7" name="avg_3mtr" type="number" step="0.001" maxlength="6" min="0"  value="<?php echo $fetch['avg_3mtr_wt'];?>" />
                                                                
                                                            </div> 
                                                           
                                                        </li>		
                                                        
                                                        <li id="li_10" >
                                                            <label class="description" for="element_10">Average 6 mt Billet Wt</label>
                                                            <div>
                                                                <input id="element_10" name="avg_6mtr"  type="number" step="0.001" maxlength="6" min="0"  value="<?php echo $fetch['avg_6mtr_wt'];?>"  />
                                                                
                                                            </div> 
                                                           
                                                        </li>		
                                                        <li id="li_9" >
                                                            <label class="description" for="element_9">Number Of Billets BP Due To MR & BD (3 Mtr) </label>
                                                            <div>
                                                                <input id="element_9" name="bp_3mtr"  type="number" min="0" maxlength="6" value="<?php echo $fetch['total_3mtr_billets_bypass'];?>" />
                                                                
                                                            </div> 
                                                           
                                                        </li>
                                                        <li id="li_8" >
                                                            <label class="description" for="element_8">Number Of Billets BP Due To MR & BD (6 Mtr)</label>
                                                            <div>
                                                                <input id="element_8" name="bp_6mtr"  type="number"  maxlength="6" value="<?php echo $fetch['total_3mtr_billets_bypass']; ;?>"/>
                                                                
                                                            </div> 
                                                            
                                                        </li>			
                                                        <li id="li_20" >
                                                            <label class="description" for="element_20">Responsible Person<span class="required">*</span> </label>
                                                            <select class="element select medium" id="resp_person" name="responsible_person" value="<?php echo $fetch['resp_per_id'];?>" required/>

                                                            <option disabled selected value> -- select an option -- </option>
                                                            <?php
// mysql select query
                                                            $q4= "SELECT * FROM `jd2_rolling_responsible_person`";
// for method 
                                                            $r4 = mysqli_query($link, $q4);
                                                            ?>
<?php while ($row4 = mysqli_fetch_array($r4)): ?>

                                                                <option value="<?php echo $row4[0]; ?>"><?php echo $row4[1]; ?></option>
<?php endwhile; ?>
                                                            </select>
                                                        </li>
                                                        <li id="li_30" >
                                                            <label class="description" for="element_30">Location Code<span class="required">*</span> </label>
                                                            <select class="element select medium" id="loc_code" name="location_code" required/>
                                                            <option disabled selected value> -- select an option -- </option>
                                                            <?php
// mysql select query
                                                            $q5= "SELECT * FROM `jd2_rolling_location`";
// for method 1

                                                            $r5 = mysqli_query($link, $q5);
                                                            ?>
                                                            <?php while ($row5 = mysqli_fetch_array($r5)): ?>
                                                                <option value="<?php echo $row5[0]; ?>"><?php echo $row5[1]; ?></option>
<?php endwhile; ?>
                                                            </select>
                                                        </li>
                                                        <li id="li_21" >
                                                            <label class="description" for="element_21">Dept <span class="required">*</span> </label>
                                                            <div>
                                                                <select class="element select medium" id="dept" name="department" required/>
                                                                <option disabled selected value> -- select an option -- </option>
                                                                <?php
// mysql select query
                                                                $q6 = "SELECT * FROM `jd2_rolling_department`";
// for method 1
                                                                $r6 = mysqli_query($link, $q6);
                                                                ?>
                                                                <?php while ($row6 = mysqli_fetch_array($r6)):; ?>
                                                                    <option value="<?php echo $row6[0]; ?>"><?php echo $row6[1]; ?></option>
<?php endwhile; ?>

                                                                </select>
                                                            </div> 
                                                        </li>		<li id="li_22" >
                                                            <label class="description" for="element_22">Shift Formen<span class="required">*</span>  </label>
                                                            <div>
                                                                <select class="element select medium" id="shift_formen" name="shift_formen" required/>
                                                               <option disabled selected value> -- select an option -- </option>

                                                                <?php
// mysql select query
                                                                $q7 = "SELECT * FROM `jd2_rolling_responsible_person` where shift_foreman = true ";
// for method 1
                                                                $r7 = mysqli_query($link, $q7);
                                                                ?>
<?php while ($row7 = mysqli_fetch_array($r7)): ?>

                                                                    <option value="<?php echo $row7[0]; ?>"><?php echo $row7[1]; ?></option>

<?php endwhile; ?>

                                                                </select>
                                                            </div> 
                                                        </li>		<li id="li_23" >
                                                            <label class="description" for="element_23">Reason Code<span class="required">*</span> </label>
                                                            <div>
                                                                <select class="element select medium" id="reason_code" name="reasonid"    required/>
                                                                <option disabled selected value> -- select an option -- </option>

                                                                <?php
// mysql select query
                                                                $q8 = "SELECT * FROM `jd2_rolling_reason`";
// for method 1
                                                                $r8 = mysqli_query($link, $q8);
                                                                ?>
                                                                <?php while ($row8 = mysqli_fetch_array($r8)): ?>
                                                                    <option value="<?php echo $row8[0]; ?>"><?php echo $row8[1]; ?></option>
<?php endwhile; ?>
                                                                </select>
                                                            </div> 
                                                        </li>		<li id="li_11" >
                                                            <label class="description" for="element_11">BD Detail And Action Taken </label>
                                                            <div>
                                                                <textarea id="element_11" name="bd_action" class="element textarea small"><?php echo $fetch['bd_detail']?></textarea> 
                                                            </div> 
                                                        </li>

                                                        <li class="buttons">

<!--                                                            <input type="hidden" name="form_id" value="28072"/>-->
                                                            <input id="saveForm" class="button_text" type="submit" value="update"  name="update">
                                                        
                                                        </li>
                                                </ul>
                                                    
                                                        </form>	
                                            
         
                                            
                                                                     
                                            <div id="footer">
                                                Generated by <a href="http://moirasariya.com">Moirasariya</a>
                                            </div>
                                        </div>
                                        <img id="bottom" src="bottom.png" alt="">
                                            </body>
                                            </html>