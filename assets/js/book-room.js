$(document).ready(function () {
    init();
    setEvents();
});

function init() {
    $('#startDate, #endDate').datepicker({
        clearBtn: true,
        format: "dd/mm/yyyy",
        orientation: 'right bottom',
        weekStart: 1
    });
    $('#services').selectpicker({
        noneSelectedText: 'None selected',
        actionsBox: true,
        style: '',
        styleBase: 'form-control',
        tickIcon: 'mt-1 las la-check'
    });
    $('#myAddress, #bedAmount, #room, .document-type').selectpicker({
        style: '',
        styleBase: 'form-control',
        size: 5
    });
    $.fn.selectpicker.Constructor.BootstrapVersion = '4';
    setStickySummaryPanel();
    checkCookie();
}

function setStickySummaryPanel() {
    const height = $('.navbar').height() + 16;
    $('.sticky-top').css('top', height + 'px');
}

function checkCookie() {
    const promoCode = Cookies.get('promo_code');
    if (promoCode != null) {
        $.ajax({
            url: '../../process/check_promo_code_availability.php',
            type: "GET",
            data: { 'promo-code': promoCode },
            success: function (response) {
                if (response === 'true') {
                    getDiscountValue();
                } else {
                    Cookies.remove('promo_code', { path: '' });
                }
            }
        });
    }
}

