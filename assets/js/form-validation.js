$.validator.addMethod("regex", function(value, element, regexp) {
    return this.optional(element) || regexp.test(value);
});
$.validator.addMethod("futuredate", function (value, element) {
    const now = new Date();
    const myDate = new Date(moment(value, 'DD/MM/YYYY').format());
    return this.optional(element) || myDate > now;
});
$.validator.addMethod("afterstartdate", function (value, element, date) {
    const startDate = new Date(moment(date, 'DD/MM/YYYY').format());
    const endDate = new Date(moment(value, 'DD/MM/YYYY').format());
    return this.optional(element) || startDate < endDate || value === "" || date === "";
});
$(function() {
    $("form[name='form-register']").validate({
        rules: {
            username: {
                required: true,
                minlength: 6,
                maxlength: 16,
                regex: /^[a-zA-Z0-9.-_]*$/,
                remote: {
                    url: 'process/check_username_availability.php',
                    data: {
                        'username': function () {
                            return $('#username').val();
                        }
                    }
                }
            },
            password: {
                required: true,
                minlength: 8,
                maxlength: 15,
                regex: /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/
            },
            password2: {
                required: true,
                equalTo: '[name="password"]'
            },
            email: {
                required: true,
                email: true,
                remote: {
                    url: 'process/check_email_availability.php',
                    data: {
                        'email': function () {
                            return $('#email').val();
                        }
                    }
                }
            }
        },
        messages: {
            username: {
                required: "Username is mandatory",
                minlength: "Username must be at least 6 characters long",
                maxlength: "Username must be maximum 16 characters long",
                regex: "Username must contain only letters, digits or - _ . characters",
                remote: "Username not available"
            },
            password: {
                required: "Password is mandatory",
                minlength: "Password must be at least 8 characters long",
                maxlength: "Password must be maximum 15 characters long",
                regex: "Password must contain at least 1 capital letter and 1 digit"
            },
            password2: {
                required: "Please repeat your password",
                equalTo: "Passwords do not match"
            },
            email: {
                required: "E-mail is mandatory",
                email: "E-mail not valid",
                remote: "There is already a user with this email"
            }
        },
        submitHandler : function(form) {
            form.submit();
        }
    });
});
$(function() {
    $("form[name='form-login']").validate({
        rules: {
            login: {
                required: true
            },
            password: {
                required: true
            }
        },
        messages: {
            login: {
                required: "Login is mandatory"
            },
            password: {
                required: "Password is mandatory"
            }
        },
        errorPlacement: function(error, element) {
            error.appendTo(element.parent().parent());
        },
        submitHandler : function(form) {
            form.submit();
        }
    });
});
$(function() {
    $("form[name='form-customer-details']").validate({
        rules: {
            'first-name': {
                required: true,
                minlength: 2,
                maxlength: 30
            },
            'last-name': {
                required: true,
                minlength: 2,
                maxlength: 30
            },
            'document-id': {
                required: true,
                minlength: 7,
                maxlength: 14,
                regex: /^[A-Z0-9 -]*$/
            }
        },
        messages: {
            'first-name': {
                required: "First name is mandatory",
                minlength: "First name must be at least 8 characters long",
                maxlength: "First name must be maximum 30 characters long"
            },
            'last-name': {
                required: "Last name is mandatory",
                minlength: "Last name must be at least 8 characters long",
                maxlength: "Last name must be maximum 30 characters long"
            },
            'document-id': {
                required: "Document ID is mandatory",
                minlength: "Document ID must be at least 7 characters long",
                maxlength: "Document ID must be maximum 14 characters long",
                regex: "Document ID must contain only capital letters, digits or - character"
            }
        },
        submitHandler : function(form) {
            form.submit();
        }
    });
});
$(function() {
    const validator = $("form[name='booking-form']").validate({
        ignore: ':hidden:not(.selectpicker)',
        rules: {
            startDate: {
                required: true,
                futuredate: true
            },
            endDate: {
                required: true,
                futuredate: true,
                afterstartdate: function () {
                    return $('#startDate').val();
                },
            },
            myAddress: {
                required: true
            },
            bedAmount: {
                required: true
            },
            room: {
                required: true
            }
        },
        messages: {
            startDate: {
                required: "Start date is mandatory",
                futuredate: "Start date must be in the future"
            },
            endDate: {
                required: "End date is mandatory",
                futuredate: "End date must be in the future",
                afterstartdate: "End date cannot be before start date"
            },
            myAddress: {
                required: "Address is mandatory"
            },
            bedAmount: {
                required: "Bed amount is mandatory"
            },
            room: {
                required: "Room is mandatory"
            }
        },
        focusInvalid: false,
        invalidHandler: () => $(this).find(":input.error:first").focus(),
        highlight: function (element) {
            $(element).siblings('.dropdown-toggle').removeClass('valid').addClass('error');
            $(element).closest('input').removeClass('valid').addClass('error');
        },
        unhighlight: function (element) {
            $(element).siblings('.dropdown-toggle').removeClass('error').addClass('valid');
            $(element).closest('input').removeClass('error').addClass('valid');
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('selectpicker')) {
                error.insertAfter(element.siblings(".dropdown-toggle"));
            } else {
                error.insertAfter(element);
            }
        }
    });
    $('select').on('change', function() {
        if (typeof validator !== 'undefined') {
            validator.element($(this));
        }
    });
});
$(function() {
    $("form[name='redeem-code-form']").validate({
        onfocusout: false,
        onkeyup: false,
        onclick: false,
        invalidHandler: function(form, validator) {
            if (validator.numberOfInvalids()) {
                validator.errorList[0].element.focus();
                const discountItem = $('.discountItem');
                if (discountItem.length > 0) {
                    discountItem.remove();
                    updateTotal();
                }
            }
        },
        rules: {
            'promo-code': {
                required: true,
                remote: '../process/check_promo_code_availability.php'
            }
        },
        messages: {
            'promo-code': {
                required: "Promo code not supplied",
                remote: "Promo code expired or doesn't exist"
            }
        },
        errorPlacement: function (error, element) {
            error.insertAfter(element.parent('.redeem-code'));
        },
        submitHandler : function() {
            setDiscountItem();
            return false;
        }
    });
});
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
