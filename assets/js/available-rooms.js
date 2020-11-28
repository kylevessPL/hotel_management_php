$(document).ready(function () {
    $('#filter-start-date, #filter-end-date').datepicker({
        clearBtn: true,
        format: "dd/mm/yyyy",
        orientation: 'auto bottom',
        weekStart: 1
    });
    $("#filter-price-slider").ionRangeSlider({
        skin: "round",
        type: "double",
        grid: true,
        step: 5,
        min_interval: 30,
        drag_interval: true,
        min: $("#filter-min-price").val(),
        max: $("#filter-max-price").val(),
        prefix: "$"
    }).on("change", function () {
        const $this = $(this), value = $this.prop("value").split(";");
        $("#filter-min-price").val(value[0]);
        $("#filter-max-price").val(value[1]);
    });
    $('#filter-amenities').multiselect({
        includeSelectAllOption: true,
        buttonWidth: '100%',
        numberDisplayed: 5,
        selectedClass: 'amenities-selected',
    });
    const table = $('#roomsTable').DataTable({
        ajax: {
            url: '../../process/get_available_rooms.php',
            type: 'GET',
            traditional: true,
            data: {
                'start-date': start_date,
                'end-date': end_date,
                'bed-amount': bed_amount,
                'min-price': min_price,
                'max-price': max_price,
                'services': services
            },
            dataSrc: ''
        },
        columns: [
            { data: 'id' },
            { data: 'room_number' },
            { data: 'bed_amount' },
            { data: 'standard_price' }
        ],
        responsive: true
    });
    new $.fn.dataTable.FixedHeader(table);
});