$.validator.addMethod("regex", function(value, element, regexp) {
    return this.optional(element) || regexp.test(value);
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
                regex: "Document ID must contain only capital letters, digits or - character",
            }
        },
        submitHandler : function(form) {
            form.submit();
        }
    });
});
$(function() {
    $("form[name='address-form']").validate({
        rules: {
            streetName: {
                required: true,
                minlength: 2,
                maxlength: 30
            },
            houseNumber: {
                required: true,
                regex: /^[0-9a-zA-Z ./]*$/,
                maxlength: 10
            },
            zipCode: {
                required: true,
                regex: /^[0-9 \-]*$/,
                minlength: 2,
                maxlength: 10
            },
            city: {
                required: true,
                minlength: 2,
                maxlength: 30
            }
        },
        messages: {
            streetName: {
                required: "Street name is mandatory",
                minlength: "Street name must be at least 2 characters long",
                maxlength: "Street name must be maximum 30 characters long"
            },
            houseNumber: {
                required: "House number is mandatory",
                regex: "House number must contain only letters and numbers",
                maxlength: "House number must be maximum 10 characters long"
            },
            zipCode: {
                required: "Zip code is mandatory",
                regex: "Zip code must contain only numbers, spaces or a dash",
                minlength: "Zip code must be at least 2 characters long",
                maxlength: "Zip code must be maximum 10 characters long"
            },
            city: {
                required: "City is mandatory",
                minlength: "City must be at least 2 characters long",
                maxlength: "City name must be maximum 30 characters long"
            }
        },
        submitHandler : function(form) {
            form.submit();
        }
    });
});
