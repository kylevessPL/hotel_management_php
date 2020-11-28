$(document).ready(function () {
    $('#filter-start-date, #filter-end-date').datepicker({
        clearBtn: true,
        format: "dd/mm/yyyy",
        orientation: 'right bottom',
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
        numberDisplayed: 5
    });
});

function roomsSearchHandler() {
    $('#roomsTable').DataTable().destroy();
    const startDate = $("#filter-start-date").val();
    const endDate = $("#filter-end-date").val();
    const bedAmount = $("#filter-bed-amount").val();
    const amenities = $("#filter-amenities").val();
    const minPrice = $("#filter-min-price").val();
    const maxPrice = $("#filter-max-price").val();
    buildTable(startDate, endDate, bedAmount, amenities, minPrice, maxPrice);
}

function buildTable(startDate, endDate, bedAmount, amenities, minPrice, maxPrice) {
    const table = $('#roomsTable').DataTable({
        ajax: {
            url: '../../process/get_available_rooms.php',
            type: 'GET',
            data: {
                'start-date': startDate,
                'end-date': endDate,
                'bed-amount': bedAmount,
                'amenities': amenities,
                'min-price': minPrice,
                'max-price': maxPrice,
            },
            dataSrc: ''
        },
        columns: [
            { data: null },
            { data: 'id' },
            { data: 'room-number' },
            { data: 'bed-amount' },
            { data: 'standard-price' },
            { data: null },
            { data: null }
        ],
        responsive: true,
        language: { emptyTable: "No rooms found matching the search criteria" },
        columnDefs: [
            {
                searchable: false,
                orderable: false,
                targets: 0
            },
            {
                targets: 1,
                visible: false
            },
            {
                targets: -1,
                data: null,
                searchable: false,
                orderable: false,
                defaultContent: "<a class='btn btn-success py-1 px-2' href='/dashboard/book-room?id='><i class='las la-calendar-check la-lg'></i></a>",
                className: 'align-middle text-center'
            },
            {
                targets: -2,
                data: null,
                searchable: false,
                orderable: false,
                defaultContent: "<button class='btn btn-primary py-1 px-2 viewRoomAmenitiesBtn'>View</button>",
                className: 'align-middle text-center'
            }
        ],
        order: [[ 2, 'asc' ]]
    });
    table.on( 'order.dt search.dt', function () {
        table.column(0, { search: 'applied', order: 'applied' }).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        });
    }).draw();
    new $.fn.dataTable.FixedHeader(table);
}
