$(document).ready(function() {
    $('.logout-action').on('click', function(event) {
        event.preventDefault();
        const href = $(this).attr('href');
        $('#sign-out-modal #sign-out-ref').attr('href', href);
        $('#sign-out-modal').modal();
    });
});