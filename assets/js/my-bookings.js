$(document).ready(function () {
    buildTable();
    const body = $('body');
    body.on('click', '.viewBookingDescBtn', function () {

    });
    body.on('hide.bs.modal', '#bookingDescModal', function () {
        setTimeout(() => $('#bookingDescModal').remove(), 400);
    });
});

function buildTable() {
    const table = $('#bookingsTable').DataTable({
        ajax: {
            url: '../../process/get_customer_bookings.php',
            type: 'GET',
            dataSrc: ''
        },
        columns: [
            { data: null },
            { data: 'booking-id' },
            { data: 'room-number' },
            { data: 'bed-amount' },
            { data: 'book-date' },
            { data: 'start-date' },
            { data: 'end-date' },
            { data: 'status' },
            { data: null }
        ],
        lengthMenu: [[7, 15, 25, 50], [7, 15, 25, 50]],
        pageLength: 7,
        responsive: true,
        language: { emptyTable: "It seems like you haven't booked any rooms yet" },
        columnDefs: [
            {
                targets: '_all',
                className: 'align-middle text-center'
            },
            {
                targets: [ 0, -1 ],
                className: 'align-middle text-center'
            },
            {
                searchable: false,
                orderable: false,
                targets: 0
            },
            {
                targets: 1,
                className: 'align-middle text-center booking-number'
            },
            {
                targets: -1,
                data: null,
                searchable: false,
                orderable: false,
                defaultContent: '<button class="btn btn-primary py-1 px-2 viewBookingDescBtn">View</button>'
            }
        ],
        order: [[ 4, 'desc' ]]
    });
    table.on( 'order.dt search.dt', function () {
        table.column(0, { search: 'applied', order: 'applied' }).nodes().each( function (cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();
    new $.fn.dataTable.FixedHeader(table);
}

function getBookingDescModal() {

}