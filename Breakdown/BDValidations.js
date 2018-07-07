
             function checkIfSameDate() {
       
       var endTimeString = document.getElementById('endtime').value;
       var startTimeString = document.getElementById('starttime').value;
       var endDate = new Date(endTimeString);
       var startDate = new Date(startTimeString);
       if(endDate.getDate() === startDate.getDate() && endDate.getFullYear() === startDate.getFullYear() && endDate.getMonth() === startDate.getMonth()){
            document.getElementById('sTime_only').value = startDate.getHours() + ':' + ((startDate.getMinutes()<10?'0':'') + startDate.getMinutes());
            document.getElementById('eTime_only').value = endDate.getHours() + ':' + ((endDate.getMinutes()<10?'0':'') + endDate.getMinutes());
           
       }
       else
       {         
     document.getElementById('sTime_only').value = startDate.getDate()+ '/' + (startDate.getMonth()+1) + " "+ startDate.getHours() + ':' + ((startDate.getMinutes()<10?'0':'') + startDate.getMinutes());
       // document.getElementById('sTime_only').value = startDate.getDate()+ '/' + (startDate.getMonth()+1) + " "+ startDate.getHours() + ':' + startDate.getMinutes();
        document.getElementById('eTime_only').value = endDate.getDate()+ '/'+ (endDate.getMonth()+1) +" "+ endDate.getHours() + ':' + ((endDate.getMinutes()<10?'0':'') + endDate.getMinutes());
       
            }
 }

      function timediff(){
       var endTime = document.getElementById('endtime').value;
       var startTime = document.getElementById('starttime').value;
       var endDateTime = new Date(endTime);
       var startDateTime = new Date(startTime);
   
      if(endDateTime === startDateTime){
          alert('inside function');
           alert("Start Date Time and End Date Time Should Not be Same");
             return false;
        }
                   else{
            
                 return true;              
        }
        
   }                 
        
      function endDateValidations(){
       
      var today =new Date();
        var endTime_New = document.getElementById('endtime').value;
        var endDateTime_New = new Date(endTime_New);
         if (endDateTime_New > today){
            alert('End Date cannot be greater than todays date');
             return false;
         }
         else{
        //alert('enddatevalidation');-->
          return true;
         
         
        }
      
         }
         
         
         
          function startDateValidations(){
        //alert('endValidation1');
      var today =new Date();
        var starttime_New = document.getElementById('starttime').value;
        var StartDateTime_New = new Date(starttime_New);
         if (StartDateTime_New > today){
            alert('Start date cannot be greater than todays date');
             return false;
         }
         else{
        //alert('enddatevalidation');-->
          return true;
         
         
        }
      
         }
         
            
     function onFormSubmit(){
       var val1 = timediff();
       var val2 =startDateValidations();
       var val3 = endDateValidations();
       var val4 = confirm('Are you sure you want to submit this form?');
       if(val1 & val2 & val3 & val4){
      return true;
       }
       else{
       alert('please Correct the Dates and then Submit Form');
       return false;
       
       }
  } 
   