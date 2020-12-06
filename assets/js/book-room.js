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
}

function setStickySummaryPanel() {
    const height = $('.navbar').height() + 16;
    $('.sticky-top').css('top', height + 'px');
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
        $('#booking-form').valid();
    });
    $('#services').on('change', function() {
        setServiceItem();
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

function setRoomItem() {
    const roomItem = $('.roomItem');
    if (roomItem.length > 0) {
        roomItem.remove();
        updateTotal();
    }
    const room = $('#room');
    if (room.val() !== '' && room.valid() === true) {
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
                updateTotal();
            }
        });
    }
}

function setServiceItem() {
    const servicesItem = $('.servicesItem');
    if (servicesItem.length > 0) {
        servicesItem.remove();
        updateTotal();
    }
    const servicesSelected = $('#services option:selected');
    if (servicesSelected.length > 0) {
        servicesSelected.each(function(index, currentElement) {
            $.ajax({
                url: '../../process/get_service_desc.php',
                type: "GET",
                data: {id: $(currentElement).val() },
                dataType: 'JSON',
                success: function (response) {
                    const serviceItemObject = getServiceItemObject($(currentElement).text(), response[0]['price']);
                    const discountItem = '.discountItem';
                    if ($(discountItem).length > 0) {
                        $(discountItem).before(serviceItemObject);
                    } else {
                        $('.total').before(serviceItemObject);
                    }
                    updateTotal();
                }
            });
        });
    }
}

function setDiscountItem() {
    const discountItem = $('.discountItem');
    if (discountItem.length > 0) {
        discountItem.remove();
        updateTotal();
    }
    const promoCode = $('#promo-code');
    if (promoCode.val() !== '' && promoCode.valid() === true) {
        $.ajax({
            url: '../../process/get_promo_code_discount.php',
            type: "GET",
            data: { 'promo-code': promoCode.val() },
            dataType: 'JSON',
            success: function (response) {
                const discountItemObject = getDiscountItemObject(promoCode.val(), response[0]['discount']);
                $('.total').before(discountItemObject);
                $('.discount-price').hide();
                updateTotal();
            }
        });
    }
}

function getRoomItemObject(item, desc, price) {
    return `
        <li class="list-group-item d-flex justify-content-between lh-condensed roomItem">
            <div>
                <h6 class="my-0">` +item+ `</h6>
                <small class="text-muted">` +desc+ `</small>
            </div>
            <span class="text-muted item-price">` +price+ ` PLN</span>
        </li>
    `;
}

function getServiceItemObject(item, price) {
    return `
        <li class="list-group-item d-flex justify-content-between lh-condensed servicesItem">
            <h6 class="my-0">` +item+ `</h6>
            <span class="text-muted item-price">` +price+ ` PLN</span>
        </li>
    `;
}

function getDiscountItemObject(code, discount) {
    return `
        <li class="list-group-item d-flex justify-content-between bg-light discountItem">
            <div class="text-success">
                <h6 class="my-0">Promo code</h6>
                <small>` +code+ `</small>
                <small class="d-none discount-value">` +discount+ `</small>
            </div>
            <span class="text-success discount-price">0 PLN</span>
        </li>
    `;
}

function updateTotal() {
    let total = Number(0);
    const itemPrice = $('.item-price');
    itemPrice.each(function(index, currentElement) {
        total += Number($(currentElement).html().substring(0, $(currentElement).html().indexOf(' PLN')));
    })
    const discountItem = '.discountItem';
    if ($(discountItem).length > 0) {
        if (total === 0) {
            $('.discount-price').html('0 PLN').hide();
        } else {
            const discount = Number($('.discount-value').html()) / 100 * total;
            if (discount > 0) {
                $('.discount-price').html('-' + discount.toFixed(2) + ' PLN').show();
                $(discountItem).show();
                total -= discount;
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
