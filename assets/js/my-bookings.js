$(document).ready(function() {
    buildTable();
    const body = $('body');
    body.on('click', '.animated-1, .viewBookingDescBtn', function() {
        handleViewBookingDescModal.call(this);
    });
    body.on('hide.bs.modal', '#viewBookingDescModal', function() {
        setTimeout(() => $('#viewBookingDescModal').remove(), 400);
    });
});

function handleViewBookingDescModal() {
    let modal = getBookingDescModal();
    const mainContainer = $('.main-container');
    mainContainer.after(modal);
    let selector = $(this).closest('tr');
    let bookingId = getBookingId.call(this, selector);
    $('#viewBookingDescTitle').append(bookingId + ' details');
    $('#viewBookingDescBookingId').prepend(bookingId);
    $('#viewBookingDescBookDate').prepend(selector.find('.book-date').html());
    $('#viewBookingDescStartDate').prepend(selector.find('.start-date').html());
    $('#viewBookingDescEndDate').prepend(selector.find('.end-date').html());
    $('#viewBookingDescRoomNumber').prepend(selector.find('.room-number').html());
    $('#viewBookingDescBedAmount').prepend(selector.find('.bed-amount').html());
    let bookingStatus = selector.find('.booking-status').html();
    let bookingStatusElement = $('#viewBookingDescBookingStatus');
    let color = getBookingStatusColor(bookingStatus, bookingStatusElement);
    bookingStatusElement.prepend('<span style="color: ' + color + ';">' + bookingStatus + '</span>');
    $('#viewBookingDescModal').modal();
    $('.cancelBookingBtn').on('click', function() {
        handleCancelBooking(bookingId);
    });
    setBookingDetails(bookingId);

    function getBookingDescModal() {
        return `
        <div aria-hidden="true" aria-labelledby="viewBookingDescTitle" class="modal fade" id="viewBookingDescModal" role="dialog" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewBookingDescTitle">Booking #</h5><button class="btn btn-sm btn-primary ml-3 printBtn" type="button" onclick="window.print()"><i class="las la-print la-lg mr-2"></i>Print</button><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <h6 class="text-uppercase">Basic information</h6>
                                <hr class="border-top">
                            </div>
                            <div class="col-sm-4">
                                <h6 class="text-uppercase">Booking details</h6>
                                <hr class="border-top">
                            </div>
                            <div class="col-sm-4">
                                <h6 class="text-uppercase">Payment information</h6>
                                <hr class="border-top">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <div id="viewBookingDescBookingId" class="mb-2"> <small class="text-muted"> booking id</small></div>
                                    <div id="viewBookingDescBookDate" class="mb-2"> <small class="text-muted"> book date</small></div>
                                    <div id="viewBookingDescStartDate" class="mb-2"> <small class="text-muted"> start date</small></div>
                                    <div id="viewBookingDescEndDate"> <small class="text-muted"> end date</small></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <div id="viewBookingDescRoomNumber" class="mb-2"> <small class="text-muted"> room number</small></div>
                                    <div id="viewBookingDescBedAmount" class="mb-2"> <small class="text-muted"> bed amount</small></div>
                                    <div id="viewBookingDescAmenities" class="mb-2"> <small class="text-muted"> room amenities</small></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <div id="viewBookingDescTotal" class="mb-2"> PLN <small class="text-muted"> total</small></div>
                                    <div id="viewBookingDescBookingStatus" class="mb-3"> <small class="text-muted"> status</small></div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <hr class="border-top">
                            <h6 class="text-uppercase">People details</h6>
                            <hr class="border-top">
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

    function getBookingId(selector) {
        let bookingId = '';
        if ($(this).hasClass('card')) {
            selector = $(this);
            bookingId = selector.find('.booking-id').html();
            bookingId = bookingId.substring(bookingId.indexOf('#') + 1);
        } else {
            bookingId = selector.find('.booking-id').html();
        }
        return bookingId;
    }

    function getBookingStatusColor(bookingStatus, bookingStatusElement) {
        let color = '';
        switch (bookingStatus) {
            case 'Paid':
                color = '#28a745';
                bookingStatusElement.after('<button class="btn btn-danger btn-block cancelBookingBtn"><i class="las la-times-circle la-lg mr-2"></i>Cancel</button>');
                break;
            case 'Unpaid':
                color = 'orange';
                bookingStatusElement.after(`
                    <div class="row d-flex justify-content-between">
                        <div class="col-md-6">
                            <button class="btn btn-success btn-block retryPaymentBtn mr-2"><i class="las la-check-circle la-lg mr-2"></i>Pay</button>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-danger btn-block cancelBookingBtn"><i class="las la-times-circle la-lg mr-2"></i>Cancel</button>
                        </div>
                    </div>
                `);
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
    }

    function handleCancelBooking(bookingId) {
        $.ajax({
            url: '../../process/cancel_booking',
            type: "GET",
            data: {'id': bookingId},
            success: function (response) {
                $(location).attr('href', './my-bookings');
            }
        })
    }

    function setBookingDetails(bookingId) {
        $.ajax({
            url: '../../process/get_booking_details',
            type: "GET",
            data: {'id': bookingId},
            dataType: 'JSON',
            success: function (response) {
                let totalElement = $('#viewBookingDescTotal');
                totalElement.prepend(response[0]['total']);
                let icon = getPaymentFormIcon(response);
                if (response[0]['payment-form'] != null) {
                    totalElement.after('<div id="viewBookingDescPaymentForm" class="mb-2"><i class="' + icon + ' la-lg mr-1" style="color: #007bff;"></i>' + response[0]['payment-form'] + ' <small class="text-muted"> payment form</small></div>');
                }
                if (response[0]['services'].length > 0) {
                    $('#viewBookingDescAmenities').after('<div id="viewBookingDescServices"><small class="text-muted"> additional services</small></div>');
                }
                let array1 = [];
                $.each(response[0]['services'], function (key, val) {
                    array1.push(val['name']);
                });
                $('#viewBookingDescServices').prepend(array1.join(', '));
                $('.modal-body').append('<div class="row" id="people-section"></div>')
                $.each(response[0]['people'], function (key, val) {
                    $('#people-section').append(`
                        <div class="col-sm-3">
                            <div class="mb-3">
                                <div class="mb-2"><strong>` + val['first-name'] + ' ' + val['last-name'] + `</strong></div>
                                <div class="mb-2">` + val['document-type'] + ` <small class="text-muted"> document type</small></div>
                                <div>` + val['document-id'] + ` <small class="text-muted"> document id</small></div>
                            </div>
                        </div>
                    `);
                });
                setRoomAmenities(response[0]['room-id']);
                $('.retryPaymentBtn').on('click', function() {
                    handleRetryPayment(response[0]['total'], );
                });
            }
        });

        function handleRetryPayment(totalPrice) {
            let selector2 = '#paymentModal';
            $('#viewBookingDescModal .close').click();
            const modal2 = getPaymentModal(true);
            mainContainer.after(modal2);
            initCreditCardFormValidator();
            $('[data-toggle="tooltip"]').tooltip();
            $('#paymentModalTitle').html('Pay for booking #' + bookingId);
            $('#transfer-title').append(bookingId);
            const total = Number(totalPrice).toFixed(2);
            const creditCardAction = $('.creditCardPayAction');
            creditCardAction.append(total + ' PLN');
            $('.payment-total').prepend(total);
            setBitcoinDetails(total, bookingId);
            setPayPalPaymentLink(bookingId);
            $(selector2).modal();
            const paymentFormRadio = $('.paymentFormRadio');
            paymentFormRadio.first().addClass('selected');
            $('#nav-tab-card').addClass('active');
            $('#cardNumber').on('keyup keydown', function() {
                handleCreditCardInput(this);
            });
            paymentFormRadio.click(function() {
                $(this).tab('show');
                $('.paymentFormRadio.selected').removeClass('selected');
                $(this).removeClass('active').addClass('selected');
            });
            $(selector2).on('hide.bs.modal', function() {
                $(location).attr('href', './my-bookings');
            });
        }

        function setRoomAmenities(roomId) {
            $.ajax({
                url: '../../process/get_room_amenities',
                type: "GET",
                data: {'id': roomId},
                dataType: 'JSON',
                success: function (response) {
                    let array2 = [];
                    $.each(response, function (key, val) {
                        array2.push(val['name']);
                    });
                    $('#viewBookingDescAmenities').prepend(array2.join(', '));
                }
            });
        }

        function getPaymentFormIcon(response) {
            let icon = '';
            switch (response[0]['payment-form']) {
                case 'Credit Card':
                    icon = 'las la-credit-card';
                    break
                case 'PayPal':
                    icon = 'lab la-paypal';
                    break;
                case 'Bitcoin':
                    icon = 'lab la-bitcoin';
                    break;
                case 'Bank transfer':
                    icon = 'las la-university';
                    break;
                default:
                    icon = 'las la-money-check-alt';
                    break;
            }
            return icon;
        }
    }
}

function buildTable() {
    let data = {
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
        language: {
            emptyTable: "It seems like you haven't booked any rooms yet",
            zeroRecords: "No bookings found matching your criteria"
        },
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
                className: 'align-middle text-center book-date',
                render: function (data) {
                    return moment(data).format('DD/MM/YYYY HH:mm:ss');
                }
            },
            {
                targets: 5,
                className: 'align-middle text-center start-date',
                render: function (data) {
                    return moment(data).format('DD/MM/YYYY');
                }
            },
            {
                targets: 6,
                className: 'align-middle text-center end-date',
                render: function (data) {
                    return moment(data).format('DD/MM/YYYY');
                }
            },
            {
                targets: 7,
                className: 'align-middle text-center',
                orderable: false,
                render: function (data) {
                    const status = data;
                    const badge = getBadgeType(data);
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
        order: [[ 1, 'desc' ]],
        initComplete: function() {
            if (!this.fnGetData().length) {
                return;
            }
            const select = setBookingStatusSelectData.call(this);
            select.selectpicker({
                style: '',
                styleBase: 'form-control',
            });
            $.fn.selectpicker.Constructor.BootstrapVersion = '4';

            function setBookingStatusSelectData() {
                const column = this.api().column(7);
                const select = $('<select class="selectpicker" data-width="120px"><optgroup label="Default"><option value="" class="font-weight-bold" title="Status" selected>Status</option></optgroup></select>')
                    .appendTo($(column.header()).empty())
                    .on('change', function() {
                        const val = $.fn.dataTable.util.escapeRegex($(this).val());
                        column
                            .search(val ? '^' + val + '$' : '', true, false)
                            .draw();
                    });
                column.data().unique().sort().each(function (d, j) {
                    const badge = getBadgeType(d);
                    const dataContent = "<span class='badge badge-" + badge + " py-1 px-2'>" + d + "</span>";
                    select.append('<option value="' + d + '" data-content="' + dataContent + '">' + d + '</option>')
                });
                return select;

                function getBadgeType(status) {
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
                    return badge;
                }
            }
        }
    }
    if (isCustomerIdSet()) {
        data.ajax = {
            url: '../../process/get_customer_bookings',
            type: 'GET',
            dataSrc: ''
        }
    }
    const table = $('#bookingsTable').DataTable(data);
    table.on( 'order.dt search.dt', function() {
        table.column(0, { search: 'applied', order: 'applied' }).nodes().each( function (cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();
    new $.fn.dataTable.FixedHeader(table);
}
