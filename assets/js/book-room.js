$(document).ready(function () {
    $('#startDate, #endDate').datepicker({
        clearBtn: true,
        format: "dd/mm/yyyy",
        orientation: 'right bottom',
        weekStart: 1
    });
});
