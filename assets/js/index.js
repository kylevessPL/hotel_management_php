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