function setEvents() {
    $('#bedAmount, #startDate, #endDate').on('change', function () {
        const startDate = $('#startDate');
        const endDate = $('#endDate');
        const bedAmount = $('#bedAmount');
        if (startDate.val() !== '' && startDate.valid() === true && endDate.val() !== '' && endDate.valid() === true && bedAmount.val() !== '' && bedAmount.valid() === true) {
            fetchRooms(startDate, endDate, bedAmount);
        } else {
            $('#room').html('<option value="">Choose dates and bed amount first</option>').selectpicker('refresh').selectpicker('val', '');
            $('#booking-form-people').remove();
            setRoomItem();
        }
    });
    $('#promo-code').on('keydown', function() {
        $(this).closest(".error").removeClass("error");
        $(this).parent().siblings('label').remove();
    });
    $('#room').on('change', function() {
        setRoomItem();
    });
    $('.bookingSubmit').on('click', function() {
        if ($('#booking-form').valid()) {
            $('.itQKmP, .hZAwTR, .iZQJIb, .muNJM').hide();
            const modal = getBookingConfirmationModal();
            $('.main-container').after(modal);
            const confirmationPeopleDetails = '.confirmation-people-details';
            const confirmationItems = '.confirmation-items';
            $(confirmationPeopleDetails).html('');
            $(confirmationItems).html('');
            $.ajax({
                url: '../../process/get_customer_details.php',
                type: "GET",
                dataType: 'JSON',
                success: function (response) {
                    const selectedAddress = $('#myAddress :selected').text();
                    $(confirmationPeopleDetails).append(createConfirmationPeopleSection('Your', response[0]['first-name'], response[0]['last-name'], selectedAddress.substring(0, selectedAddress.indexOf(',')), selectedAddress.substring(selectedAddress.indexOf(',') + 2), response[0]['document-type'], response[0]['document-id']));
                    $('.booking-person').each(function (index, currentElement) {
                        let firstName = $(currentElement).find('.first-name').val();
                        let lastName = $(currentElement).find('.last-name').val();
                        let documentType = $(currentElement).find('.document-type :selected').text();
                        let documentId = $(currentElement).find('.document-id').val();
                        $(confirmationPeopleDetails).append(createConfirmationPeopleSection('Person ' + Number(index + 1), firstName, lastName, null, null, documentType, documentId));
                    });
                    $(confirmationItems).append(createConfirmationItemsSection(1, $('.roomItem .room-item-name').html(), $('.roomItem .room-item-desc').html(), $('.roomItem .item-price').html()));
                    $('.servicesItem').each(function(index, currentElement) {
                        $(confirmationItems).append(createConfirmationItemsSection(index + 2, $(currentElement).find('.item-name').html(), 'Additional service #' + $(currentElement).find('.item-index').html(), $(currentElement).find('.item-price').html()));
                    });
                    const total = $('#total').html();
                    const totalValue = total.substring(0, total.indexOf(' PLN'));
                    $('.confirmation-period').html($('.periodItem').find('small').html());
                    if ($('.discountItem').length > 0) {
                        const discountPrice = $('.discount-price').html();
                        const subTotalValue = (Number(discountPrice.substring(1, discountPrice.indexOf(' PLN'))) + Number(totalValue)).toFixed(2);
                        $('.confirmation-subtotal').html(subTotalValue + ' PLN');
                        $('.confirmation-discount-value').html('Discount (' + $('.discount-value').html() +'%)');
                        $('.confirmation-discount-price').html(discountPrice);
                        $('.discounted').show();
                    } else {
                        $('.discounted').hide();
                    }
                    $('.confirmation-total').html(totalValue + ' PLN');
                    let selector = $('#confirmBookingModal');
                    selector.modal();
                    selector.on('hide.bs.modal', function () {
                        setTimeout(() => $('#confirmBookingModal').remove(), 400);
                    });
                    $('#confirmBookingAction').on('click', function () {
                        let services = [];
                        $('#services option:selected').each(function (index, currentElement) {
                            services.push( { "id": $(currentElement).val() } );
                        });
                        let people = [];
                        $('.booking-person').each(function (index, currentElement) {
                            let firstName = $(currentElement).find('.first-name').val();
                            let lastName = $(currentElement).find('.last-name').val();
                            let documentType = $(currentElement).find('.document-type :selected').text();
                            let documentId = $(currentElement).find('.document-id').val();
                            people.push( { "first-name": firstName, "last-name": lastName, "document-type": documentType, "document-id": documentId } );
                        });
                        let promoCode = '';
                        const discountItem = $('.discountItem');
                        if (discountItem.length > 0) {
                            promoCode = $(discountItem).find('.discount-code').html();
                        }
                        const data = {
                            "start-date": $('#booking-form #startDate').val(),
                            "end-date": $('#booking-form #endDate').val(),
                            "room-id": $('#booking-form #room').val(),
                            "services": services,
                            "people": people,
                            "promo-code": promoCode
                        };
                        $.ajax({
                            url: '../../process/book_room_and_get_id.php',
                            type: "POST",
                            contentType: "application/json; charset=utf-8",
                            data: JSON.stringify(data),
                            processData: false,
                            dataType: 'JSON',
                            success: function (response) {
                                let selector2 = '#paymentModal';
                                $('#confirmBookingModal .close').click();
                                const modal2 = getPaymentModal();
                                $('.main-container').after(modal2);
                                $('[data-toggle="tooltip"]').tooltip();
                                $('#paymentModalTitle').html('Pay for booking #' + response[0]['id']);
                                $('#transfer-title').append(response[0]['id']);
                                const total = Number(response[0]['total']).toFixed(2);
                                const creditCardAction = $('.creditCardPayAction');
                                creditCardAction.append(total + ' PLN');
                                $('.payment-total').prepend(total);
                                setBitcoinDetails(total, response[0]['id']);
                                setPayPalPaymentLink(response[0]['id']);
                                $(selector2).modal();
                                const paymentFormRadio = $('.paymentFormRadio');
                                paymentFormRadio.first().addClass('selected');
                                $('#nav-tab-card').addClass('active');
                                $('#cardNumber').on('keyup keydown', function() {
                                    $(this).val(function (index, value) {
                                        const selectionStart = $(this).get(0).selectionStart;
                                        let trimmedCardNum = value.replace(/\s+/g, '');
                                        if (trimmedCardNum.length > 16) {
                                            trimmedCardNum = trimmedCardNum.substr(0, 16);
                                        }
                                        const partitions = trimmedCardNum.startsWith('34') || trimmedCardNum.startsWith('37')
                                            ? [4,6,5]
                                            : [4,4,4,4];
                                        const numbers = [];
                                        let position = 0;
                                        partitions.forEach(partition => {
                                            const part = trimmedCardNum.substr(position, partition);
                                            if (part) numbers.push(part);
                                            position += partition;
                                        });
                                        const formattedCardNum = numbers.join(' ');
                                        if (selectionStart < formattedCardNum.length - 1) {
                                            setTimeout(() => {
                                                $(this).get(0).setSelectionRange(selectionStart, selectionStart, 'none');
                                            });
                                        }
                                        return formattedCardNum;
                                    })
                                });
                                paymentFormRadio.click(function () {
                                    $(this).tab('show');
                                    $('.paymentFormRadio.selected').removeClass('selected');
                                    $(this).removeClass('active').addClass('selected');
                                });
                                creditCardAction.on('click', function () {
                                    $('.creditCardTab').prepend('<p class="alert alert-danger">Unfortunately, we can\'t process your payment.</p>');
                                });
                            },
                            error: function () {
                                $('.booking-confirmation-alert').html('Oops, something went wrong. Please try again later.').removeClass('alert-warning').addClass('alert-danger');
                            }
                        });
                    });
                }
            });
        }
    });
    $('#services').on('change', function() {
        setServiceItem();
    });
}

