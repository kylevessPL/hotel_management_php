$(document).ready(function() {
    lightbox.option({
        fadeDuration: 500,
        wrapAround: true,
        disableScrolling: true
    });
    $('.meal-packs-img').on('click', function () {
        $('.meal-packs-gallery-toggle').click();
    });
});