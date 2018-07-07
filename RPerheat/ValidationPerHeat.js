
  
/**
 * VALIDATIONS TO AVOID DUPLICATE HEAT NUMBER and Heat Number Sequence
 */
 
 
 
isUpdate=true;
function timediff(isUpdate){
    //alert(isUpdate);
   //alert(true);
       var endTime = document.getElementById('endtime').value;
       var startTime = document.getElementById('starttime').value;
       var endDateTime = new Date(endTime);
       var startDateTime = new Date(startTime);
       
       var HeatFinalTime=  endDateTime - startDateTime;
       //alert (HeatFinalTime);
               
        
      if((HeatFinalTime > 9000000 )){
           alert("Total Heat Time  Cannot be Greater than 2:30 Hours Please Correct Heat End Time");
             return false;
             //alert ('in False Statement');
        }
        
        else if(HeatFinalTime===0){
            alert('Heat Start Time and End Time Cannot be Same');
            return false;
        }
              else{  
                 var val1= heatNumberCheck(isUpdate);
        if (val1) {
            return true;
        } else {
            return false;
        }
                 //var val2= seqHeatNumCheck();
                 //return true;   
           //alert ('in True Statement');   
              
        }
        
             
}
 function heatNumberCheck(isUpdateRequest) {
    
    var heatnumber = parseInt(document.getElementById("heatnumber").value,10);
    var perDate = document.getElementById("perheatdate").value;
   
    var finalResult;
    $(document).ready(function () {
        $.ajax({
            url: 'HeatNumValid.php',
            type: 'post',
            async: false,
            data: {'action': 'checkHeat', 'heatnumber': heatnumber, 'perDate': perDate},
            success: function (result) {
              
                if (result > 0 && !isUpdateRequest) {
                    alert("Heat Number   " + heatnumber + "  is already Present for the date "+ perDate);
                    alert("Please Enter New Heat Number");
                    finalResult = false;
                } else {
                    
                        finalResult = true;
                }
            },
            error: function (xhr, desc, err) {
                console.log(xhr);
                console.log("Details: " + desc + "\nError:" + err);
            }
        });
    });
    
    // alert('FinalResult');

if((finalResult)&&(heatnumber !== 1)){
       
    seqResult = seqHeatNumCheck();
    //alert(seqResult);
    }
    else{
       
        if (confirm('Are You Sure To Want To Submit the form?'))
                 {
                        seqResult = true;
                       
                         
                    } else {
                        seqResult = false;
                    }
    }
    finalResult = seqResult && finalResult;
    //alert('mainFinal');
    //alert(finalResult);
    return finalResult;
}

function seqHeatNumCheck() {
    //alert("in seqHeatNumCheck");
    var heatnumber = document.getElementById("heatnumber").value;
    var perDate = document.getElementById("perheatdate").value;
    heatnumber = heatnumber - 1;
    //alert('HeatNum');
    //alert(heatnumber);
    var finalResult;
    $(document).ready(function () {
        $.ajax({
            url: 'HeatNumValid.php',
            type: 'post',
            async: false,
            data: {'action': 'checkHeat', 'heatnumber': heatnumber, 'perDate': perDate},
            success: function (result) {
                if (result > 0) {
                    if (confirm('Are You Sure To Want To Submit the form?'))
                    {
                        finalResult = true;
                         //timediff(); 
                    } else {
                        finalResult = false;
                    }
                } else {
                        alert("Heat Number Not in Sequence. Please Enter Heatnumber :"+heatnumber+ "  for the Date  " +  perDate);
                        finalResult = false;
                }
            },
            error: function (xhr, desc, err) {
                console.log(xhr);
                console.log("Details: " + desc + "\nError:" + err);
            }
        });
    });
    return finalResult;
}



       function perheatDeleteSummary(PerHeatId){
          
    if(confirm('Are you sure you want to delete this row?')){
            $.ajax({
                url: 'PerheatDeleteSummary.php',
                type: 'post',
                async: false,
                data: {'action': 'deletePerHeatSummary', 'PerHeatId': PerHeatId},
                success: function (result) {
                     if(result > 0){
                         alert('Heat Number Deleted Successfully');
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
                        
   



      