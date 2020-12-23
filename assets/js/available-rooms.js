$(document).ready(function () {
    initTable();
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
        prefix: "$",
        onChange: function (data) {
            $("#filter-min-price").val(data.from);
            $("#filter-max-price").val(data.to);
        }
    });
    $('#filter-min-price, #filter-max-price').on("change", function () {
        const data = $("#filter-price-slider").data("ionRangeSlider");
        const from = $("#filter-min-price");
        const to = $("#filter-max-price");
        if (!(from.val() >= data.result.min && from.val() <= data.result.max && to.val() >= data.result.min && to.val() <= data.result.max && from.val() <= to.val())) {
            from.val(data.result.from);
            to.val(data.result.to);
        } else {
            data.update({
                from: from.val(),
                to: to.val()
            });
        }
    });
    $('#filter-amenities').selectpicker({
        noneSelectedText: 'None selected',
        actionsBox: true,
        style: '',
        styleBase: 'form-control',
        tickIcon: 'mt-1 las la-check'
    });
    $('#filter-bed-amount').selectpicker({
        style: '',
        styleBase: 'form-control'
    });
    $.fn.selectpicker.Constructor.BootstrapVersion = '4';
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
    $('#roomsTable tbody').on('click', 'button', function (event) {
        event.preventDefault();
        const modal = getRoomDescModal();
        $('.main-container').after(modal);
        $.ajax({
            url: '../../process/get_room_amenities',
            type: "GET",
            data: { id: $(this).closest("tr").find("td.dt-id").text() },
            dataType: 'JSON',
            success: function (response) {
                let list = '';
                $.each(response, function(key, val) {
                    list += '<li>' + val['name'] + '</li>';
                });
                $('#viewRoomDescAmenities').html('<br><ul class="list-unstyled"><li>Amenities:<ul>' + list + '</ul></li></ul>');
            }
        });
        $('#viewRoomDescModalTitle').html('Room description');
        $('#viewRoomDescRoomNumber').html('Room number: ' + $(this).closest("tr").find("td.dt-room-number").text());
        $('#viewRoomDescBedAmount').html('<br>' + 'Bed amount: ' + $(this).closest("tr").find("td.dt-bed-amount").text());
        $('#viewRoomDescStandardPrice').html('<br>' + 'Standard price: ' + $(this).closest("tr").find("td.dt-standard-price").text() + ' USD');
        $('#viewRoomDescAmenities').html('<br>' + 'Amenities: ');
        let selector = $('#viewRoomDescModal');
        selector.modal();
        selector.on('hide.bs.modal', function () {
            setTimeout(() => $('#viewRoomDescModal').remove(), 400);
        });
    });
}

function initTable() {
    $('#roomsTable').DataTable({
        columns: [
            { data: null },
            { data: 'id' },
            { data: 'room-number' },
            { data: 'bed-amount' },
            { data: 'standard-price' },
            { data: null },
            { data: null }
        ],
        lengthMenu: [[7, 15, 25, 50], [7, 15, 25, 50]],
        pageLength: 7,
        responsive: true,
        language: { emptyTable: "Please use the search form to find rooms matching your criteria" },
        columnDefs: [
            {
                targets: '_all',
                className: 'align-middle text-center'
            },
            {
                searchable: false,
                orderable: false,
                targets: 0
            }
        ],
        order: [[ 2, 'asc' ]]
    }).clear().draw();
}

function buildTable(startDate, endDate, bedAmount, amenities, minPrice, maxPrice) {
    const table = $('#roomsTable').DataTable({
        ajax: {
            url: '../../process/get_available_rooms',
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
        lengthMenu: [[7, 15, 25, 50], [7, 15, 25, 50]],
        pageLength: 7,
        responsive: true,
        language: {
            emptyTable: "No rooms found matching the search criteria",
            zeroRecords: "No rooms found matching your criteria"
        },
        columnDefs: [
            {
                targets: [ 0, -1, -2 ],
                className: 'align-middle text-center'
            },
            {
                searchable: false,
                orderable: false,
                targets: 0
            },
            {
                targets: 1,
                className: 'd-none dt-id'
            },
            {
                targets: 2,
                className: 'align-middle text-center dt-room-number'
            },
            {
                targets: 3,
                className: 'align-middle text-center dt-bed-amount'
            },
            {
                targets: 4,
                className: 'align-middle text-center dt-standard-price'
            },
            {
                targets: -1,
                data: null,
                searchable: false,
                orderable: false,
                render: function (row) {
                    return '<a class="btn btn-success py-1 px-2" href="/dashboard/book-room?id='+row.id+'&start-date='+encodeURIComponent($("#filter-start-date").val())+'&end-date='+encodeURIComponent($("#filter-end-date").val())+'"><i class="las la-calendar-check la-lg"></i></a>'
                }
            },
            {
                targets: -2,
                data: null,
                searchable: false,
                orderable: false,
                defaultContent: '<button class="btn btn-primary py-1 px-2 viewRoomAmenitiesBtn">View</button>'
            }
        ],
        order: [[ 2, 'asc' ]]
    });
    table.on( 'order.dt search.dt', function () {
        table.column(0, { search: 'applied', order: 'applied' }).nodes().each( function (cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();
    new $.fn.dataTable.FixedHeader(table);
}

function getRoomDescModal() {
    return `
        <div aria-hidden="true" aria-labelledby="viewRoomDescModalTitle" class="modal fade" id="viewRoomDescModal" role="dialog" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewRoomDescModalTitle"></h5><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div id="viewRoomDescRoomNumber"></div>
                        <div id="viewRoomDescBedAmount"></div>
                        <div id="viewRoomDescStandardPrice"></div>
                        <div id="viewRoomDescAmenities"></div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal" type="button">Close</button>
                    </div>
                </div>
            </div>
        </div>
    `;
}

$(function() {
    $("form[name='rooms-search-form']").validate({
        rules: {
            'filter-start-date': {
                required: true,
                futuredate: true
            },
            'filter-end-date': {
                required: true,
                futuredate: true,
                afterstartdate: function () {
                    return $('#filter-start-date').val();
                },
            }
        },
        messages: {
            'filter-start-date': {
                required: "Start date is mandatory",
                futuredate: "Start date must be in the future"
            },
            'filter-end-date': {
                required: "End date is mandatory",
                futuredate: "End date must be in the future",
                afterstartdate: "End date cannot be before start date"
            }
        },
        submitHandler: function () {
            roomsSearchHandler();
            return false;
        }
    });
});
