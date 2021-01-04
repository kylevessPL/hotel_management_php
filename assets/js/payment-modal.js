function initCreditCardFormValidator() {
    $("form[name='credit-card-form']").validate({
        rules: {
            fullName: {
                required: true,
                minlength: 8,
                maxlength: 30
            },
            cardNumber: {
                required: true,
                minlength: 14,
                maxlength: 19,
                creditcard: true
            },
            expiryMonth: {
                required: true,
                min: 1,
                max: 12,
                rangelength: [1, 2]
            },
            expiryYear: {
                required: true,
                min: function() {
                    return (new Date).getFullYear().toString().substring(2);
                },
                max: function() {
                    return (Number((new Date).getFullYear().toString().substring(2)) + 10).toString();
                },
                rangelength: [2, 2]
            },
            cvv: {
                required: true,
                rangelength: [3, 3],
                number: true
            }
        },
        messages: {
            fullName: {
                required: "Full name is mandatory",
                minlength: "Full name must be at least 8 characters long",
                maxlength: "Full name must be maximum 30 characters long"
            },
            cardNumber: {
                required: "Card number is mandatory",
                minlength: "Credit card not valid",
                maxlength: "Credit card not valid"
            },
            expiryMonth: {
                required: "Expiry month is mandatory",
                min: "Expiry month not valid",
                max: "Expiry month not valid",
                rangelength: "Expiry month not valid",
            },
            expiryYear: {
                required: "Expiry year is mandatory",
                min: "Expiry year not valid",
                max: "Expiry year not valid",
                rangelength: "Expiry year not valid",
            },
            cvv: {
                required: "CVV code is mandatory",
                rangelength: "CVV code not valid",
                number: "CVV code not valid"
            }
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('credit-card-input') || element.hasClass('expiry-date-input')) {
                error.insertAfter(element.parent('.input-group'));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function() {
            setTimeout(function () {
                $('.credit-card-alert').remove();
                $('.creditCardTab').prepend('<p class="alert alert-danger credit-card-alert">Unfortunately, we can\'t process your payment.</p>');
            }, 1000);
            return false;
        }
    });
    new Cleave('#cardNumber', {
        creditCard: true,
        onCreditCardTypeChanged: function (type) {
            type = type.split("15")[0];
            const iconElement = '.credit-card-icon';
            $(iconElement).html('');
            if (~['amex', 'discover', 'jcb', 'maestro', 'mastercard', 'unionpay', 'visa'].indexOf(type) > -1) {
                type = 'default';
            }
            $(iconElement).html('<img role="img" style="width: 48px;" src="/assets/images/' + type + '.svg" alt="' + type.charAt(0).toUpperCase() + type.slice(1) + '"/>');
        }
    });
}

function handleCreditCardInput(element) {
    if (element.value === element.lastValue) {
        return;
    }
    let caretPosition = element.selectionStart;
    const sanitizedValue = element.value.replace(/[^0-9]/gi, '');
    const parts = [];
    for (let i = 0, len = sanitizedValue.length; i < len; i += 4) {
        parts.push(sanitizedValue.substring(i, i + 4));
    }
    for (let i = caretPosition - 1; i >= 0; i--) {
        const c = element.value[i];
        if (c < '0' || c > '9') {
            caretPosition--;
        }
    }
    caretPosition += Math.floor(caretPosition / 4);
    element.value = element.lastValue = parts.join(' ');
    element.selectionStart = element.selectionEnd = caretPosition;
}

function setBitcoinDetails(total, bookingId) {
    $.ajax({
        url: '../../process/get_bitcoin_details',
        type: "GET",
        data: { "value": total, "id": bookingId },
        dataType: 'JSON',
        success: function (response) {
            $('.payment-total-btc').prepend(response[0]['total']);
            $('.bitcoin-address').html(response[0]['address']);
        }
    });
}

function setPayPalPaymentLink(bookingId) {
    $.ajax({
        url: '../../process/get_paypal_payment_link',
        type: "GET",
        data: { "booking-id": bookingId },
        dataType: 'JSON',
        success: function (response) {
            $('.payPalPayAction').attr('href', response[0]['payment-link']);
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
    return ((new Date).getMonth() + 1).toString();
}

function getCurrentYear() {
    return (new Date).getFullYear().toString().substring(2);
}

function addLeadingZeros(value) {
    if (value.length < 2) {
        value = '0' + value;
    }
    return value;
}

function getPaymentModal(allowClose = false) {
    let allowHTML = '';
    if (allowClose === false) {
        allowHTML = 'data-backdrop="static" data-keyboard="false"';
    }
    return `
        <div aria-hidden="true" aria-labelledby="paymentModalTitle" class="modal fade" id="paymentModal" role="dialog" tabindex="-1"` +allowHTML+ `>
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
                                <form role="form" id="credit-card-form" name="credit-card-form">
                                    <div class="form-group">
                                        <label for="fullName">Full name</label>
                                        <input type="text" name="fullName" id="fullName" placeholder="Enter card holder full name" required class="form-control">
                                    </div>
                                    <div class="form-group position-relative">
                                        <label for="cardNumber">Card number</label>
                                        <div class="input-group">
                                            <input type="text" name="cardNumber" id="cardNumber" placeholder="0000 0000 0000 0000" class="form-control credit-card-input" minlength="14" maxlength="19">
                                            <div class="credit-card-icon position-absolute pt-1" style="right: 110px; z-index: 100000000;">
                                                <img role="img" style="width:48px;" src="/assets/images/default.svg" alt="Credit card"/>
                                            </div>
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
                                                    <input type="number" min="1" max="12" value="`+addLeadingZeros(getCurrentMonth())+`" name="expiryMonth" id="expiryMonth" placeholder="MM" class="form-control expiry-date-input" oninput='formatNumberInput(this)'>
                                                    <span class="exp-separator">/</span>
                                                    <input type="number" min="`+addLeadingZeros(getCurrentYear())+`" max="`+addLeadingZeros(Number(getCurrentYear()) + 10)+`" value="`+addLeadingZeros(getCurrentYear())+`" name="expiryYear" id="expiryYear" placeholder="YY" class="form-control expiry-date-input" oninput='formatNumberInput(this)'>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group mb-4">
                                                <label data-toggle="tooltip" title="Three-digits code on the back of your card">CVV<i class="las la-question-circle ml-1"></i></label>
                                                <input class="form-control" type="password" name="cvv" id="cvv" maxlength="3" placeholder="Enter CVV">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-success btn-block rounded-pill shadow-sm creditCardPayAction"><i class="las la-lock la-lg mr-2"></i>Pay </button>
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
