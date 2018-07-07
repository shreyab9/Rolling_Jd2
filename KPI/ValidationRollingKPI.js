
                    
                              
                         
      function kpiDuplicateDateCheck() {
    //  alert ('in test Validation Function');
    var kpiDate = document.getElementById("kpidate").value;    
    
    //alert(kpiDate);
    var finalResult;
    $(document).ready(function () {
        $.ajax({
            url: 'KPIDateCheckValidations.php',
            type: 'post',
            async: false,
            data: {'action': 'kpiDateCheck', 'kpiDate': kpiDate},
            success: function (result) {
                if (result > 0) {
                    alert("Date is already exists ,Please check date and Enter Correct One");
                    finalResult = false;
                } else {
                    if (confirm('ARE YOU SURE YOU WANT TO SUBMIT THE FORM ?'))
                  
                    {
                        finalResult = true;
                         
                    } else {
                        finalResult = false;
                    }
                }
            },
            error: function (xhr, desc, err) {
                console.log(xhr);
                console.log("Details: " + desc + "\nError:" + err);
            }
        });
    });
   
   //alert('FinalResulpowerDateCheckt');
    return finalResult;
   }
                    
                    
   function kpiRowDeleteSummary(KpiId){
    if(confirm('Are you sure you want to delete this row?')){
                  $.ajax({
                url: 'KPIDeleteRow.php',
                type: 'post',
                async: false,
                data: {'action': 'DeleteKPIRow', 'KpiId': KpiId},
                success: function (result) {
                     if(result > 0){
                      
                         alert('Row Deleted Successfully');
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
                        