$(document).ready(function () {
    buildTable();
    const body = $('body');
    body.on('click', '.viewBookingDescBtn', function () {
        let modal = getBookingDescModal();
        $('.main-container').after(modal);
        let selector = 'tr';
        let bookingId = '';
        if ($(this).hasClass('latest-content')) {
            selector = '.card-body';
            bookingId = $(this).closest('.card').find('.booking-id').html();
            bookingId = bookingId.substring(bookingId.indexOf('#') + 1);
        } else {
            bookingId = $(this).closest(selector).find('.booking-id').html();
        }
        $('#viewBookingDescTitle').append(bookingId + ' details');
        $('#viewBookingDescBookingId').prepend(bookingId);
        $('#viewBookingDescBookDate').prepend($(this).closest(selector).find('.book-date').html());
        $('#viewBookingDescStartDate').prepend($(this).closest(selector).find('.start-date').html());
        $('#viewBookingDescEndDate').prepend($(this).closest(selector).find('.end-date').html());
        $('#viewBookingDescRoomNumber').prepend($(this).closest(selector).find('.room-number').html());
        $('#viewBookingDescBedAmount').prepend($(this).closest(selector).find('.bed-amount').html());
        let bookingStatus = $(this).closest(selector).find('.booking-status').html();
        let color = '';
        switch (bookingStatus) {
            case 'Paid':
                color = '#28a745';
                break;
            case 'Unpaid':
                color = 'orange';
                break;
            case 'Cancelled':
                color = '#dc3545';
                break;
            case 'Completed':
                color = '#007bff';
                break;
            default:
                color = 'black';
                break;
        }
        $('#viewBookingDescBookingStatus').prepend('<span style="color: '+color+';">'+bookingStatus+'</span>');
        $('#viewBookingDescModal').modal();
    });
    body.on('hide.bs.modal', '#viewBookingDescModal', function () {
        setTimeout(() => $('#viewBookingDescModal').remove(), 400);
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
                searchable: false,
                orderable: false,
                targets: 0,
                className: 'align-middle text-center'
            },
            {
                targets: 1,
                className: 'align-middle text-center booking-id'
            },
            {
                targets: 2,
                className: 'align-middle text-center room-number'
            },
            {
                targets: 3,
                className: 'align-middle text-center bed-amount'
            },
            {
                targets: 4,
                className: 'align-middle text-center book-date'
            },
            {
                targets: 5,
                className: 'align-middle text-center start-date'
            },
            {
                targets: 6,
                className: 'align-middle text-center end-date'
            },
            {
                targets: 7,
                className: 'align-middle text-center',
                render: function (data) {
                    const status = data;
                    let badge = '';
                    switch (status) {
                        case 'Paid':
                            badge = 'success';
                            break;
                        case 'Unpaid':
                            badge = 'warning';
                            break;
                        case 'Cancelled':
                            badge = 'danger';
                            break;
                        case 'Completed':
                            badge = 'primary';
                            break;
                        default:
                            badge = 'secondary';
                            break;
                    }
                    return '<span class="badge badge-pill badge-'+badge+' booking-status py-1 px-2" style="font-size: 14px;">'+status+'</span>';
                }
            },
            {
                targets: -1,
                data: null,
                searchable: false,
                orderable: false,
                className: 'align-middle text-center',
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
                        <h5 class="modal-title" id="viewBookingDescTitle">Booking #</h5><button class="btn btn-sm btn-primary ml-3 printBtn" type="button" onclick="window.print()"><i class="las la-print la-lg mr-2"></i>Print</button><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-4">
                            <h6 class="text-uppercase">Basic information</h6>
                            <hr class="border-top">
                            <div id="viewBookingDescBookingId"> <small class="text-muted"> booking id</small></div>
                            <div id="viewBookingDescBookDate"> <small class="text-muted"> book date</small></div>
                            <div id="viewBookingDescStartDate"> <small class="text-muted"> start date</small></div>
                            <div id="viewBookingDescEndDate"> <small class="text-muted"> end date</small></div>
                        </div>
                        <div class="mb-4">
                            <hr class="border-top">
                            <h6 class="text-uppercase">Booking details</h6>
                            <hr class="border-top" style="dashed #999;">
                            <div id="viewBookingDescRoomNumber"> <small class="text-muted"> room number</small></div>
                            <div id="viewBookingDescBedAmount"> <small class="text-muted"> bed amount</small></div>
                            <div id="viewBookingDescAmenities"> <small class="text-muted"> room amenities</small></div>
                            <div id="viewBookingDescServices"> <small class="text-muted"> additional services</small></div>
                        </div>
                        <div class="mb-4">
                            <hr class="border-top">
                            <h6 class="text-uppercase">People details</h6>
                            <hr class="border-top" style="dashed #999;">
                        </div>
                        <div>
                            <hr class="border-top">
                            <h6 class="text-uppercase">Payment information</h6>
                            <hr class="border-top" style="dashed #999;">
                            <div id="viewBookingDescTotal"> <small class="text-muted"> total</small></div>
                            <div id="viewBookingDescBookingStatus"> <small class="text-muted"> status</small></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal" type="button">Close</button>
                    </div>
                </div>
            </div>
        </div>
    `;
}
