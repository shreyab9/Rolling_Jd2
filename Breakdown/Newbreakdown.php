                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



// php select option value from database

include('..\Connection.php');

//extract($_GET);
//$date=$_SESSION["date"];
//echo $_SESSION["date"];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
                           
                                
                                </head>
                                <body id="main_body"  style="Padding:20px;">
                                    <img id="top" src="top.png" alt="">
                                        <div id="form_container">

                                            <h1><a>JD-2 Breakdown Detail</a></h1>
                                            
                                                <form id="form_28072" class="appnitro"  method="post" action="Addbreakdown.php" onsubmit="return onFormSubmit(); return confirm('Are you sure you want to submit this form?');">
                                                <div class="form_description">

                                                    <h2>JD-2 Breakdown Detail</h2>
                                                   <p> <a href="http://dataapp/Rolling_Jd2/Home.php"> Home </a> </p>
                                                </div>						
                                                <ul >
                                                    <li id="li_1" >
                                                        <div ng-app="myApp" ng-controller="myCntrl"> 

                                                            <label class="description" for="element_1">Date </label>
                                                            <div>

                                                                <input type="text" uib-datepicker-popup="{{dateformat}}" ng-model="dt" is-open="showdp" max-date="dtmax" name="date" required/>
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
                                                  	<li id="li_15" >
                                                        <label class="description" for="element_15">Shift<span class="required">*</span> </label>

                                                        <select class="element select medium" id="element_15" name="shift" required/>
                                                        <option disabled selected value> -- select -- </option>
                                                        <option>Day</option>
                                                        <option>Night</option>

                                                        </select>

                                                    </li>		<li id="li_16" >
                                                        <label class="description" for="element_16">Heat No <span class="required">*</span> </label>
                                                        <div>
                                                            <select class="element select medium" id="element_16" name="hnumber" required/>
                                                            <option disabled selected value> -- select -- </option>

                                                            <option value="1" >1</option>
                                                            <option value="2" >2</option>
                                                            <option value="3" >3</option>
                                                            <option value="4" >4</option>
                                                            <option value="5" >5</option>
                                                            <option value="6" >6</option>
                                                            <option value="7" >7</option>
                                                            <option value="8" >8</option>
                                                            <option value="9" >9</option>
                                                            <option value="10" >10</option>
                                                            <option value="11" >11</option>
                                                            <option value="12" >12</option>
                                                            <option value="13" >13</option>
                                                            <option value="14" >14</option>
                                                             <option value="15" >15</option>
                                                              <option value="16" >16</option>

                                                            </select>
                                                        </div> 
                                                    </li>		
                                                    <li id="li_17">
                                                        <label class="description" for="element_17">Mill-1 Size<span class="required">*</span> </label>
                                                        <div>
                                                            <select class="element select medium" id="element_17" name="m1size" required/>
                                                            <option disabled selected value> -- select-- </option>
                                                            <?php
// mysql select query
                                                            $query = "SELECT * FROM `jd2_rolling_size` ORDER BY size_id ASC";

// for method 1
                                                            $result1 = mysqli_query($link, $query);
                                                            ?>
                                                            <?php while ($row1 = mysqli_fetch_array($result1)):; ?>
                                                                <option value="<?php echo $row1[0]; ?>"><?php echo $row1[1]; ?></option>
<?php endwhile; ?>

                                                            </select>
                                                        </div> 
                                                    </li>		<li id="li_18" >
                                                        <label class="description" for="element_18">Mill-2 Size<span class="required">*</span> </label>
                                                        <div>
                                                            <select class="element select medium" id="element_18" name="m2size" required/>
                                                         <option disabled selected value> -- select-- </option>
                                                            <?php
// mysql select query
                                                            $query = "SELECT * FROM `jd2_rolling_size` ORDER BY size_id ASC";
// for method 
                                                            $result1 = mysqli_query($link, $query);
                                                            ?>
                                                            <?php while ($row1 = mysqli_fetch_array($result1)):; ?>
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
                                                                        <input type="text" name ="start_time" id="starttime" required/>
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
                                                                        <input type="text"  name ="end_time" id="endtime" onblur="checkIfSameDate()" required/>
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
                                                                <select class="element select medium" id="element_19" name="stand"> 
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
                                                                <input id="element_4" name="dependent_mr"  type="number" maxlength="6" min="0" />
                                                                   

                                                            </div> 
                                                            
                                                        </li>		
                                                        
                                                        <li id="li_5" >
                                                            <label class="description" for="element_5">No. Of InDependent Miss Roll</label>
                                                            <div>
                                                                <input id="element_5" name="independent_mr"  type="number" maxlength="6" min="0" />
                                                                
                                                            </div> 
                                                        
                                                         
                                                        </li>		
                                                                                         

                                                        <li id="li_6" >
                                                            <label class="description" for="element_6">No. Of Cutting </label>
                                                            <div>
                                                                <input id="element_6" name="cutting"  type="number" maxlength="6" min="0" />
                                                               
                                                            </div> 
                                                            
                                                        </li>		<li id="li_7" >
                                                            <label class="description" for="element_7">Average 3 mt Billet Wt </label>
                                                            <div>
                                                                <input id="element_7" name="avg_3mtr" type="number" step="0.001" maxlength="6" min="0" />
                                                                
                                                            </div> 
                                                           
                                                        </li>		
                                                        
                                                        <li id="li_10" >
                                                            <label class="description" for="element_10">Average 6 mt Billet Wt</label>
                                                            <div>
                                                                <input id="element_10" name="avg_6mtr"  type="number" step="0.001" maxlength="6" min="0" />
                                                                
                                                            </div> 
                                                           
                                                        </li>		
                                                        <li id="li_9" >
                                                            <label class="description" for="element_9">Number Of Billets BP Due To MR & BD (3 Mtr) </label>
                                                            <div>
                                                                <input id="element_9" name="bp_3mtr"  type="number" min="0" maxlength="6" />
                                                                
                                                            </div> 
                                                           
                                                        </li>
                                                        <li id="li_8" >
                                                            <label class="description" for="element_8">Number Of Billets BP Due To MR & BD (6 Mtr)</label>
                                                            <div>
                                                                <input id="element_8" name="bp_6mtr"  type="number" min="0" maxlength="6"  />
                                                                
                                                            </div> 
                                                           
                                                        </li>			
                                                        <li id="li_20" >
                                                            <label class="description" for="element_20">Responsible Person<span class="required">*</span> </label>
                                                            <select class="element select medium" id="element_20" name="responsible_person" required/>

                                                            <option disabled selected value> -- select an option -- </option>
                                                            <?php