function setPayPalPaymentLink(bookingId) {
    $.ajax({
        url: '../../process/get_paypal_payment_link.php',
        type: "GET",
        data: { "booking-id": bookingId },
        dataType: 'JSON',
        success: function (response) {
            $('.payPalPayAction').attr('href', response[0]['payment-link']);
        }
    });
}

function fetchRooms(startDate, endDate, bedAmount) {
    $.ajax({
        url: '../../process/get_available_rooms.php',
        type: "GET",
        data: {
            'start-date': startDate.val(),
            'end-date': endDate.val(),
            'bed-amount': bedAmount.val()
        },
        dataType: 'JSON',
        success: function (response) {
            let list = '<option value="">None selected</option>';
            $.each(response, function(key, val) {
                list += '<option value="'+val['id']+'">Room number: '+val['room-number']+', Price: '+val['standard-price']+' PLN</option>';
            });
            const room = $('#room');
            room.html(list);
            room.selectpicker('refresh');
            if (typeof setChoice === 'function') {
                setChoice();
            }
            setRoomItem();
            showPeopleOption(bedAmount);
        }
    });
}

function showPeopleOption(bedAmount) {
    const selector = "#booking-form-people";
    let peopleSection = $(selector);
    peopleSection.remove();
    if (bedAmount.val() > 1) {
        $('#booking-form-main').after('<div id="booking-form-people" class="pt-2 pb-3"></div>');
        peopleSection = $(selector);
        peopleSection.html('<button class="btn btn-outline-success text-right add-person-action" type="button"><i class="las la-plus-circle la-lg mr-2"></i>Add person</button>');
        peopleSection.on('click', '.removePerson', function () {
            const selector = '.booking-person';
            $(this).closest(selector).find('.first-name').rules("remove");
            $(this).closest(selector).find('.last-name').rules("remove");
            $(this).closest(selector).find('.document-id').rules("remove");
            $(this).closest(selector).remove();
            $(selector).each(function(index, currentElement) {
                $(currentElement).find('legend').html('Person ' + (index + 1));
            });
            if ($('.booking-person').length < bedAmount.val() - 1) {
                $('.add-person-action').show();
            }
        });
        const addPerson = $('.add-person-action');
        addPerson.on('click', function() {
            const bookingPerson = $('.booking-person');
            const bedAmount = $('#bedAmount');
            if (bookingPerson.length < bedAmount.val() - 1) {
                if (bookingPerson.length === bedAmount.val() - 2) {
                    $('.add-person-action').hide();
                }
                const count = bookingPerson.length + 1;
                const id = Date.now();
                $('#booking-form-people').append(`
                    <div class="booking-person mt-2">
                        <fieldset class="border p-3 position-relative">
                            <legend class="w-auto pb-1" style="font-size: 20px;">Person ` + count + `</legend>
                            <button class="badge badge-danger removePerson position-absolute" type="button" style="top: 0.4em; right: 20px; line-height: 1.2em;"><i class="las la-times mr-1"></i>Remove</button>
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label class="control-label label-first-name" for="first-name-` + id + `">First name<span style="color: red">*</span></label>
                                    <input class="form-control first-name" id="first-name-` + id + `" name="first-name-` + id + `" type="text" placeholder="Enter first name">
                                </div>
                                <div class="col-sm-6">
                                    <label class="control-label label-last-name" for="last-name-` + id + `">Last name<span style="color: red">*</span></label>
                                    <input class="form-control last-name" id="last-name-` + id + `" name="last-name-` + id + `" type="text" placeholder="Enter last name">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label class="control-label label-document-type" for="document-type-` + id + `">Document type<span style="color: red">*</span></label>
                                    <select id="document-type-` + id + `" name="document-type-` + id + `" class="selectpicker form-control document-type">
                                        <option value="ID card">ID card</option>
                                        <option value="Passport">Passport</option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label class="control-label label-document-id" for="document-id-` + id + `">Document ID<span style="color: red">*</span></label>
                                    <input class="form-control document-id" id="document-id-` + id + `" name="document-id-` + id + `" type="text" placeholder="Enter document ID">
                                </div>
                            </div>
                        </fieldset>
                    </div>`);
                const documentType = $('.document-type');
                documentType.selectpicker('refresh');
                documentType.selectpicker('setStyle', 'btn', 'remove');
                documentType.selectpicker('setStyle', 'form-control');
                setPeopleSectionClassRules();
            }
        });
    }
}

