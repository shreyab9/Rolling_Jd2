
$(document).ready(function () {
    // start time initialize

    $('.timepicker1').datetimepicker({
        format: 'LT'
    }).on('dp.change', function (e) {
        var endTimeValue = $('.timepicker2').data("DateTimePicker").viewDate();

        // setting min and max time 
        $('.timepicker1').data("DateTimePicker").minDate(moment().startOf('day'));
        $('.timepicker1').data("DateTimePicker").maxDate(moment().endOf('day'));

        var endTimeFieldValue = $('#end-time').val();

        // Check if start date is greater than end date
        if (e.date && endTimeFieldValue && e.date.isAfter(endTimeValue)) {
            $('.timepicker2').data("DateTimePicker").clear();
            alert('End time should be greater than start time !!');
        }


    });

    // end time initialize

    $('.timepicker2').datetimepicker({
        format: 'LT'
    }).on('dp.change', function (e) {
        var startTimeValue = $('.timepicker1').data("DateTimePicker").viewDate();
        var endTimeValue = $('.timepicker2').data("DateTimePicker").viewDate();

        var endTimeFieldValue2 = $('#end-time').val();
        var startTimeFieldValue2 = $('#start-time').val();

        // disbale time which is less than start time
        if (startTimeFieldValue2 && startTimeValue.isAfter(endTimeValue)) {
            var startTimeClone = startTimeValue.clone();
            $('.timepicker2').data("DateTimePicker").minDate(startTimeClone.add(1, 'minute'));
            $('.timepicker2').data("DateTimePicker").maxDate(startTimeClone.clone().endOf('day'));
            $('.timepicker2').data("DateTimePicker").clear();
        }

    });

});