$(document).ready(function () {
    buildTable();
    const body = $('body');
    body.on('click', '.viewBookingDescBtn', function () {
        let modal = getBookingDescModal();
        $('.main-container').after(modal);
        $('#viewBookingDescModal').modal();
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
                searchable: false,
                orderable: false,
                targets: 0
            },
            {
                targets: 1,
                className: 'align-middle text-center booking-number'
            },
            {
                targets: 7,
                render: function (data) {
                    const status = data;
                    let badge = '';
                    switch (status) {
                        case 'Paid':
                            badge = 'success';
                            break;
                        case 'Cancelled':
                            badge = 'danger';
                            break;
                        case 'Completed':
                            badge = 'primary';
                            break;
                        case 'Unpaid':
                            badge = 'warning';
                            break;
                        default:
                            badge = 'secondary';
                            break;
                    }
                    return '<span class="badge badge-pill badge-'+badge+' py-1 px-2" style="font-size: 14px;">'+status+'</span>';
                }
            },
            {
                targets: -1,
                data: null,
                searchable: false,
                orderable: false,
                defaultContent: '<button class="btn btn-primary py-1 px-2 viewBookingDescBtn">View</button>'
            }
        ],
        order: [[ 1, 'desc' ]]
    });
    table.on( 'order.dt search.dt', function () {
        table.column(0, { search: 'applied', order: 'applied' }).nodes().each( function (cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();
    new $.fn.dataTable.FixedHeader(table);
}

function getBookingDescModal() {
    return `
        <div aria-hidden="true" aria-labelledby="viewBookingDescTitle" class="modal fade" id="viewBookingDescModal" role="dialog" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewBookingDescTitle"></h5><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal" type="button">Close</button>
                    </div>
                </div>
            </div>
        </div>
    `;
}
