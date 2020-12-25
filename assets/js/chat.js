$(document).ready(function() {
    $.getScript("https://apps.elfsight.com/p/platform.js")
        .done(function() {
            $('a[href="https://www.messenger.com/t/2256078427869983"]').next().remove();
        });
});