function createConfirmationPeopleSection(title, firstName, lastName, addressPart1, addressPart2, documentType, documentId) {
    let data = '';
    if (addressPart1 !== null && addressPart2 !== null) {
        data = '<div>' + addressPart1 + '</div><div>' + addressPart2 + '</div>';
    }
    return `
        <div class="col-sm-3">
            <h6 class="mb-3">` +title+' details:'+ `</h6>
            <div>
                <strong>` +firstName+' '+lastName+ `</strong>
            </div>`
            +data+ `
            <div>` +'Document type: '+documentType+ `</div>
            <div>` +'Document ID: '+documentId+ `</div>
        </div>
    `;
}

function createConfirmationItemsSection(index, item, desc, price) {
    return `
        <tr>
            <th class='align-middle' scope='row'>` +index+ `</th>
            <td class='align-middle text-center'>` +item+ `</td>
            <td class='align-middle text-center'>` +desc+ `</td>
            <td class='align-middle text-center'>` +price+ `</td>
        </tr>
    `;
}

function setRoomItem() {
    const roomItem = $('.roomItem');
    if (roomItem.length > 0) {
        roomItem.remove();
        setPeriodItem();
        updateTotal();
    }
    const room = $('#room');
    if (room.val() !== '' && room.valid() === true) {
        getRoomAmenities();
    }
}

function getRoomAmenities() {
    const room = $('#room');
    $.ajax({
        url: '../../process/get_room_amenities.php',
        type: "GET",
        data: { id: room.val() },
        dataType: 'JSON',
        success: function (response) {
            const selectedRoom = $('#room option:selected').text();
            let amenities = [];
            $.each(response, function(key, val) {
                amenities.push(val['name']);
            });
            const roomItemObject = getRoomItemObject('Room ' + selectedRoom.substring(13, 16), $('#bedAmount option:selected').text() + ' bed variant, ' + amenities.join(', '), selectedRoom.substring(25, selectedRoom.indexOf(' PLN')))
            $('.items').prepend(roomItemObject);
            setPeriodItem();
            updateTotal();
        }
    });
}

function setServiceItem() {
    const servicesItem = $('.servicesItem');
    if (servicesItem.length > 0) {
        servicesItem.remove();
        updateTotal();
    }
    const servicesSelected = $('#services option:selected');
    if (servicesSelected.length > 0) {
        getServicesDesc();
    }
}

function getServicesDesc() {
    const servicesSelected = $('#services option:selected');
    servicesSelected.each(function(index, currentElement) {
        $.ajax({
            url: '../../process/get_service_desc.php',
            type: "GET",
            data: {id: $(currentElement).val() },
            dataType: 'JSON',
            success: function (response) {
                const serviceItemObject = getServiceItemObject($(currentElement).text(), $(currentElement).index() + 1, response[0]['price']);
                const discountItem = '.discountItem';
                const periodItem = '.periodItem';
                if ($(periodItem).length > 0) {
                    $(periodItem).before(serviceItemObject);
                } else if ($(discountItem).length > 0) {
                    $(discountItem).before(serviceItemObject);
                } else {
                    $('.total').before(serviceItemObject);
                }
                updateTotal();
            }
        });
    });
}

function setDiscountItem() {
    const discountItem = $('.discountItem');
    if (discountItem.length > 0) {
        discountItem.remove();
        Cookies.remove('promo_code', { path: '' });
        updateTotal();
    }
    const promoCode = $('#promo-code');
    if (promoCode.val() !== '' && promoCode.valid() === true) {
        getDiscountValue();
    }
}

