$(document).ready(function () {
    $('#document-type').selectpicker({
        style: '',
        styleBase: 'form-control'
    });
    $.fn.selectpicker.Constructor.BootstrapVersion = '4';
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
