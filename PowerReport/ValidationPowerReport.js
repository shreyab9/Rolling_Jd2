                   
       
   function duplicateDateCheck() {
   
    var readingDate = document.getElementById("datetime").value;
    var finalResult;
    $(document).ready(function () {
        $.ajax({
            url: 'DateCheckValidation.php',
            type: 'post',
            async: false,
            data: {'action': 'powerDateCheck', 'readingDate': readingDate},
            success: function (result) {
               //alert ('hi_a');
             //alert (result); 
              // alert('hi');
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
 
    return finalResult;
   } 
   
function deletePowerReportReading(PowerReportId){
    if(confirm('Are you sure you want to delete this row?')){
            $.ajax({
                url: 'DeletePowerReading.php',
                type: 'post',
                async: false,
                data: {'action': 'deletePowerReading', 'PowerReportId': PowerReportId},
                success: function (result) {
                     if(result > 0){
                         
                         alert('Power readings deleted successfully');
                         location.reload();
                     }
                     else{
                         alert('Issue in deleting Power Reading.');
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