function getDiscountValue() {
    const promoCode = $('#promo-code');
    let value = '';
    if (promoCode.val() !== '') {
        value = promoCode.val();
    } else {
        value = Cookies.get('promo_code');
    }
    $.ajax({
        url: '../../process/get_promo_code_discount.php',
        type: "GET",
        data: { 'promo-code': value },
        dataType: 'JSON',
        success: function (response) {
            const discountItemObject = getDiscountItemObject(value, response[0]['discount']);
            const periodItem = '.periodItem';
            if ($(periodItem).length > 0) {
                $(periodItem).after(discountItemObject);
            } else {
                $('.total').before(discountItemObject);
            }
            $('.discount-price').hide();
            Cookies.set('promo_code', value, { expires: 30, path: '' });
            updateTotal();
        }
    });
}

function setPeriodItem() {
    if ($('.roomItem').length === 0) {
        $('.periodItem').remove();
    } else {
        const startDate = moment($('#startDate').val(), 'DD/MM/YYYY');
        const endDate = moment($('#endDate').val(), 'DD/MM/YYYY');
        const period = moment.duration(endDate.diff(startDate));
        const periodItemObject = getPeriodItemObject(startDate.format('DD/MM/YYYY'), endDate.format('DD/MM/YYYY'), period);
        const discountItem = '.discountItem';
        if ($(discountItem).length > 0) {
            $(discountItem).before(periodItemObject);
        } else {
            $('.total').before(periodItemObject);
        }
    }
}

function getRoomItemObject(item, desc, price) {
    return `
        <li class="list-group-item d-flex justify-content-between lh-condensed roomItem">
            <div>
                <h6 class="my-0 room-item-name">` +item+ `</h6>
                <small class="text-muted room-item-desc">` +desc+ `</small>
            </div>
            <span class="text-muted item-price">` +price+ ` PLN</span>
        </li>
    `;
}

function getServiceItemObject(item, selectedIndex, price) {
    return `
        <li class="list-group-item d-flex justify-content-between lh-condensed servicesItem">
            <h6 class="my-0 item-name">` +item+ `</h6>
            <small class="d-none item-index">` +selectedIndex+ `</small>
            <span class="text-muted item-price">` +price+ ` PLN</span>
        </li>
    `;
}

function getDiscountItemObject(code, discount) {
    return `
        <li class="list-group-item d-flex justify-content-between bg-light discountItem">
            <div class="text-success">
                <h6 class="my-0">Promo code</h6>
                <small class="discount-code">` +code+ `</small>
                <small class="d-none discount-value">` +discount+ `</small>
            </div>
            <span class="text-success discount-price">0 PLN</span>
        </li>
    `;
}

function getPeriodItemObject(startDate, endDate, period) {
    let duration = '';
    if (period.years() !== 0) {
        duration += period.years();
        if (period.years() === 1) {
            duration += ' year ';
        } else {
            duration += ' years ';
        }
    }
    if (period.months() !== 0) {
        duration += period.months();
        if (period.months() === 1) {
            duration += ' month ';
        } else {
            duration += ' months ';
        }
    }
    if (period.days() !== 0) {
        duration += period.days();
        if (period.days() === 1) {
            duration += ' day';
        } else {
            duration += ' days';
        }
    }
    return `
        <li class="list-group-item d-flex justify-content-between bg-light periodItem">
            <div style="color: #0c68f1;">
                <h6 class="my-0">Period</h6>
                <small>` +startDate+ ' - ' +endDate+ `</small>
            </div>
            <span class="period" style="color: #0c68f1;">` +duration+ `</span>
        </li>
    `;
}

