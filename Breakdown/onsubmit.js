// Below Function Executes On Form Submit
function ValidationEvent() {
// Storing Field Values In Variables
//start time
    var start_time = $("#start_time").val();

//end time
    var end_time = $("#end_time").val();

//convert both time into timestamp
    var stt = new Date($('#start_time').val());
    stt = stt.getTime();

    var endt = new Date($('#end_time').val());
    endt = endt.getTime();

//by this you can see time stamp value in console via firebug
    console.log("Time1: " + stt + " Time2: " + endt);

    if (stt > endt) {
        $("#start_time").after('<span class="error"><br>Start-time must be smaller then End-time.</span>');
        $("#end_time").after('<span class="error"><br>End-time must be bigger then Start-time.</span>');
        return false;
    }
}