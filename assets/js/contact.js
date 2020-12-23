$(function() {
    const form = $("form[name='form-contact']");
    form.validate({
        rules: {
            name: {
                required: true,
                minlength: 2,
                maxlength: 30
            },
            email: {
                required: true,
                email: true
            },
            message: {
                required: true,
                minlength: 10
            },
            gRecaptchaResponse: {
                required: function () {
                    return grecaptcha.getResponse() === '';
                }
            }
        },
        messages: {
            name: {
                required: "Name is mandatory",
                minlength: "Name must be at least 2 characters long",
                maxlength: "Name must be maximum 30 characters long"
            },
            email: {
                required: "Email is mandatory",
                email: "Email not valid"
            },
            message: {
                required: "Message is mandatory",
                minlength: "Message must be at least 10 characters long"
            },
            gRecaptchaResponse: {
                required: "Please verify reCaptcha"
            }
        },
        highlight: function (element) {
            $(element).parent().find('iframe').removeClass('valid').addClass('error');
            $(element).closest('input').removeClass('valid').addClass('error');
            $(element).closest('textarea').removeClass('valid').addClass('error');
        },
        unhighlight: function (element) {
            $(element).parent().find('iframe').removeClass('error').addClass('valid');
            $(element).closest('input').removeClass('error').addClass('valid');
            $(element).closest('textarea').removeClass('error').addClass('valid');
        },
        invalidHandler: () => $(this).find(":input.error:first").focus(),
        submitHandler : function(form) {
            form.submit();
        }
    });
    form.data("validator").settings.ignore = "";
});

function handleReCaptchaChange() {
    $("#gRecaptchaResponse").valid();
}
