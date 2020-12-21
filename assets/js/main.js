$(document).ready(function(){
    $('.dropdown .navbarDropdown').hover(function() {
        const dropdownMenu = $(this).children(".dropdown-menu");
        if(dropdownMenu.is(":visible")){
            dropdownMenu.parent().toggleClass("open");
        }
    });
});