// mysql select query
                                                            $query1 = "SELECT * FROM `jd2_rolling_responsible_person`";
// for method 
                                                            $result2 = mysqli_query($link, $query1);
                                                            ?>
<?php while ($row2 = mysqli_fetch_array($result2)): ?>

                                                                <option value="<?php echo $row2[0]; ?>"><?php echo $row2[1]; ?></option>
<?php endwhile; ?>
                                                            </select>
                                                        </li>
                                                        <li id="li_30" >
                                                            <label class="description" for="element_30">Location Code<span class="required">*</span> </label>
                                                            <select class="element select medium" id="element_30" name="location_code" required/>
                                                            <option disabled selected value> -- select an option -- </option>
                                                            <?php
// mysql select query
                                                            $q1 = "SELECT * FROM `jd2_rolling_location`";
// for method 1

                                                            $r1 = mysqli_query($link, $q1);
                                                            ?>
                                                            <?php while ($r12 = mysqli_fetch_array($r1)): ?>
                                                                <option value="<?php echo $r12[0]; ?>"><?php echo $r12[1]; ?></option>
<?php endwhile; ?>
                                                            </select>
                                                        </li>
                                                        <li id="li_21" >
                                                            <label class="description" for="element_21">Department <span class="required">*</span> </label>
                                                            <div>
                                                                <select class="element select medium" id="element_21" name="department" required/>
                                                                <option disabled selected value> -- select an option -- </option>
                                                                <?php
// mysql select query
                                                                $q4 = "SELECT * FROM `jd2_rolling_department`";
// for method 1
                                                                $r4 = mysqli_query($link, $q4);
                                                                ?>
                                                                <?php while ($r42 = mysqli_fetch_array($r4)): ?>
                                                                    <option value="<?php echo $r42[0]; ?>"><?php echo $r42[1]; ?></option>
<?php endwhile; ?>

                                                                </select>
                                                            </div> 
                                                        </li>		<li id="li_22" >
                                                            <label class="description" for="element_22">Shift Formen<span class="required">*</span>  </label>
                                                            <div>
                                                                <select class="element select medium" id="element_22" name="shift_formen" required/>
                                                               <option disabled selected value> -- select an option -- </option>

                                                                <?php
// mysql select query
                                                                $q5 = "SELECT * FROM `jd2_rolling_responsible_person` where shift_foreman = true  ";
// for method 1
                                                                $r5 = mysqli_query($link, $q5);
                                                                ?>
<?php while ($r51 = mysqli_fetch_array($r5)): ?>

                                                                    <option value="<?php echo $r51[0]; ?>"><?php echo $r51[1]; ?></option>

<?php endwhile; ?>

                                                                </select>
                                                            </div> 
                                                        </li>		<li id="li_23" >
                                                            <label class="description" for="element_23">Reason Code<span class="required">*</span> </label>
                                                            <div>
                                                                <select class="element select medium" id="element_23" name="reasonid"    required/>
                                                                <option disabled selected value> -- select an option -- </option>

                                                                <?php
// mysql select query
                                                                $q11 = "SELECT * FROM `jd2_rolling_reason`";
// for method 1
                                                                $r11 = mysqli_query($link, $q11);
                                                                ?>
                                                                <?php while ($row12 = mysqli_fetch_array($r11)): ?>
                                                                    <option value="<?php echo $row12[0]; ?>"><?php echo $row12[1]; ?></option>
<?php endwhile; ?>
                                                                </select>
                                                            </div> 
                                                        </li>		<li id="li_11" >
                                                            <label class="description" for="element_11">BD Detail And Action Taken </label>
                                                            <div>
                                                                <textarea id="element_11" name="bd_action" class="element textarea small"></textarea> 
                                                            </div> 
                                                        </li>

                                                        <li class="buttons">

<!--                                                            <input type="hidden" name="form_id" value="28072"/>-->
                                                            <input id="saveForm" class="button_text" type="submit" value="Submit">
                                                        
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