$(document).ready(function() {
    lightbox.option({
        fadeDuration: 500,
        wrapAround: true,
        disableScrolling: true
    });
    $('.card-meals').on('click', function() {
        $('.meal-packs-gallery-toggle').click();
    });
    $('.card-rooms').on('click', function() {
        $('.rooms-gallery-toggle').click();
    });
    $('.card-infrastructure').on('click', function() {
        $('.infrastructure-gallery-toggle').click();
    });
});