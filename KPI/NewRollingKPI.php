

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
         <link rel="shortcut icon" type="image/png"  href="../favicon.png"/>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                <title>KPI</title>
            <link rel="stylesheet" type="text/css" href="view.css" media="all">
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
                <script type="text/javascript" src="./js/jquery.min.js"></script>
                <script type="text/javascript" src="./js/moment.min.js"></script>
                <script type="text/javascript" src="./js/bootstrap.min.js"></script>
                <script type="text/javascript" src="./js/bootstrap-datetimepicker.min.js"></script> 
                <script type="text/javascript" src="./ValidationRollingKPI.js"></script>
                
                            </head>
                            <body id="main_body" >

                                <img id="top" src="top.png" alt="">
                                    <div id="form_container">

                                        <h1><a> JD2-Rolling Mill KPI</a></h1>
-                    <!--                <form id="form_55349" class="appnitro"  method="post" action="AddRollingKPI.php" onsubmit="return  confirm('Are you sure you want to submit this form?'); return onFormSubmit();" >-->
                                                       <form id="form_55349" class="appnitro"  method="post" action="AddRollingKPI.php" onsubmit="return  kpiDuplicateDateCheck();">
                                            <div class="form_description">
                                                <h2>JD2-Rolling Mill KPI</h2>
                                                <p>This is your form description. Click here to edit.</p>
                                                 <p> <a href="http://dataapp.moira.local/Rolling_Jd2/Home.php"> Home </a> </p>
                                            </div>						
                                            <ul >
                                                            <li id="li_1">
                                                        <div ng-app="myApp" ng-controller="myCntrl"> 

                                                            <label class="description" for="element_1">Date<span class="required">*</span> </label>
                                                            <div>

                                                                <input type="text" uib-datepicker-popup="{{dateformat}}" ng-model="dt" is-open="showdp" max-date="dtmax" name="kpidate" id="kpidate" required/>
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
 <form>
  <label for="8mmEndCutWt(inkg)">
    <span>8mm EndCut Wt(kg)</span>
    <input type="number" id="endcut8mm" name="endcut8mm" step="0.001" min="0"/>
  </label>
  <label for="10mmEndCutWt(inkg)">
    <span>10mm EndCut Wt(kg)</span>
    <input type="number" id="endcut10mm" name="endcut10mm" step="0.001" min="0"/>
  </label>
<label for="12mmEndCutWt(inkg)">
    <span>12mm EndCut Wt(kg)</span>
    <input type="number" id="endcut12mm" name="endcut12mm" step="0.001" min="0"/>
  </label>
  <label for="16mmEndCutWt(inkg)">
    <span>16mm EndCut Wt(g)</span>
    <input type="number" id="endcut16mm" name="endcut16mm" step="0.001" min="0"/>
  </label>
 <label for="20mmEndCutWt(inkg)">
    <span>20mm EndCut Wt(kg)</span>
    <input type="number" id="endcut20mm" name="endcut20mm"step="0.001" min="0" />
  </label>
  <label for="25mmEndCutWt(inkg)">
    <span>25mm EndCut Wt(kg)</span>
    <input type="number" id="endcut25mm" name="endcut25mm" step="0.001" min="0"/>
  </label>
      <label for="28mmEndCutWt(inkg)">
    <span>28mm EndCut Wt(kg)</span>
    <input type="number" id="endcut28mm" name="endcut28mm" step="0.001" min="0"/>
  </label>
  <label for="32mmEndCutWt(inkg)">
    <span>32mm EndCut Wt(kg)</span>
    <input type="number" id="endcut32mm" name="endcut32mm" step="0.001" min="0"/>
  </label>
     <label for="End Cut Missroll wt(inkg)">
    <span>EndCut Missroll Wt(kg)</span>
    <input type="number" id="endcutmrwt" name="endcutmrwt" step="0.001" min="0"/>
  </label>
  <label for="PerTonPower Consumption">
      <span>Per Ton Power Consumption</span>
    <input type="number" id="pertonpower" name="pertonpower" min="0" />
  </label>   
    <label for="CoolingBedEndCut(inkg)">
    <span>Cooling Bed EndCut(kg)</span>
    <input type="number" id="coolingbedendcut" name="coolingbedendcut" step="0.001" min="0"/>
  </label>
  <label for="MillEndCut(Gulli)">
    <span>Mill EndCut (Gulli kg)</span>
    <input type="number" id="millendcut" name="millendcut"step="0.001" min="0" />
  </label>
  
   
     
     <label for="Burnning Loss 8mm">
    <span>8mm Burning Loss(%)</span>
    <input type="number" id="bloss8mm" name="bloss8mm" step="0.01" min="0"/>
  </label>
  <label for="Burnning Loss 10mm">
      <span>10mm Burning Loss(%)</span>
    <input type="number" id="bloss10mm" name="bloss10mm" step="0.01" min="0"/>
  </label>   
      <label for="Burnning Loss 12mm">
    <span>12mm Burning Loss(%)</span>
    <input type="number" id="bloss12mm" name="bloss12mm"step="0.01" min="0" />
  </label>
  <label for="Burnning Loss 16mm">
      <span>16mm Burning Loss(%)</span>
    <input type="number" id="bloss16mm" name="bloss16mm" step="0.01" min="0"/>
  </label> 
          <label for="Burnning Loss 20mm">
    <span>20mm Burning Loss(%)</span>
    <input type="number" id="bloss20mm" name="bloss20mm" step="0.01" min="0" />
  </label>
  <label for="Burnning Loss 25mm">
      <span>25mm Burning Loss(%)</span>
    <input type="number" id="bloss25mm" name="bloss25mm" step="0.01" min="0" />
  </label> 
          <label for="Burnning Loss 28mm">
    <span>28mm Burning Loss(%)</span>
    <input type="number" id="bloss28mm" name="bloss28mm" step="0.01" min="0" />
  </label>
  <label for="Burnning Loss 32mm">
      <span>32mm Burning Loss(%)</span>
    <input type="number" id="bloss32mm" name="bloss32mm" step="0.01" min="0"/>
  </label> 
      <label for="PertonWater Consumption">
    <span>PerTon  Water Consump(MILL)</span>
    <input type="number" id="pertonwatermill" name="pertonwatermill" min="0" />
  </label>
  <label for="Per Ton Water Consumption">
      <span>PerTon  Water Consump(TMT)</span>
    <input type="number" id="pertonwatertmt" name="pertonwatertmt" min="0"/>
  </label> 
        <label for="ISI GradeWise ">
    <span>TMT Wt out of ISI Grade(kg)</span>
    <input type="number" id="isigradewise" name="isigradewise"  min="0"/>
  </label>
  <label for="ISI WeightWise">
      <span>TMT Wt out of ISI Weight(kg)</span>
    <input type="number" id="isiweightwise" name="isiweightwise"  min="0" />
  </label>  
     
       <li class="buttons">
                                             <input type="hidden" name="form_id" value="55349" />

                                          <input id="saveForm" class="button_text" type="submit" name="submit" value="Submit" />
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