function getBookingConfirmationModal() {
    return `
        <div aria-hidden="true" aria-labelledby="confirmBookingModalTitle" class="modal fade" id="confirmBookingModal" role="dialog" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmBookingModalTitle">Booking confirmation</h5><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <p class="alert alert-warning booking-confirmation-alert">Please check all the information carefully and process to payment if everything is correct</p>
                        <hr>
                        <div class="row mb-4 confirmation-people-details">
                        </div>
                        <div class="table-responsive-sm">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col" class="text-center">Service</th>
                                    <th scope="col" class="text-center">Description</th>
                                    <th scope="col" class="text-center">Price</th>
                                </tr>
                                </thead>
                                <tbody class="confirmation-items">
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-sm-5 ml-auto">
                                <table class="table table-clear">
                                    <tbody>
                                    <tr>
                                        <td>
                                            <strong>Period</strong>
                                        </td>
                                        <td class="text-right confirmation-period"></td>
                                    </tr>
                                    <tr class="discounted">
                                        <td>
                                            <strong>Subtotal</strong>
                                        </td>
                                        <td class="text-right confirmation-subtotal"></td>
                                    </tr>
                                    <tr class="discounted">
                                        <td>
                                            <strong class="confirmation-discount-value"></strong>
                                        </td>
                                        <td class="text-right confirmation-discount-price"></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Total</strong>
                                        </td>
                                        <td class="text-right">
                                            <strong class="confirmation-total"></strong>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" data-dismiss="modal" type="button" id="abortBtn">Abort</button>
                        <button class="btn btn-success" id="confirmBookingAction">Process to payment</button>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function getPaymentModal() {
    return `
        <div aria-hidden="true" aria-labelledby="paymentModalTitle" class="modal fade" id="paymentModal" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="paymentModalTitle"></h5><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div role="tablist" class="row justify-content-center mb-4 radio-group">
                            <div class="col-sm-3 col-5">
                                <a class='radio mx-auto paymentFormRadio' data-target="#nav-tab-card"><img class="fit-image" src="/assets/images/visa_mastercard.png" width="105px" height="55px" alt="Credit card"></a>
                            </div>
                            <div class="col-sm-3 col-5">
                                <a class='radio mx-auto paymentFormRadio' data-target="#nav-tab-paypal"><img class="fit-image" src="/assets/images/paypal.png" width="105px" height="55px" alt="Credit card"></a>
                            </div>
                            <div class="col-sm-3 col-5">
                                <a class='radio mx-auto paymentFormRadio' data-target="#nav-tab-bitcoin"><img class="fit-image" src="/assets/images/bitcoin.png" width="105px" height="55px" alt=""></a>
                            </div>
                            <div class="col-sm-3 col-5">
                                <a class='radio mx-auto paymentFormRadio' data-target="#nav-tab-bank"><img class="fit-image" src="/assets/images/bank_transfer.png" width="105px" height="55px" alt=""></a>
                            </div>
                        </div>
                        <div class="tab-content">
                            <div id="nav-tab-card" class="tab-pane fade show creditCardTab">
                                <form role="form">
                                    <div class="form-group">
                                        <label for="fullName">Full name</label>
                                        <input type="text" name="fullName" id="fullName" placeholder="Enter card holder full name" required class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="cardNumber">Card number</label>
                                        <div class="input-group">
                                            <input type="text" name="cardNumber" id="cardNumber" placeholder="0000 0000 0000 0000" class="form-control">
                                            <div class="input-group-append">
                                                <span class="input-group-text text-muted">
                                                    <i class="lab la-cc-visa la-lg pr-2"></i>
                                                    <i class="lab la-cc-mastercard la-lg pr-2"></i>
                                                    <i class="lab la-cc-amex la-lg"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row d-flex justify-content-between">
                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <label><span class="hidden-xs">Expiry date</span></label>
                                                <div class="input-group">
                                                    <input type="number" min="1" max="12" value="`+addLeadingZeros(getCurrentMonth())+`" name="expiry-month" id="expiry-month" placeholder="MM" class="form-control" oninput='formatNumberInput(this)'>
                                                    <span class="exp-separator">/</span>
                                                    <input type="number" min="`+addLeadingZeros(getCurrentYear())+`" max="`+addLeadingZeros(Number(getCurrentYear()) + 10)+`" value="`+addLeadingZeros(getCurrentYear())+`" name="expiry-year" id="expiry-year" placeholder="YY" class="form-control" oninput='formatNumberInput(this)'>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group mb-4">
                                                <label data-toggle="tooltip" title="Three-digits code on the back of your card">CVV<i class="las la-question-circle ml-1"></i></label>
                                                <input class="form-control" type="password" name="cvv" id="cvv" maxlength="3" placeholder="Enter CVV code">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-success btn-block rounded-pill shadow-sm creditCardPayAction"><i class="las la-lock la-lg mr-2"></i>Pay </button>
                                </form>
                            </div>
                            <div id="nav-tab-paypal" class="tab-pane fade">
                                <div class="text-center my-3">
                                    <p>PayPal is the fastest way to pay</p>
                                    <a class="btn btn-primary rounded-pill payPalPayAction" target="_blank"><i class="lab la-paypal la-lg mr-2"></i>Pay with PayPal</a>
                                </div>
                                <p class="text-muted">*No account required</p>
                                <p class="text-muted">*Additional fees may apply</p>
                            </div>
                            <div id="nav-tab-bitcoin" class="tab-pane fade">
                                <h6>Pay in Bitcoin cryptocurrency</h6><br>
                                <dl>
                                    <dt>Bitcoin address</dt>
                                    <dd class="bitcoin-address"></dd>
                                </dl>
                                <dl>
                                    <dt>Transfer amount</dt>
                                    <dd class="payment-total-btc"> BTC</dd>
                                </dl>
                                <p class="text-muted">Please note that the above wallet address will be valid for the next 48 hours only.<br>Therefore you have to complete your payment within that time.</p>
                            </div>
                            <div id="nav-tab-bank" class="tab-pane fade">
                                <h6>Pay via traditional bank transfer</h6><br>
                                <dl>
                                    <dt>Bank</dt>
                                    <dd>ING Bank Śląski</dd>
                                </dl>
                                <dl>
                                    <dt>IBAN</dt>
                                    <dd>PL73 1050 1937 1000 0097 0371 5046</dd>
                                </dl>
                                <dl>
                                    <dt>SWIFT</dt>
                                    <dd>INGBPLPW</dd>
                                </dl>
                                <dl>
                                    <dt>Transfer title</dt>
                                    <dd id="transfer-title">Booking #</dd>
                                </dl>
                                <dl>
                                    <dt>Transfer amount</dt>
                                    <dd class="payment-total"> PLN</dd>
                                </dl>
                                <p class="text-muted">Please check carefully if the data you've entered is all correct.<br>Please note that the transfer details must be exactly like above.</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal" type="button">Pay later</button>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function updateTotal() {
    let total = Number(0);
    const itemPrice = $('.item-price');
    itemPrice.each(function(index, currentElement) {
        total += Number($(currentElement).html().substring(0, $(currentElement).html().indexOf(' PLN')));
    })
    if ($('.roomItem').length > 0) {
        const startDate = moment($('#startDate').val(), 'DD/MM/YYYY');
        const endDate = moment($('#endDate').val(), 'DD/MM/YYYY');
        const periodDays = endDate.diff(startDate, 'days');
        total *= Number(periodDays);
    }
    const discountItem = '.discountItem';
    if ($(discountItem).length > 0) {
        if (total === 0) {
            $('.discount-price').html('0 PLN').hide();
        } else {
            const discountValue = $('.discount-value');
            const discount = (Number(discountValue.html()) / 100 * total).toFixed(2);
            if (discount > 0) {
                $('.discount-price').html('-' + discount + ' PLN').show();
                $(discountItem).show();
                total *= (1 - Number(discountValue.html()) / 100);
            }
        }
    }
    $('#total-count').html(itemPrice.length);
    $('#total').html((total === 0 ? total : total.toFixed(2)) + ' PLN');
}

