/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var startDate;
$("#startdate").datetimepicker({
    timepicker: true,
    closeOnDateSelect: false,
    closeOnTimeSelect: true,
    initTime: true,
    format: 'd-m-Y H:m',
    minDate: 0,
    roundTime: 'ceil',
    onChangeDateTime: function (dp, $input) {
        startDate = $("#startdate").val();
    }
});
$("#enddate").datetimepicker({
    timepicker: true,
    closeOnDateSelect: false,
    closeOnTimeSelect: true,
    initTime: true,
    format: 'd-m-Y H:m',
    onClose: function (current_time, $input) {
        var endDate = $("#enddate").val();
        if (startDate > endDate) {
            alert('Please select correct date');
        }
    }
});