<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="shortcut icon" type="image/png"  href="../favicon.png"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Rolling Mill Power Report</title>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="view.css" media="all">
<script type="text/javascript" src="view.js"></script>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet"></link>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" rel="stylesheet"> 
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
                                <script type="text/javascript" src="./ValidationPowerReport.js"></script>
         
         </head>
    
<body id="main_body" >
	
	<img id="top" src="top.png" alt="">
	<div id="form_container">
	
		<h1><a>Rolling Mill Power Report</a></h1>
                <form id="powerreport" class="appnitro"  method="post" action="AddPowerReport.php" onsubmit="return duplicateDateCheck();">
					<div class="form_description">
			<h2>Rolling Mill Power Report</h2>
			<p>This is your form description. Click here to edit.</p>
                        <p> <a href="http://dataapp.moira.local/Rolling_Jd2/Home.php"> Home </a> </p>
		</div>	
                <ul >
                 <li id="li_100" >
		<label class="description" for="element_1">Date-Time<span class="required">*</span> </label>	 
              <div class="form-group">
                  <div class='input-group date' id='datetimepicker1'>
                      <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                    </span>
                       <input id="datetime" name="datetime"  type="text"  required /> 
              
                            </div>      
                        <script type="text/javascript">
            $(function () {
                $('#datetimepicker1').datetimepicker();
            });
        </script>
                    
              
                
                        </li>
					<li id="li_1" >
		<label class="description" for="element_1">Daily Mill Unit (MWH) <span class="required">*</span></label>
		<div>
	           <input id="element_1" name="dumwh"  type="number" step="0.001" min="0"  required/>   
		</div>

		</li>		
                 <li id="li_2" >
		<label class="description" for="element_2">Daily Mill Unit (MVAH)<span class="required">*</span> </label>
		<div>
			<input id="element_2" name="dumvah"  type="number" step="0.001" min="0"  required/> 
		          
                </div> 
                 
		</li>		
                <li id="li_3" >
		<label class="description" for="element_3">MVA<span class="required">*</span> </label>
		<div>
	<input id="element_3" name="mva" type="number" step="0.0001" min="0"  required/> 
		</div> 
                
		</li>		
                <li id="li_4" >
		<label class="description" for="element_4">P.F.<span class="required">*</span> </label>
		<div>
			<input id="element_4" name="pf"  type="number" step="0.0001" min="0"  required/> 
		</div>
                
		</li>		<li id="li_5" >
		<label class="description" for="element_5">Unit-3 </label>
		<div>
			<input id="element_5" name="unit3"  type="number" step="0.00001" min="0" /> 
		</div> 
                
		</li>	
                <li id="li_61" >
		<label class="description" for="element_6">CCD </label>
		<div>
			<input id="element_61" name="ccd"  type="number" step="0.0000001" min="0" /> 
		</div>
                 
                </li>
                <li id="li_6" >
		<label class="description" for="element_6">100-HP Blower </label>
		<div>
			<input id="element_6" name="100hpblower"  type="number" step="0.00001" min="0"  />
                 
		</li>		<li id="li_7" >
		<label class="description" for="element_7">Moira Furnace </label>
		<div>
			<input id="element_7" name="moirafnce"  type="number" step="0.00001" min="0"  /> 
		</div> 
               
		</li>		
                <li id="li_8" >
		<label class="description" for="element_8">Moira LT </label>
		<div>
			<input id="element_8" name="moiralt"  type="number" step="0.00001" min="0"  /> 
		</div> 
               
		</li>		
                <li id="li_9" >
		<label class="description" for="element_9">Bundling Press </label>
		<div>
			<input id="element_9" name="bundlingpress"  type="number" step="0.00001" min="0"  /> 
		</div> 
              
		</li>		
                <li id="li_10" >
		<label class="description" for="element_10">MWH Monthly </label>
		<div>
			<input id="element_10" name="mwhmu"  type="number" step="0.00001" min="0"  /> 
		</div> 
                
		</li>		<li id="li_11" >
		<label class="description" for="element_11">MVAH Monthly</label>
		<div>
	         <input id="element_11" name="mvahmu"  type="number" step="0.00001" min="0"  /> 
		</div> 
               
		</li>		
                <li id="li_12" >
		<label class="description" for="element_12">MVA Monthly </label>
		<div>
			<input id="element_12" name="mvamu"  type="number" step="0.00001" min="0" /> 
		</div> 
                
		</li>
			
					<li class="buttons">
			    <input type="hidden" name="form_id" value="53129" />
			    
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