function setPeopleSectionClassRules() {
    $('.first-name').last().rules( "add", {
        required: true,
        minlength: 2,
        maxlength: 30,
        messages: {
            required: "First name is mandatory",
            minlength: "First name must be at least 8 characters long",
            maxlength: "First name must be maximum 30 characters long"
        }
    });
    $('.last-name').last().rules( "add", {
        required: true,
        minlength: 2,
        maxlength: 30,
        messages: {
            required: "Last name is mandatory",
            minlength: "Last name must be at least 8 characters long",
            maxlength: "Last name must be maximum 30 characters long"
        }
    });
    $('.document-id').last().rules( "add", {
        required: true,
        minlength: 7,
        maxlength: 14,
        regex: /^[A-Z0-9 -]*$/,
        messages: {
            required: "Document ID is mandatory",
            minlength: "Document ID must be at least 7 characters long",
            maxlength: "Document ID must be maximum 14 characters long",
            regex: "Document ID must contain only capital letters, digits or - character"
        }
    });
}

function formatNumberInput(input) {
    input.value = Number(input.value);
    if (input.value.length < 2) {
        input.value = '0' + input.value;
    }
}

function getCurrentMonth() {
    return (new Date).getMonth().toString();
}

function getCurrentYear() {
    return (new Date).getFullYear().toString().substring(2);
}

function addLeadingZeros(value) {
    if(value.length < 2) {
        value = '0' + value;
    }
    return value;
}

function setBitcoinDetails(total, bookingId) {
    $.ajax({
        url: '../../process/get_bitcoin_details.php',
        type: "GET",
        data: { "value": total, "id": bookingId },
        dataType: 'JSON',
        success: function (response) {
            $('.payment-total-btc').prepend(response[0]['total']);
            $('.bitcoin-address').html(response[0]['address']);
        }
    });
}
